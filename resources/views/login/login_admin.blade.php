<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Administrator</title>
    <link rel="stylesheet" href="{{ asset('css/login_admin.css') }}">

</head>
<body>
    <div class="login-box">
        <h2>Login Administrator</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label for="name">Username</label>
            <input type="text" id="name" name="name" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
