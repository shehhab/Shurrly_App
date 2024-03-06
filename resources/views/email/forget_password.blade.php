<!DOCTYPE html>
<html>
<head>
    <title>Reset Email Verification</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>

    <p>Your OTP for email verification is: {{ $otp }}</p>

    <p>This code is valid for 5 minutes. Please use it to verify your email address.</p>

    <p>Thank you!</p>
</body>
</html>
