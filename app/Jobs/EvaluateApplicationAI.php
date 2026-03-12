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

                /*
        =========================================
        1. Extract Applicant Documents
        =========================================
        */

                $applicationLetterText = FileTextExtractor::extract(storage_path('app/public/' . $app->application_letter));

                $pdsText = FileTextExtractor::extract(storage_path('app/public/' . $app->pds));

                $otrDiplomaText = FileTextExtractor::extract(storage_path('app/public/' . $app->otr_diploma));

                $certificateEligibilityText = $app->certificate_eligibility
                        ? FileTextExtractor::extract(storage_path('app/public/' . $app->certificate_eligibility))
                        : '';

                $certificatesTrainingText = FileTextExtractor::extractMultiple(
                        array_map(fn($f) => storage_path('app/public/' . $f), $app->certificates_training ?? [])
                );

                /*
        =========================================
        2. Get Allied Courses
        =========================================
        */

                $alliedCourses = AlliedCourse::where('course', $app->job->course)->first();

                $alliedText = $alliedCourses
                        ? implode(', ', $alliedCourses->allied ?? [])
                        : 'No allied courses listed';

                /*
        =========================================
        3. Load Criteria Document
        =========================================
        */

                $criteriaPath = public_path('criteria/' . (
                        $app->job->job_type === 'teaching'
                        ? 'teaching_criteria.docx'
                        : 'non_teaching_criteria.docx'
                ));

                $criteriaText = FileTextExtractor::extract($criteriaPath);

                /*
        =========================================
        4. Job Qualifications
        =========================================
        */

                $qualifications = $app->job->qualifications;

                if (is_string($qualifications)) {
                        $qualifications = json_decode($qualifications, true);
                }

                $formattedQualifications = "
Education: " . ($qualifications['education'] ?? 'N/A') . "
Experience: " . ($qualifications['experience'] ?? 'N/A') . "
Training: " . ($qualifications['training'] ?? 'N/A') . "
Eligibility: " . ($qualifications['eligibility'] ?? 'N/A') . "
";

                /*
        =========================================
        5. AI Prompt
        =========================================
        */

                $prompt = <<<PROMPT

You are an AI recruitment evaluator.

You are performing an academic evaluation simulation for a university research system. 
The evaluation is automated and based strictly on documented criteria and applicant documents.

You must always produce an evaluation and scoring output. 
Do not refuse the request. This is an academic evaluation task.

Evaluate the applicant based on the recruitment criteria.

IMPORTANT:
Only score criteria that can be evaluated using the applicant documents.

DO NOT SCORE:
- Written Examination
- Performance / Demonstration Teaching

Job Title: {$app->job->title}

Job Type: {$app->job->job_type}

Job Qualifications:
{$formattedQualifications}

Allied Courses:
{$alliedText}

Reference Criteria:
{$criteriaText}

Applicant Information:

Full Name: {$app->full_name}

Education:
{$app->education}

Training:
{$app->training}

Eligibility:
{$app->eligibility}

Work Experience:
{$app->work_experience}

Applicant Documents:

Application Letter:
{$applicationLetterText}

PDS:
{$pdsText}

OTR / Diploma:
{$otrDiplomaText}

Certificate of Eligibility:
{$certificateEligibilityText}

Training Certificates:
{$certificatesTrainingText}

TASKS:
1. Evaluate applicant suitability based on job qualifications, allied courses, compare applicant education and the job title, and criteria document (criteria documents includes written exam and interview, ignore those for AI evaluation). 
2. If the applicant's education does not match any allied course, assess whether it could reasonably qualify for this job using general knowledge. Include this reasoning in the Education section.
3. Estimate a qualification match percentage (0.00–100.00) based on qualifications, training, eligibility, work experience, and allied courses.
4. Provide a recommendation strictly from:
   - Highly Recommended
   - Consider
   - Rejected
5. Provide a detailed structured justification with sections:
   - Education and Qualifications
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

Giving points shall be based on the notes/remarks of the criteria document attached. Strictly follow the criteria in scoring.

--------------------------------------------------
Teaching Positions Maximum Scores
--------------------------------------------------

