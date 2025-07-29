<?php

function updateOnline($connect, $userId = null)
{
    if ($userId === null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user']['id'] ?? null;
    }

    if (!$userId) {
        return;
    }

    $time = date('Y-m-d H:i:s');

    mysqli_query($connect, "UPDATE users_info SET online_date = '$time' WHERE id = '$userId'");
}
