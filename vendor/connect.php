<?php
error_reporting(E_ALL);
    $connect = mysqli_connect('localhost', 'root', '', 'forum1');
    if (!$connect) {
        die('Error connect to DataBase');
    }
	?>