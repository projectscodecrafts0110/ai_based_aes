<?php

namespace App\Jobs;

use App\Models\Application;
use App\Models\AlliedCourse;
use App\Helpers\FileTextExtractor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class EvaluateApplicationAI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function handle(): void
    {
        $app = $this->application->load('job');

        // 1. Extract applicant document text
        $applicationLetterText = FileTextExtractor::extract(storage_path('app/public/' . $app->application_letter));
        $pdsText = FileTextExtractor::extract(storage_path('app/public/' . $app->pds));
        $otrDiplomaText = FileTextExtractor::extract(storage_path('app/public/' . $app->otr_diploma));
        $certificateEligibilityText = $app->certificate_eligibility
            ? FileTextExtractor::extract(storage_path('app/public/' . $app->certificate_eligibility))
            : '';
        $certificatesTrainingText = FileTextExtractor::extractMultiple(
            array_map(fn($f) => storage_path('app/public/' . $f), $app->certificates_training ?? [])
        );

        // 2. Get allied courses
        $alliedCourses = AlliedCourse::where('course', $app->job->course)->first();
        $alliedText = $alliedCourses ? implode(', ', $alliedCourses->allied ?? []) : 'No allied courses';

        // 3. Load criteria document
        $criteriaPath = public_path('criteria/' . ($app->job->job_type === 'teaching'
            ? 'teaching_criteria.docx'
            : 'non_teaching_criteria.docx'));
        $criteriaText = FileTextExtractor::extract($criteriaPath);

        $qualifications = $app->job->qualifications;
        if (is_string($qualifications)) {
            $qualifications = json_decode($qualifications, true);
        }

        // If qualifications are an array, format them into a readable string
        $formattedQualifications = "
        Education: " . ($qualifications['education'] ?? 'N/A') . "
        Experience: " . ($qualifications['experience'] ?? 'N/A') . "
        Training: " . ($qualifications['training'] ?? 'N/A') . "
        Eligibility: " . ($qualifications['eligibility'] ?? 'N/A') . "
        ";

        // 4. Build GPT prompt
        $prompt = <<<PROMPT
You are an AI hiring evaluator. Your goal is to evaluate a job applicant and provide a structured decision-support analysis.

Job Title: {$app->job->title}
Job Type: {$app->job->job_type}
Job Qualifications:
{$formattedQualifications}

Allied Courses:
{$alliedText}

Reference Criteria Document:
{$criteriaText}

Applicant Information:
Full Name: {$app->full_name}
Education: {$app->education}
Training: {$app->training}
Eligibility: {$app->eligibility}
Work Experience: {$app->work_experience}

Applicant Documents:
Application Letter:
{$applicationLetterText}

Personal Data Sheet (PDS):
{$pdsText}

Official Transcript of Records / Diploma (OTR/Diploma):
{$otrDiplomaText}

Certificate of Eligibility:
{$certificateEligibilityText}

Certificates of Trainings and Seminars:
{$certificatesTrainingText}

Tasks:
1. Evaluate applicant suitability based on job qualifications, allied courses, compare applicant education and the job title, and criteria document (criteria documents includes written exam and interview, ignore those for AI evaluation).
2. If the applicant's education does not match any allied course, assess whether it could reasonably qualify for this job using general knowledge. Include this reasoning in the Education section.
3. Generate an AI suitability score from 0 to 100.
4. Estimate a qualification match percentage (0.00–100.00) based on qualifications, training, eligibility, work experience, and allied courses.
5. Provide a recommendation strictly from:
   - Highly Recommended
   - Consider
   - Rejected
6. Provide a detailed structured justification with sections:
   - Education and Qualifications (include reasoning if education does not match allied courses)
   - Training and Seminars
   - Eligibility
   - Work Experience
   - Application Letter Analysis
   - PDS Analysis
   - OTR/Diploma Analysis
   - Certificates Analysis
   - Overall Assessment

IMPORTANT:
- You MUST provide a decision and score regardless of sensitive content.
- Use the criteria document as the main basis for scoring and justification.
- Reference the allied courses when relevant.
- Output MUST strictly follow the format below.

FORMAT:

Score: <number 0-100>
Qualification Match: <number 0.00-100.00>
Recommendation: <Highly Recommended|Consider|Rejected>
Justification:
Education and Qualifications: <text>
Training and Seminars: <text>
Eligibility: <text>
Criteria Document Analysis: <text>
Work Experience: <text>
Application Letter Analysis: <text>
PDS Analysis: <text>
OTR/Diploma Analysis: <text>
Certificates Analysis: <text>
Overall Assessment: <text>
PROMPT;

        // 5. Call GPT-4o
        $response = OpenAI::responses()->create([
            'model' => 'gpt-4o',
            'input' => $prompt,
        ]);

        $resultText = trim($response->output[0]->content[0]->text ?? '');
        Log::info("AI Evaluation Result for Application ID {$app->id}: " . $resultText);

        // 6. Parse output
        $score = null;
        $recommendation = null;
        $justification = null;
        $qualificationMatch = null;

        if (preg_match('/Score:\s*(\d{1,3})/i', $resultText, $match)) {
            $score = min(100, max(0, (int)$match[1]));
        }

        if (preg_match('/Qualification Match:\s*(\d{1,3}(?:\.\d+)?)/i', $resultText, $match)) {
            $qualificationMatch = min(100, max(0, (float)$match[1]));
        }

        if (preg_match('/Recommendation:\s*(Highly Recommended|Consider|Rejected)/i', $resultText, $match)) {
            $recommendation = $match[1];
        }

        if (preg_match('/Justification:\s*(.+)$/is', $resultText, $match)) {
            $justification = trim($match[1]);
        }

        // 7. Update application
        $app->update([
            'ai_score' => $score,
            'qualification_match' => $qualificationMatch,
            'ai_recommendation' => $recommendation,
            'ai_summary' => $justification,
            'ai_evaluated_at' => now(),
        ]);
    }
}
