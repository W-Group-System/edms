<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Memorandum Request Approved</title>
</head>
<body>
    <p>Your memorandum request has been approved.</p>

    <p><strong>Memo Number:</strong> {{ $memo->memo_number }}</p>
    <p><strong>Title:</strong> {{ $memo->title }}</p>

    <p>You are receiving this email because you submitted this memorandum request.</p>
</body>
</html>
