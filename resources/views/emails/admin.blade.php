<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message</title>
</head>
<body>
    <p><strong>New contact request received</strong></p>

    <p><strong>Name:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Phone:</strong> {{ $data['phone'] ?? 'N/A' }}</p>
    <p><strong>Organization:</strong> {{ $data['organization'] ?? 'N/A' }}</p>
    <p><strong>Subject:</strong> {{ $data['subject'] ?? 'N/A' }}</p>

    <p><strong>Message:</strong></p>
    <p>{{ $data['message'] }}</p>
</body>
</html>
