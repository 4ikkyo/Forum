<?php
$time = date("Y-m-d H:i:s");
mysqli_query($connect, "UPDATE users_info SET online_date = NOW() WHERE id = '$id'");