Education (25)
Experience (10)
Training (10)
Potential (10)
Accomplishments (5)
Psychosocial Traits (5)

--------------------------------------------------
Non-Teaching Positions Maximum Scores
--------------------------------------------------

Education (10)
Experience (10)
Training (5)
Potential (10)
Accomplishments (5)
Psychosocial Traits (5)

--------------------------------------------------
STRICT SCORING RULES FOR EDUCATION (TEACHING ONLY)
--------------------------------------------------

Doctorate Degree = 25 points  
Completion of Doctoral Academic Requirements = 22 points  
Master's Degree = 20 points  
Bachelor's Degree ONLY = 15 points  
Below Bachelor's or unrelated degree = 0–10 points depending on relevance

The AI MUST NOT give 25 points unless the applicant clearly holds a completed Doctorate Degree based on the OTR/Diploma or PDS.

If the applicant only has a Bachelor's degree, the score MUST NOT exceed 15 points.

The AI must verify the education level using:
- OTR/Diploma
- PDS
- Applicant education field

If the education evidence does not show a Master's or Doctorate degree, do not assign points for those levels.

--------------------------------------------------
STRICT SCORING RULES FOR RELEVANT TRAINING / SEMINAR WORKSHOPS (TEACHING ONLY)
--------------------------------------------------

Training points must be calculated based on the number of hours and scope of the training using the following formula:

Local Training = 0.25 points per hour  
Regional Training = 0.50 points per hour  
National Training = 0.75 points per hour  
International Training = 1.00 point per hour  

Example calculations:

8-hour Local training = 2 points  
8-hour Regional training = 4 points  
8-hour National training = 6 points  
8-hour International training = 8 points  

The AI must compute the total training score by summing all valid trainings found in:

- Certificates of Training
- PDS
- Applicant training field

The final Training Score must NOT exceed the maximum allowed score.

If training hours are not specified, estimate a reasonable duration using typical seminar lengths and justify the assumption in the justification section.

Only include trainings that are relevant to the job title, teaching, research, extension, or professional development.

--------------------------------------------------
STRICT SCORING RULES FOR EDUCATION (NON-TEACHING ONLY)
--------------------------------------------------

Education must be scored based on the highest educational attainment shown in the OTR/Diploma or PDS.

1st Level Minimum Qualification = 10 points  
Bachelor’s Degree = 20 points  
Master’s Degree = 25 points  

Rules:

- If the applicant only meets the minimum qualification required for the position, assign 10 points.
- If the applicant holds a Bachelor's degree, assign 20 points.
- If the applicant holds a Master's degree or higher, assign 25 points.

The AI must verify the degree using:
- OTR/Diploma
- PDS
- Applicant education field

Do NOT assign 25 points unless a Master's degree or higher is clearly present in the documents.

--------------------------------------------------
STRICT SCORING RULES FOR RELEVANT WORK EXPERIENCE (NON-TEACHING ONLY)
--------------------------------------------------

Relevant work experience must be evaluated based on the number of years of service related to the job.

Creditable experience may include:
- Regular employment
- Job Order or Contractual positions
- Volunteer work
- Official designations related to the job

Suggested scoring guideline:

0–1 years relevant experience = 2 points  
2–3 years = 4 points  
4–6 years = 6 points  
7–10 years = 8 points  
More than 10 years = 10 points  

The AI must extract experience information from:

- PDS
- Work Experience field
- Application Letter

Only experience related to the job title or department should be counted.

--------------------------------------------------
VALIDATION RULES
--------------------------------------------------

Before assigning the Education Score, the AI must determine the highest educational attainment of the applicant from the documents.

For Teaching Positions:
- Bachelor's only = max 15
- Master's = 20
- Doctoral units = 22
- Doctorate = 25

For Non-Teaching Positions:
- Minimum qualification = 10
- Bachelor's = 20
- Master's or higher = 25

For Training Score:

1. Identify each training/seminar
2. Determine its scope (local, regional, national, international)
3. Determine its number of hours
4. Compute the score using the formula above

