<!DOCTYPE html>
<html>
<head>
    <title>Kape Ni Asero Login Code</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>🔐 Login Verification</h2>
    <p>Hello,</p>
    <p>You are trying to log in to the POS System. Please use the code below to complete your login:</p>

    <div style="background: #f3f3f3; padding: 20px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; border-radius: 5px;">
        {{ $code }}
    </div>

    <p>This code will expire in 10 minutes.</p>
    <p>If you did not try to login, please contact the admin immediately.</p>
</body>
</html>