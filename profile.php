<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: /signin.php');
}
$id = $_SESSION['user']['id'];

require_once "vendor/connect.php";
require_once "vendor/online/updateOnline.php";
require_once "vendor/online/outputOnline.php";

$infoUser = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM users_info JOIN users ON users_info.id = users.id WHERE users_info.id=" . (int)$_GET['userID'] . ""));

$topics = mysqli_query($connect, "SELECT
                                    `users`.`id` AS `user_id`,
                                    `topics`.`id` AS `topic_id`,
                                    `topics`.`title` AS `topic_title`,
                                    `categories`.`name` AS `category_name`,
                                    `topics`.`creation_date` AS `created_at`,
                                    MAX(comments.creation_date) AS `last_comment`,
                                    `info`.`name` AS `user`,
                                    `num_comments`.`num` AS `num_comments`
                                FROM
                                    `topics`
                                JOIN `categories` ON `topics`.`category_id` = `categories`.`id`
                                LEFT JOIN `comments` ON `comments`.`topic_id` = `topics`.`id`
                                JOIN `users` ON `topics`.`user_id` = `users`.`id`
                                LEFT JOIN(SELECT users.id AS `id`, users.username AS `name` FROM users) AS `info` ON `info`.`id` = comments.user_id
                                LEFT JOIN(SELECT COUNT(comments.topic_id) AS `num`, comments.topic_id AS `topic_id` FROM comments GROUP BY topic_id) AS `num_comments` ON `num_comments`.`topic_id` = topics.id
                                WHERE `users`.`id` = " . (int)$_GET['userID'] . "
                                GROUP BY topic_id,topic_title,category_name,created_at,user,num_comments
                                HAVING last_comment IN (SELECT MAX(comments.creation_date) FROM comments GROUP BY comments.topic_id) OR `num_comments` IS NULL;");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--========== BOX ICONS ==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!--========== CSS ==========-->
    <link rel="stylesheet" href="assets/css/styles.css">

    <title>5PDA</title>
</head>

<body>
    <!--========== HEADER ==========-->
    <header class="header">
        <div class="header__container">
            <img src="<?= $_SESSION['user']['avatar'] ?>" alt="" class="header__img">

            <a href="#" class="header__logo">Профиль</a>

            <div class="header__toggle">
                <i class='bx bx-menu' id="header-toggle"></i>
            </div>
        </div>
    </header>

    <!--========== NAV ==========-->
    <div class="nav" id="navbar">
        <nav class="nav__container">
            <div>
                <a href="index.php" class="nav__link nav__logo">
                    <i class='bx bxs-disc nav__icon'></i>
                    <span class="nav__logo-name">5PDA</span>
                </a>

                <div class="nav__list">
                    <div class="nav__items">
                        <h3 class="nav__subtitle">Меню</h3>

                        <a href="index.php" class="nav__link active">
                            <i class='bx bx-user nav__icon'></i>
                            <!-- bx-home -->
                            <span class="nav__name">Профиль</span>
                        </a>
                        <a href="forum.php" class="nav__link">
                            <i class='bx bx-message-rounded nav__icon'></i>
                            <span class="nav__name">Форум</span>
                        </a>
                    </div>
                </div>
            </div>

            <a href="vendor/logout.php" class="nav__link nav__logout">
                <i class='bx bx-log-out nav__icon'></i>
                <span class="nav__name">Выйти</span>
            </a>
        </nav>
    </div>

    <!--========== CONTENTS ==========-->
    <main>
        <section class="profile-main">
            <div class="profile-main__container">
                <img src="<?= $infoUser['avatar'] ?>" alt="" class="profile-photo">
                <div class="profile-info">
                    <div class="profile-main-info-box">
                        <h1><?= $infoUser['username'] ?></h1>
                        <span>Пользователь</span>
                        <span><? echo getLastTimeActivity($infoUser['online_date']) ?></span>
                    </div>
                    <ul>
                        <li>
                            <p class="title">Регистрация:</p>
                            <span><?= date_format(new DateTime($infoUser["registration_date"]), 'Y-m-d') ?></span>
                        </li>
                        <!-- <li>
                            <p class="title">Последнее посещение:</p>
                            <span><?= empty($infoUser["online_date"]) ? "Никогда" : $infoUser["online_date"]; ?></span>
                        </li> -->
                        <li>
                            <p class="title">Пол</p>
                            <span><?= $infoUser['gender'] ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="second_info">
            <div class="second_info__container">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="40%" style="text-align: left">Вопрос</th>
                            <th width="20%" style="text-align: center">Дата создания</th>
                            <th width="20%" style="text-align: center">Последнее сообщение</th>
                            <th width="10%" style="text-align: center">Ответов</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $topic = mysqli_fetch_all($topics, MYSQLI_ASSOC);
                        if ($topic != NULL) {
                            foreach ($topic as $result) {
                                echo '<tr>';
                                echo "<td style='text-align: left'><span style='font-size:80%'>Раздел:{$result["category_name"]} </span> <br> <strong> <a href='/question.php?topicID={$result["topic_id"]}&page=1'>{$result["topic_title"]}</a></strong>  </td>";
                                echo "<td style='text-align: center; vertical-align: middle;'>{$result["created_at"]}</td>";
                                if ($result["last_comment"] !== NULL) {
                                    echo "<td style='text-align: center; vertical-align: middle;'>{$result["last_comment"]} <br> <span style='font-size:80%; text-align:left'> Пользователь: {$result["user"]} </span></td>";
                                } else {
                                    echo "<td style='text-align: center; vertical-align: middle;'>—</td>";
                                }
                                $num_comments = empty($result["num_comments"]) ? "0" : $result["num_comments"];
                                echo "<td style='text-align: center'>{$num_comments}</td>";
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr>';
                            echo "<td colspan='4' style='text-align: center; vertical-align: middle;'>Данный пользователь не создавал никаких тем</td>";
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

<script>
    <?php echo "var userID = " . $_SESSION['user']['id'] . ";"; ?>
    setInterval(function() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "vendor/online/autoCheckOnline.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("user_id=" + userID);
    }, 60000);
</script>

</html>