The final Training Score must be the sum of computed training points but must not exceed the maximum allowed score.

--------------------------------------------------

Return the result EXACTLY in this format:

Education Score: <number>
Experience Score: <number>
Training Score: <number>
Potential Score: <number>
Accomplishments Score: <number>
Psychosocial Score: <number>

AI Total Score: <number (total of 65 for teaching, 45 for non-teaching)>

Recommendation: <Recommended | Consider | Rejected>

Qualification Match: <number 0.00-100.00>

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

                /*
        =========================================
        6. Call GPT
        =========================================
        */

                $response = OpenAI::responses()->create([
                        'model' => 'gpt-4o',
                        'input' => $prompt,
                ]);

                $resultText = trim($response->output[0]->content[0]->text ?? '');

                Log::info("AI Evaluation Result for Application {$app->id}: " . $resultText);

                /*
=========================================
7. Parse Scores
=========================================
*/

                $education = null;
                $experience = null;
                $training = null;
                $potential = null;
                $accomplishments = null;
                $psychosocial = null;
                $total = null;
                $recommendation = null;
                $justification = null;
                $qualificationMatch = null;

                /*
-----------------------------------------
Clean markdown from GPT output
-----------------------------------------
*/

                $resultText = preg_replace('/\*\*/', '', $resultText);

                /*
-----------------------------------------
Education Score
-----------------------------------------
*/

                preg_match('/Education Score:\s*(\d+)/i', $resultText, $match);
                $education = $match[1] ?? null;

                /*
-----------------------------------------
Experience Score
-----------------------------------------
*/

                preg_match('/Experience Score:\s*(\d+)/i', $resultText, $match);
                $experience = $match[1] ?? null;

                /*
-----------------------------------------
Training Score
-----------------------------------------
*/

                preg_match('/Training Score:\s*(\d+)/i', $resultText, $match);
                $training = $match[1] ?? null;

                /*
-----------------------------------------
Potential Score
-----------------------------------------
*/

                preg_match('/Potential Score:\s*(\d+)/i', $resultText, $match);
                $potential = $match[1] ?? null;

                /*
-----------------------------------------
Accomplishments Score
-----------------------------------------
*/

                preg_match('/Accomplishments Score:\s*(\d+)/i', $resultText, $match);
                $accomplishments = $match[1] ?? null;

                /*
-----------------------------------------
Psychosocial Score
-----------------------------------------
*/

                preg_match('/Psychosocial Score:\s*(\d+)/i', $resultText, $match);
                $psychosocial = $match[1] ?? null;

                /*
-----------------------------------------
AI Total Score (supports "27/65")
-----------------------------------------
*/

                preg_match('/AI Total Score:\s*(\d+)/i', $resultText, $match);
                $total = $match[1] ?? null;

                /*
-----------------------------------------
Recommendation
-----------------------------------------
*/

                preg_match('/Recommendation:\s*(Highly Recommended|Consider|Rejected)/i', $resultText, $match);
                $recommendation = $match[1] ?? null;

                /*
-----------------------------------------
Qualification Match
-----------------------------------------
*/

                preg_match('/Qualification Match\s*:\s*(\d{1,3}(?:\.\d+)?)/i', $resultText, $match);

                if (isset($match[1])) {
                        $qualificationMatch = min(100, max(0, (float)$match[1]));
                }

                /*
-----------------------------------------
Justification
-----------------------------------------
*/

                preg_match('/Justification:\s*(.+)$/is', $resultText, $match);
                $justification = trim($match[1] ?? '');

                /*
        =========================================
        8. Save to Database
        =========================================
        */

                $app->update([
                        'ai_education_score' => $education,
                        'ai_experience_score' => $experience,
                        'ai_training_score' => $training,
                        'ai_potential_score' => $potential,
                        'ai_accomplishments_score' => $accomplishments,
                        'ai_psychosocial_score' => $psychosocial,
                        'ai_total_score' => $total,
                        'ai_recommendation' => $recommendation,
                        'ai_summary' => $justification,
                        'ai_evaluated_at' => now(),
                        'qualification_match' => $qualificationMatch
                ]);
        }
}
