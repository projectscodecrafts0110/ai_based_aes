@extends('layouts.admin')

@section('content')
    <div class="container py-5">

        <div class="mb-4">
            <h2>Add New Course</h2>
            <a href="{{ route('admin.courses') }}" class="btn btn-secondary btn-sm">
                ← Back to Courses
            </a>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.courses.update', $course->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    {{-- Main Course --}}
                    <div class="mb-3">
                        <label for="course" class="form-label">Course Name</label>
                        <input type="text" class="form-control" name="course" id="course"
                            value="{{ old('course', $course->course ?? '') }}" required>
                        <small class="text-muted">Do not abbreviate course names (e.g., use "Bachelor of Science in Computer
                            Science" instead
                            of "BSCS")</small>
                    </div>

                    {{-- Allied Courses --}}
                    <div class="mb-3">
                        <label class="form-label">Allied Courses/Fields</label>
                        @php
                            $courseAllied = $course->allied ?? [];
                        @endphp
                        <div id="allied-wrapper">
                            @foreach ($courseAllied as $index => $allied)
                                <div class="input-group mb-2">
                                    <input type="text" name="allied[{{ $index }}]" class="form-control"
                                        placeholder="Enter allied course" value="{{ old("allied.$index", $allied) }}">
                                    <button type="button" class="btn btn-danger remove-field">
                                        Remove
                                    </button>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-success" id="add-allied">
                        +
                    </button>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            Save Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Simple JS for dynamic fields --}}
    <script>
        document.getElementById('add-allied').addEventListener('click', function() {
            let wrapper = document.getElementById('allied-wrapper');

            let div = document.createElement('div');
            div.classList.add('input-group', 'mb-2');

            div.innerHTML = `
        <input type="text" name="allied[]" class="form-control" placeholder="Enter allied course">
        <button type="button" class="btn btn-danger remove-field">Remove</button>
    `;

            wrapper.appendChild(div);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-field')) {
                e.target.closest('.input-group').remove();
            }
        });
    </script>

@endsection
