<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h2>welcome {{ $user->name }}</h2>
    <br>
    enter <u>{{ $token }}</u>
    <h3>expires at {{ config('verification.expire_time') }} minutes</h3>
</body>

</html>
