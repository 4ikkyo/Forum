<?php
function getLastTimeActivity($userLastActivity)
{
    $current_time = time();
    $last_activity_time = strtotime($userLastActivity);
    $time_elapsed = $current_time - $last_activity_time;

    $time_elapsed_string = "";

    if ($time_elapsed < 60) {
        // $time_elapsed_string = $time_elapsed . " секунд назад";
        $time_elapsed_string = "ONLINE";
    } elseif ($time_elapsed < 3600) {
        $time_elapsed_string = "Был " . floor($time_elapsed / 60) . " минут назад";
    } elseif ($time_elapsed < 86400) {
        $time_elapsed_string = "Был " . floor($time_elapsed / 3600) . " часов назад";
    } else {
        $time_elapsed_string = "Был давно";
        // $time_elapsed_string = floor($time_elapsed / 86400) . " дней назад";
    }

    return $time_elapsed_string;
}

// echo $time_elapsed_string;