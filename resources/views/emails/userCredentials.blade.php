<!DOCTYPE html>
<html>
<head>
    <title>Account Credentials</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>👋 Welcome to Kape Ni Asero!</h2>
    <p>Hello <strong>{{ $user->name }}</strong>,</p>
    
    <p>An administrator has created an account for you. You can now log in using the credentials below:</p>

    <div style="background: #f3f3f3; padding: 20px; border-radius: 5px; margin: 20px 0;">
        <p style="margin: 5px 0;"><strong>Email:</strong> {{ $user->email }}</p>
        <p style="margin: 5px 0;"><strong>Password:</strong> {{ $password }}</p>
        <p style="margin: 5px 0;"><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
    </div>

    <p>Please log in and change your password as soon as possible for security.</p>
    
    <p>Best regards,<br>The Kape Ni Asero Team</p>
</body>
</html>