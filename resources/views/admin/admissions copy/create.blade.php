@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h2>Student Admission Form</h2>
                        <div class="position-absolute top-0 end-0 mt-4 me-4">
                            <a href="{{ route('registrar.admissions.index') }}" class="text-dark" style="font-size: 1.25rem;">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                        <br>

                        <form action="{{ route('registrar.admissions.store') }}" method="POST">
                            @csrf

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

                            <!-- Student Information -->
                            <h5 class="mb-3 text-primary">Student Information</h5>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="student_id"><strong>Student ID</strong></label>
                                    <input type="text" name="student_id" id="student_id"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email"><strong>Email</strong></label>
                                    <input type="email" name="email" id="email" class="form-control form-control-sm"
                                        required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="first_name"><strong>First Name</strong></label>
                                    <input type="text" name="first_name" id="first_name"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="middle_name"><strong>Middle Name</strong></label>
                                    <input type="text" name="middle_name" id="middle_name"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4">
                                    <label for="last_name"><strong>Last Name</strong></label>
                                    <input type="text" name="last_name" id="last_name"
                                        class="form-control form-control-sm" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="suffix"><strong>Suffix (Optional)</strong></label>
                                    <input type="text" name="suffix" id="suffix" class="form-control form-control-sm"
                                        placeholder="e.g. Jr., Sr., III">
                                </div>
                                <div class="col-md-6">
                                    <label for="contact"><strong>Contact Number</strong></label>
                                    <input type="text" name="contact" id="contact" class="form-control form-control-sm"
                                        required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label for="address"><strong>Address</strong></label>
                                    <input type="text" name="address" id="address" class="form-control form-control-sm"
                                        required>
                                </div>
                            </div>

                            <!-- Guardian Information -->
                            <h5 class="mb-3 text-primary">Guardian Information</h5>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="guardian_name"><strong>Guardian Full Name</strong></label>
                                    <input type="text" name="guardian_name" id="guardian_name"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="guardian_contact"><strong>Guardian Contact Number</strong></label>
                                    <input type="text" name="guardian_contact" id="guardian_contact"
                                        class="form-control form-control-sm" required>
                                </div>
                            </div>

                            <!-- Academic Info -->
                            <h5 class="mb-3 text-primary">Academic Information</h5>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="last_school_attended"><strong>Previous School</strong></label>
                                    <input type="text" name="last_school_attended" id="last_school_attended"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-md-6">
                                    <label for="year"><strong>Year</strong></label>
                                    <select name="year" id="year" class="form-control form-control-sm" required>
                                        <option value="">-- Select Year --</option>
                                        <option value="Grade 7">Grade 7</option>
                                        {{-- <option value="Grade 8">Grade 8</option>
                                        <option value="Grade 9">Grade 9</option>
                                        <option value="Grade 10">Grade 10</option> --}}
                                        <option value="Grade 11">Grade 11</option>
                                        {{-- <option value="Grade 12">Grade 12</option> --}}
                                    </select>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="strand"><strong>Strand (Optional)</strong></label>
                                    <select name="strand" id="strand" class="form-control form-control-sm">
                                        <option value="">-- Select Strand --</option>
                                        <option value="STEM">STEM</option>
                                        <option value="ABM">ABM</option>
                                        <option value="HUMSS">HUMSS</option>
                                        <option value="TVL">TVL</option>
                                        <option value="GAS">GAS</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex float-end">
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
