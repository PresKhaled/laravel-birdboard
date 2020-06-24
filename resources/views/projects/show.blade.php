<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Birdboard - Specific project</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    
    <style>
        ul {
            font-size: 18px;
            line-height: 2;
        }
    
        html, body {
            background-color: #fff;
            padding: 25px;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }
    </style>
</head>
<body>
    <h1>Project /</h1>
    <ul>
        <li>{{ $project->title }}</li>
        <li>{{ $project->description }}</li>
        <li style="color: green">{{ $project->created_at->diffForHumans() }}</li>
    </ul>
</body>
</html>