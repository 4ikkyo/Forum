<?php	
	session_start();
    if (!$_SESSION['user']) {
        header('Location: /');
    }
    $id = $_SESSION['user']['id'];
	require_once 'connect.php';

	$topic_id = $_POST['topic-id'];
	
    mysqli_query($connect, "DELETE FROM topics WHERE id = '$topic_id';");

    header("Location: ../index.php");
