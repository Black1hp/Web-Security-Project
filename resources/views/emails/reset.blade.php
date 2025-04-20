<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset Request</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f6f6; color: #222; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #e0e0e0; padding: 32px; }
        h2 { color: #5a2d82; }
        .button {
            display: inline-block;
            padding: 12px 28px;
            background: #7f49d3;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 24px;
        }
        .footer { margin-top: 32px; font-size: 13px; color: #888; }
    </style>
</head>
<body>
<div class="container">
    <h2>Hello, {{ $name }}!</h2>
    <p>We received a request to reset your password for your Elsewedy University account.</p>
    <a href="{{ $link }}" class="button">Reset Password</a>
    <p>If the button above does not work, copy and paste the following link into your browser:</p>
    <p><a href="{{ $link }}">{{ $link }}</a></p>
    <div class="footer">
        If you did not request a password reset, please ignore this email.
    </div>
</div>
</body>
</html>
