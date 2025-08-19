<!DOCTYPE html>
<html>
<head>
    <title>Create Teacher</title>
</head>
<body>
    <h1>Add New Teacher</h1>
    <form action="{{ route('teachers.store') }}" method="POST">
        @csrf
        <p>
            <label>Name:</label><br>
            <input type="text" name="name" value="{{ old('name') }}">
        </p>
        <p>
            <label>Subject:</label><br>
            <input type="text" name="subject" value="{{ old('subject') }}">
        </p>
        <button type="submit">Save</button>
    </form>
    <a href="{{ route('teachers.index') }}">← Back</a>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>
