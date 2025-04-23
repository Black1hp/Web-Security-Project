<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - El Sewedy University of Technology</title>
    <style>
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        .university-logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
        }
        .verification-button {
            background-color: #0056b3;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 20px 0;
        }
        .footer-text {
            font-size: 12px;
            color: #6c757d;
        }
        .text-center {
            text-align: center;
        }
        .card {
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        .card-body {
            padding: 1.25rem;
        }
        .mt-4 {
            margin-top: 1.5rem;
        }
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        hr {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body style="background-color: #f8f9fa;">
    <div class="email-container">
        <div class="text-center">
            <img src="https://images.wuzzuf-data.net/files/company_logo/Elsewedy-University-of-Technology-Egypt-96010-1698259526.jpg?height=160&width=160" alt="El Sewedy University Logo" class="university-logo">
            <h2 class="mb-4">Email Verification</h2>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Welcome to El Sewedy University of Technology!</h5>
                <p class="card-text">Dear {{ $name }},</p>
                <p>Thank you for registering with El Sewedy University of Technology. To complete your registration and activate your account, please click the verification button below:</p>
                
                <div class="text-center">
                    <a href="{{ $link }}" class="verification-button">
                        Verify Email Address
                    </a>
                </div>

                <p class="mt-4">If you did not create an account, no further action is required.</p>
                
                <hr>
                
                <p class="footer-text">
                    If you're having trouble clicking the "Verify Email Address" button, copy and paste the following URL into your web browser:<br>
                    <a href="{{ $link }}">{{ $link }}</a>
                </p>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="footer-text">
                © {{ date('Y') }} El Sewedy University of Technology. All rights reserved.<br>
                This is an automated message, please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
