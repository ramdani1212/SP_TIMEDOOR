<!DOCTYPE html>
<html>
<head>
    <title>Teacher List</title>
</head>
<body>
    <h1>Teacher List</h1>
    <a href="{{ route('teachers.create') }}">+ Add New Teacher</a>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Name</th>
                <th>Subject</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->subject }}</td>
                    <td>
                        <a href="{{ route('teachers.show', $teacher->id) }}">View</a> |
                        <a href="{{ route('teachers.edit', $teacher->id) }}">Edit</a> |
                        <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
