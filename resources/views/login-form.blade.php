<!DOCTYPE html>
<html>
    <head>
        <title>Вход</title>
        <link rel="stylesheet" type="text/css" href="/static/css/style.css"/>
    </head>
    <body>
        <div class="main">
            <form action="/auth/login" method="POST">
                <div class="form-block">
                    <div class="auth-main-title">Вход</div>
                    <input class="input-field" type="text" name="email" placeholder="Почта">
                    <input class="input-field" type="password" name="password" placeholder="Пароль">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <button type="submit" class="auth-submit-button">Войти</button>
                </div>
            </form>
        </div>
    </body>
</html>

