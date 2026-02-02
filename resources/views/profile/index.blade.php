@extends('layouts.index')

@section('title', 'My Profile')

@section('subheader')
    @component('layouts.partials._breadcrumbs')
        @slot('title')
            My Profile
        @endslot
        @slot('action')
            <span class="text-muted font-weight-bold">Account Settings</span>
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">
                                Personal Information
                                <span class="d-block text-muted pt-2 font-size-sm">Update your account details</span>
                            </h3>
                        </div>
                    </div>
                    <form id="profileForm">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Full Name:</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="name" placeholder="Enter full name"
                                        value="{{ auth()->user()->name }}" required />
                                    <span class="form-text text-muted">Please enter your full name.</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Email Address:</label>
                                <div class="col-lg-6">
                                    <input type="email" class="form-control" name="email" placeholder="Enter email"
                                        value="{{ auth()->user()->email }}" required />
                                    <span class="form-text text-muted">We'll never share your email with anyone else.</span>
                                </div>
                            </div>

                            <div class="separator separator-dashed my-10"></div>

                            <h3 class="font-size-lg text-dark font-weight-bold mb-6">Change Password:</h3>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Current Password:</label>
                                <div class="col-lg-6">
                                    <input type="password" class="form-control" name="current_password"
                                        placeholder="Current password" />
                                    <span class="form-text text-muted">Leave blank if you don't want to change
                                        password.</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">New Password:</label>
                                <div class="col-lg-6">
                                    <input type="password" class="form-control" name="new_password"
                                        placeholder="New password" />
                                    <span class="form-text text-muted">Minimum 8 characters.</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Confirm Password:</label>
                                <div class="col-lg-6">
                                    <input type="password" class="form-control" name="new_password_confirmation"
                                        placeholder="Confirm new password" />
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-success mr-2">Save Changes</button>
                                    <button type="reset" class="btn btn-secondary">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();

                const formData = $(this).serialize();
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();

                submitBtn.html('<i class="spinner spinner-white spinner-sm"></i> Saving...').prop(
                    'disabled', true);

                $.ajax({
                    url: '{{ route('profile.update') }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success(response.message || 'Profile updated successfully!');

                        // Clear password fields
                        $('input[name="current_password"]').val('');
                        $('input[name="new_password"]').val('');
                        $('input[name="new_password_confirmation"]').val('');

                        submitBtn.html(originalText).prop('disabled', false);
                    },
                    error: function(xhr) {
                        let message = 'Failed to update profile';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            message = Object.values(errors).flat().join('<br>');
                        }

                        toastr.error(message);
                        submitBtn.html(originalText).prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
