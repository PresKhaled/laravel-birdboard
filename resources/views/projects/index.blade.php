<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Birdboard</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        ul {
            font-size: 18px;
            line-height: 1.3;
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
    <h1>Projects</h1>
    @forelse ($projects as $project)
        <ul>
            <a href="{{ $project->url() }}">{{ $project->title }}</a>
            <ul>
                <li>{{ $project->description }}</li>
                <li style="color: green">{{ $project->created_at->diffForHumans() }}</li>
            </ul>
            <br />
        </ul>
    @empty
        <p>No projects yet.</p>
    @endforelse
</body>
</html>