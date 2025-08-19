<!DOCTYPE html>
<html>
<head>
    <title>Edit Teacher</title>
</head>
<body>
    <h1>Edit Teacher</h1>
    <form action="{{ route('teachers.update', $teacher->id) }}" method="POST">
        @csrf
        @method('PUT')
        <p>
            <label>Name:</label><br>
            <input type="text" name="name" value="{{ old('name', $teacher->name) }}">
        </p>
        <p>
            <label>Subject:</label><br>
            <input type="text" name="subject" value="{{ old('subject', $teacher->subject) }}">
        </p>
        <button type="submit">Update</button>
    </form>
    <a href="{{ route('teachers.index') }}">← Back</a>
</body>
</html>
