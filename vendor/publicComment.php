<?php	
	session_start();
    if (!$_SESSION['user']) {
        header('Location: /');
    }
    $id = $_SESSION['user']['id'];
	require_once 'connect.php';

	$topic_id = $_POST['topic_id'];
    $text = $_POST['text'];
	
    mysqli_query($connect, "INSERT INTO `comments` (`id`, `topic_id`, `user_id`, `content`) VALUES (NULL, '$topic_id' ,'$id', '$text')");

    header("Location: ../question.php?topicID=".$_SESSION['currentTopic']."&page=".$_SESSION['currentPage']);
?>
