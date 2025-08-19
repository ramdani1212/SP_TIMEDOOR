<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Jadwal Baru</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #E8F5E9; margin: 0; padding: 0; }
        .form-container { max-width: 600px; margin: 40px auto; padding: 20px; background-color: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
        h1 { color: #4CAF50; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input[type="date"], input[type="time"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .submit-button { width: 100%; background-color: #4CAF50; color: white; padding: 12px; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #008CBA; text-decoration: none; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Buat Jadwal Baru</h1>
        <form action="{{ route('teacher.schedules.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="schedule_date">Tanggal:</label>
                <input type="date" name="schedule_date" id="schedule_date" required>
            </div>
            <div class="form-group">
                <label for="start_time">Waktu Mulai:</label>
                <input type="time" name="start_time" id="start_time" required>
            </div>
            <div class="form-group">
                <label for="end_time">Waktu Selesai:</label>
                <input type="time" name="end_time" id="end_time" required>
            </div>
            <button type="submit" class="submit-button">Simpan Jadwal</button>
        </form>
        <a href="{{ route('teacher.dashboard') }}" class="back-link">Kembali ke Dashboard</a>
    </div>
</body>
</html>