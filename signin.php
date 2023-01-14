<?php
session_start();
if ($_SESSION['user']) {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--========== BOX ICONS ==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!--========== CSS ==========-->
    <link rel="stylesheet" href="assets/css/styles.css">


    <title>Responsive sidebar submenus</title>
</head>

<body>
    <main>
        <section class="sign">
            <div class="head-sign">
                <h3>Авторизация</h3>
            </div>
            <form class="sign-form" action="vendor/signin.php" method="post" style="max-width: 400px;">
                <div class="sign-info">
                    <label>Логин</label>
                    <input type="text" name="username" placeholder="Введите свой логин">
                </div>
                <div class="sign-info">
                    <label>Пароль</label>
                    <input type="password" name="password" placeholder="Введите пароль">
                </div>
                <button class="button" type="submit">Войти</button>
                <hr>
                <div style="font-size: 15px;">
                    <span> Нет аккаунта? -</span> <a href="signup.php" style="color: black; font-weight: bold;">Зарегестрироваться</a>
                </div>
                <?php
                if ($_SESSION['message']) {
                    echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
                }
                unset($_SESSION['message']);
                ?>
            </form>
        </section>
    </main>

</body>

</html>