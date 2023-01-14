<?php
require_once 'connect.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM users JOIN users_info ON users_info.id = users.id WHERE `username` = '$username'";
$result = $connect->query($query);
$row = $result->fetch_assoc();

if (password_verify($password, $row['password'])) {
    session_start();
    $_SESSION['user'] = [
        "id" => $row['id'],
        "username" => $row['username'],
        "avatar" => $row['avatar']
    ];
    //$_SESSION['user_id'] = $row['id'];
    header('Location: ../index.php');
} else {
    $_SESSION['message'] = 'Не верный логин или пароль';
    header('Location: ../signin.php');
}

?>

<pre>
    <?php
    print_r($check_user);
    print_r($row);
    ?>
</pre>