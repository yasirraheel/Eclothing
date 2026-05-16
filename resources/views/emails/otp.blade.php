<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="color: #f85606;">Account Verification</h2>
        <p>Hello,</p>
        <p>Thank you for registering. Your One-Time Password (OTP) for email verification is:</p>
        <div style="font-size: 24px; font-weight: bold; background: #f9f9f9; padding: 15px; text-align: center; letter-spacing: 5px; margin: 20px 0; border: 1px dashed #ccc;">
            {{ $otp }}
        </div>
        <p>This OTP will expire in 15 minutes. Please do not share it with anyone.</p>
        <p>Regards,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>
