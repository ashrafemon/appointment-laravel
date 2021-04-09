<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Appointment Booked</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l"
        crossorigin="anonymous"
    />
</head>
<body>
<div class="card">
    <div class="card-body">
        <h1>Thank You</h1>
        <h2>Hi {{$name}},</h2>
        <p>Your password change is confirmed.</p>
        <hr/>

        <p>Email: {{$email}}</p>
        <p>Dummy Password: {{$password}}</p>

        <hr/>

        <p>Thanks,</p>
        <p class="mb-0">Appointment Corporation</p>
    </div>
</div>

</body>
</html>
