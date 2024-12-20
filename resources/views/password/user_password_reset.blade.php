@extends('password.app')

@section('content')
    <style>
        .error {
            border-color: red;
        }
        .error-message {
            color: red;
            font-size: 0.875em;
        }
    </style>

    <form id="resetPasswordForm" action="{{ route('password-reset') }}" method="post">
        @csrf
        @method('PUT')
        <input type="hidden" name="token" value="{{ $token }}">
        <img src="" id="signupLogo"/>

        <h2 class="formTitle">Reset Password</h2>

        <div class="inputDiv">
            <label class="inputLabel">New Password</label>
            <input type="password" id="newPassword" name="new_password" >
            <span class="error-message" id="newPasswordError"></span>
        </div>

        <div class="inputDiv">
            <label class="inputLabel">Confirm Password</label>
            <input type="password" id="confirmPassword" name="confirm_password" class="">
            <span class="error-message" id="confirmPasswordError"></span>
        </div>

        <div class="buttonWrapper">
            <button type="submit" class="submitButton pure-button pure-button-primary">
                <span>Submit</span>
            </button>
        </div>
    </form>

    <script>
        document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let isValid = true;
            let newPassword = document.getElementById('newPassword').value.trim();
            let confirmPassword = document.getElementById('confirmPassword').value.trim();

            // Clear previous error messages and styles
            document.getElementById('newPasswordError').textContent = '';
            document.getElementById('confirmPasswordError').textContent = '';
            document.getElementById('newPassword').classList.remove('error');
            document.getElementById('confirmPassword').classList.remove('error');

            // Check if new_password is empty or less than 6 characters
            if (!newPassword) {
                document.getElementById('newPasswordError').textContent = 'New password is required.';
                document.getElementById('newPassword').classList.add('error');
                isValid = false;
            } else if (newPassword.length < 6) {
                document.getElementById('newPasswordError').textContent = 'Password must be at least 6 characters long.';
                document.getElementById('newPassword').classList.add('error');
                isValid = false;
            }

            // Check if confirm_password is empty
            if (!confirmPassword) {
                document.getElementById('confirmPasswordError').textContent = 'Confirm password is required.';
                document.getElementById('confirmPassword').classList.add('error');
                isValid = false;
            }

            // Check if passwords match
            if (newPassword && confirmPassword && newPassword !== confirmPassword) {
                document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
                document.getElementById('confirmPassword').classList.add('error');
                isValid = false;
            }

            // If the form is valid, submit it
            if (isValid) {
                this.submit();
            }
        });
    </script>
@endsection
