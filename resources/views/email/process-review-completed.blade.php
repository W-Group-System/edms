<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Process Review Request Completed</title>
</head>
<body>
    <p>Your process review request has been completed.</p>

    <p><strong>Reference Number:</strong> {{ $prr->reference_num }}</p>
    <p><strong>Title:</strong> {{ $prr->document_title }}</p>

    <p>You are receiving this email because you submitted this process review request.</p>
</body>
</html>
