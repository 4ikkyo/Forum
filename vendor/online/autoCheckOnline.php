<?php
if (!$_SESSION['user']) {
    header('Location: /');
}
require_once 'connect.php';
if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $time = date("Y-m-d H:i:s");
    mysqli_query($connect, "UPDATE users_info SET online_date = '$time' WHERE id = '$userId'");
}
