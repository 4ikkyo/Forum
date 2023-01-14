<?php	
	session_start();
    if (!$_SESSION['user']) {
        header('Location: /');
    }
    $id = $_SESSION['user']['id'];
	require_once 'connect.php';

    $topic_id = $_POST['topic-id'];
    $category_id = $_POST['category'];
	$title = $_POST['title'];
    $text = $_POST['editTopicText'];
	
    mysqli_query($connect, "UPDATE topics SET category_id='$category_id', title = '$title', content = '$text' WHERE id = '$topic_id'");

    header("Location: ../question.php?topicID=".$_SESSION['currentTopic']."&page=".$_SESSION['currentPage']);
    
?>
