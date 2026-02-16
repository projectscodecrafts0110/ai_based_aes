@extends('layouts.app')

@section('title', 'Job Preference')

@section('content')
    <div class="container py-5">
        <h3 class="fw-bold mb-4">Job Preference</h3>

        <form method="POST" action="{{ route('apply') }}">
            @csrf
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Job Type</label>
                            <select name="job_type" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Teaching">Teaching</option>
                                <option value="Non-Teaching">Non-Teaching</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Employment Status</label>
                            <select name="employment_status" class="form-select" required>
                                <option value="" selected disabled>Select</option>
                                <option value="Permanent">Permanent</option>
                                <option value="Part_Time">Part-Time</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Campus</label>
                            <select name="campus" class="form-select" required>
                                <option value="" selected disabled>Select Campus</option>
                                @foreach ($campuses as $campus)
                                    <option value="{{ $campus }}">{{ $campus }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Department / College</label>
                            <select name="department" id="department" class="form-select" required>
                                <option value="" selected disabled>Select Department</option>
                            </select>
                        </div>

                    </div>

                    <div class="mt-4 text-end">
                        <button class="btn btn-primary">
                            Find Available Jobs
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const campusSelect = document.querySelector('select[name="campus"]');
        const departmentSelect = document.getElementById('department');

        if (!campusSelect) return; // safety guard

        campusSelect.addEventListener('change', function() {
            const campus = this.value;

            departmentSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(`/api/departments/${encodeURIComponent(campus)}`)
                .then(res => res.json())
                .then(data => {
                    departmentSelect.innerHTML = '<option value="">Select Department</option>';

                    data.forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept;
                        option.textContent = dept;
                        departmentSelect.appendChild(option);
                    });
                });
        });
    });
</script>
