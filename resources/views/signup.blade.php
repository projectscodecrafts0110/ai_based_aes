<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<style>
    body {
        background-color: rgb(112, 6, 6)
    }

    #background {
        background-image: url('{{ asset('images/signup-bg.jpg') }}');
        background-size: cover;
        background-position: center;
    }
</style>

<body>
    <main class="container-fluid vh-100">
        <div class="row h-100">

            <!-- LEFT: Image Section (70%) -->
            <div class="col-lg-8 d-flex justify-content-center align-items-center" id="background">
            </div>

            <!-- RIGHT: Login Card (30%) -->
            <div class="col-lg-4 d-flex justify-content-center align-items-center shadow-lg">
                <div class="card shadow-lg border-0 rounded w-100 mx-4" style="max-width: 420px;">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <img src="{{ asset('images/ispsc_logo.png') }}" alt="ISPSC Logo" class="img-fluid"
                                style="max-width: 20%">
                            <h4 class="mt-2">SMARTHIRE Sign Up</h3>
                        </div>
                        <hr>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                    required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                                @error('password')
                                    <small class="text-danger">Password Not Match</small>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacyCheck"
                                        name="privacy_agreement" value="1" required data-bs-toggle="tooltip"
                                        title="Please read the Data Privacy Notice before agreeing.">

                                    <label class="form-check-label small" for="privacyCheck">
                                        I have read and agree to the
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">
                                            Data Privacy Notice
                                        </a>
                                    </label>
                                </div>

                                @error('privacy_agreement')
                                    <small class="text-danger">You must agree before registering.</small>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    Create Account
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <small>
                                    Already have an account?
                                    <a href="{{ route('login') }}">Login</a>
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <!-- Data Privacy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">SmartHire Data Privacy Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <p><strong>Introduction</strong></p>
                    <p>
                        SmartHire – AI-Based Applicant Evaluation System is committed to protecting your personal
                        data in compliance with the Data Privacy Act of 2012 (Republic Act No. 10173).
                    </p>

                    <p><strong>Information We Collect</strong></p>
                    <p>
                        We collect personal information such as your full name, email address,
                        login credentials, and other application-related data necessary for
                        recruitment, screening, and evaluation purposes.
                    </p>

                    <p><strong>Purpose of Data Collection</strong></p>
                    <p>
                        Your personal information is collected and processed to:
                    </p>
                    <ul>
                        <li>Facilitate account creation and authentication</li>
                        <li>Evaluate applicant qualifications using AI-assisted screening tools</li>
                        <li>Communicate updates regarding your application</li>
                        <li>Maintain system security and prevent fraud</li>
                    </ul>

                    <p><strong>Data Protection & Security</strong></p>
                    <p>
                        SmartHire implements appropriate technical and organizational
                        security measures to protect your personal information against
                        unauthorized access, disclosure, alteration, or destruction.
                    </p>

                    <p><strong>Data Retention</strong></p>
                    <p>
                        Your information will be retained only for as long as necessary
                        for recruitment and legal compliance purposes, after which it will
                        be securely deleted or anonymized.
                    </p>

                    <p><strong>Your Rights</strong></p>
                    <p>
                        Under the Data Privacy Act, you have the right to access, correct,
                        object to processing, and request deletion of your personal data,
                        subject to legal limitations.
                    </p>

                    <p><strong>Consent</strong></p>
                    <p>
                        By registering an account in SmartHire, you voluntarily provide
                        your personal information and consent to its collection,
                        processing, and storage in accordance with this Data Privacy Notice.
                    </p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="button" class="btn btn-success" id="agreePrivacyBtn">
                        I Understand & Agree
                    </button>
                </div>
            </div>
        </div>
    </div>
    <footer class="text-center py-3 small" style="background-color: rgb(223, 223, 32)">
        © {{ date('Y') }} SMARTHIRE · ISPSC
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let consentGiven = false;

            const privacyCheck = document.getElementById("privacyCheck");
            const agreeBtn = document.getElementById("agreePrivacyBtn");
            const privacyModal = document.getElementById("privacyModal");

            // Enable Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // If user tries to manually check checkbox
            privacyCheck.addEventListener("click", function(e) {
                if (!consentGiven) {
                    e.preventDefault();
                    alert("Please click 'I Understand & Agree' inside the Data Privacy Notice.");
                }
            });

            // When user clicks I Understand button
            agreeBtn.addEventListener("click", function() {

                consentGiven = true;

                privacyCheck.checked = true;

                // Close modal properly
                const modalInstance = bootstrap.Modal.getInstance(privacyModal);
                modalInstance.hide();
            });

        });
    </script>
</body>

</html>
