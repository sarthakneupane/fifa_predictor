<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notification</title>
</head>
<body>
    <p>Hi {{ $data['name'] }},</p>

    <p>Thank you for reaching out to us. We have received your message with the following details:</p>

    <p><strong>Subject:</strong> {{ $data['subject'] ?? 'N/A' }}</p>
    <p><strong>Your Message:</strong></p>
    <p>{{ $data['message'] }}</p>

    <br>
    <p>We will get back to you soon.</p>

    <p>— GFiveTech Team</p>
</body>
</html>
