<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat</title>
    <style>
        body {
            text-align: center;
            font-family: sans-serif;
            padding: 80px;
        }
        .box {
            border: 8px solid #16a34a;
            padding: 60px;
        }
        h1 {
            font-size: 42px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>SERTIFIKAT</h1>
        <p>Diberikan kepada:</p>
        <h2>{{ $registration->user->name }}</h2>

        <p>Atas partisipasinya sebagai peserta dalam event:</p>

        <h3>{{ $registration->event->title }}</h3>

        <p>{{ $registration->event->date_human }}</p>

        <br><br>

        <p>Organizer</p>
        <strong>{{ $registration->event->organizer->name }}</strong>
    </div>
</body>
</html>
