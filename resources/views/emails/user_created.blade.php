<!DOCTYPE html>
<html>
<head>
    <title>Welcome to GAFALBUM</title>
</head>
<body>
    <h2>Hello, {{ $user->name }}!</h2>
    <p>An account has been created for you on GAFALBUM.</p>
    <p>Here are your login details:</p>
    <ul>
        <li><strong>Service Number:</strong> {{ $user->service_number }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>
    <p>Please log in and change your password as soon as possible.</p>
    <p><a href="{{ route('login') }}">Click here to login</a></p>
    <p>Thank you!</p>
</body>
</html>
