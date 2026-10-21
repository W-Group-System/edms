<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Process Review Request Completed</title>
</head>
<body>
    <p>Your Process Review Request has been completed.</p>

    <p><strong>Reference Number:</strong> {{ $prr->reference_num }}</p>
    <p><strong>Title:</strong> {{ $prr->document_title }}</p>

    <p>Please proceed with finalizing the new or revised Policy and upload the finalized document to Pre-Assessment in EDMS.</p>
    <p>Thank you.</p>
</body>
</html>