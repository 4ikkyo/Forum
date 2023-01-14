<?php	
	session_start();
    if (!$_SESSION['user']) {
        header('Location: /');
    }
    $id = $_SESSION['user']['id'];
	require_once 'connect.php';

    $category_id = $_POST['category'];
	$title = $_POST['title'];
    $content = $_POST['content'];
	
    mysqli_query($connect, "INSERT INTO `topics` (`category_id`, `user_id`, `title`, `content`) VALUES ('$category_id', '$id', '$title', '$content')");

    //header('Location: ../forum.php');
?>
