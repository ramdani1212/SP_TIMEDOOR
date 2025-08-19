<!DOCTYPE html>
<html>
<head>
    <title>Teacher Detail</title>
</head>
<body>
    <h1>Teacher Detail</h1>

    <p><strong>Name:</strong> {{ $teacher->name }}</p>
    <p><strong>Subject:</strong> {{ $teacher->subject }}</p>

    <a href="{{ route('teachers.index') }}">← Back</a>
</body>
</html>
