@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <div class="container py-5">
        <div class="row g-4">

            <!-- LEFT COLUMN: Contact Information -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h2 class="fw-bold mb-3">Contact Information</h2>

                        <p class="text-muted mb-1"><strong>Address:</strong> Ilocos Sur Polytechnic State College (Main
                            Campus), Sta. Maria, Ilocos Sur, Philippines</p>
                        <p class="text-muted mb-1"><strong>Phone:</strong> (+63) XXX-XXXX</p>
                        <p class="text-muted mb-1"><strong>Email:</strong> info@ispsc.edu.ph</p>
                        <p class="text-muted"><strong>Office Hours:</strong> Monday – Friday, 8:00 AM – 5:00 PM</p>

                        <hr>

                        <h5 class="fw-semibold mt-4">AES Support</h5>
                        <p class="text-muted">
                            For inquiries or technical assistance regarding the AES platform, contact our support team at:
                            <br>
                            <strong>Email:</strong> support@aes.ispsc.edu.ph
                        </p>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Contact Form / Optional -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h2 class="fw-bold mb-3">Send Us a Message</h2>

                        <p class="text-muted">
                            Fill out the form below and our team will respond as soon as possible.
                        </p>

                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Your Name">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" placeholder="you@example.com">
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="5" placeholder="Type your message here"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
