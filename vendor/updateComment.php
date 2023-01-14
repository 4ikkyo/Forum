<?php	
	session_start();
    if (!$_SESSION['user']) {
        header('Location: /');
    }
    $id = $_SESSION['user']['id'];
	require_once 'connect.php';

    $text = $_POST['editCommentText'];
    $comment_id = $_POST['comment-id'];
	
    mysqli_query($connect, "UPDATE comments SET content = '$text' WHERE id = '$comment_id'");

    header("Location: ../question.php?topicID=".$_SESSION['currentTopic']."&page=".$_SESSION['currentPage']);
    
?>
