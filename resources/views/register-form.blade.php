<!DOCTYPE html>
<html>
    <head>
        <title>Регистрация</title>
        <link rel="stylesheet" type="text/css" href="/static/css/style.css"/>
    </head>
    <body>
        <div class="main">
            <form action="/auth/register" method="POST">
                <div class="form-block">
                    <div class="auth-main-title">Регистрация</div>
                    <input class="input-field" type="text" name="name" placeholder="Имя">
                    <input class="input-field" type="text" name="email" placeholder="Электронная почта">
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
                    <button class="auth-submit-button" type="submit">Зарегистрироваться</button>
                </div>
            </form>
        </div>
    </body>
</html>

