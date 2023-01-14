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
                <h3>Регистрация</h3>
            </div>
            <form class="sign-form" action="vendor/signup.php" method="post" enctype="multipart/form-data">
                <div class="sign-info">
                    <label for="username">Логин</label>
                    <input type="text" name="username" placeholder="Введите свой логин" minlength="4" maxlength="64" required>
                </div>
                <div class="sign-info">
                    <label for="email">Почта</label>
                    <input type="email" name="email" placeholder="Введите адрес своей почты" required>
                </div>
                <div class="sign-info">
                    <label for="gender">Пол</label>
                    <select id="gender" name="gender" style="width: 100px; height:30px">
                        <option value="Мужской">Мужской</option>
                        <option value="Женский">Женский</option>
                    </select>
                </div>
                <div class="sign-info">
                    <label for="avatar">Изображение профиля</label>
                    <input type="file" name="avatar">
                </div>
                <div class="sign-info">
                    <label for="password">Пароль</label>
                    <input type="password" name="password" placeholder="Введите пароль" minlength="6" maxlength="32" required>
                </div>
                <div class="sign-info">
                    <label for="password_confirm">Подтверждение пароля</label>
                    <input type="password" name="password_confirm" placeholder="Подтвердите пароль" minlength="6" maxlength="32" required>
                </div>
                <div class="sign-info">
                    <label for="birthday">Дата рождения</label>
                    <input type="date" name="birthday" placeholder="Дата рождения" required>
                </div>
                <button class="button" type="submit">Зарегестрироваться</button>
                <hr>
                <div style="font-size: 15px;">
                    <span> У вас уже есть аккаунт? -</span> <a href="signin.php" style="color: black; font-weight: bold;">авторизируйтесь</a>
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