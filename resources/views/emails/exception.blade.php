<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
    <h1>Issue in mail: {!! $title !!}</h1>

    <p>Exception type: {!! $type !!}</p>

    <p>Issue is: {!! $content !!}</p>
   
    <p>Thanks,
    <br>
        {{ config('app.name') }}
    </p>
</body>
</html>