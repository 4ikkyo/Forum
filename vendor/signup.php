<?php
session_start();

require_once 'connect.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];
$gender = $_POST['gender'];
$birthday = $_POST['birthday'];
$registration_date = date("Y-m-d H:i:s");

$sameUsername = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM users WHERE username='$username'"));

if ($sameUsername == 0) {
    if ($password === $password_confirm) {

        if ($_FILES['avatar']['name'] == NULL) {
            $path = 'default/1.png';
        } else {
            $path = 'uploads/' . time() . $_FILES['avatar']['name'];
        }
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], '../' . $path)) {
            $_SESSION['message'] = 'Ошибка при загрузке сообщения';
            header('Location: ../signup.php');
        }

        $password = password_hash($password, PASSWORD_BCRYPT);

        mysqli_query($connect, "INSERT INTO `users` (`username`, `email`, `password`) VALUES ('$username', '$email', '$password')");
        $user_id = $connect->insert_id;
        mysqli_query($connect, "INSERT INTO `users_info` (`id`, `registration_date`, `avatar`, `gender`, `birthday`) VALUES ('$user_id', '$registration_date', '$path', '$gender', '$birthday')");

        $_SESSION['message'] = 'Регистрация прошла успешно!';
        header('Location: ../signin.php');
    } else {
        $_SESSION['message'] = 'Пароли не совпадают';
        header('Location: ../signup.php');
    }
} else {
    $_SESSION['message'] = "Извините, это имя пользователя уже занято. Пожалуйста, выберите другой";
    header('Location: ../signup.php');
}
