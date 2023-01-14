<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: /');
}
$id = $_SESSION['user']['id'];

require_once "vendor/connect.php";
require_once "vendor/online/updateOnline.php";

$topics = mysqli_query($connect, "SELECT
                                `topics`.`id` AS `topic_id`,
                                `topics`.`title` AS `topic_title`,
                                `categories`.`name` AS `category_name`,
                                `topics`.`creation_date` AS `created_at`,
                                `users`.`username` AS `author_name`,
                                MAX(comments.creation_date) AS `last_comment`,
                                `info`.`name` AS `user`,
                                `num_comments`.`num` AS `num_comments`
                            FROM 
                                `topics` 
                            JOIN `categories` ON `topics`.`category_id` = `categories`.`id`
                            LEFT JOIN `comments` ON `comments`.`topic_id` = `topics`.`id`
                            JOIN `users` ON `topics`.`user_id` = `users`.`id`
                            LEFT JOIN (SELECT users.id as `id`, users.username as `name` FROM users) AS `info` ON `info`.`id` = comments.user_id
                            LEFT JOIN (SELECT COUNT(comments.topic_id) as `num`, comments.topic_id as `topic_id` FROM comments GROUP BY topic_id) AS `num_comments` ON `num_comments`.`topic_id` = topics.id
                            GROUP BY topic_id, topic_title, category_name, created_at, author_name, user, num_comments
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

    <script type="text/javascript" src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>

    <title>5PDA</title>
</head>

<body>
    <!--========== HEADER ==========-->
    <header class="header">
        <div class="header__container">
            <img src="<?= $_SESSION['user']['avatar'] ?>" alt="" class="header__img">

            <a href="#" class="header__logo">Форум</a>

            <div class="header__search">
                <input id="search-text" onkeyup="tableSearch()" type="search" placeholder="Поиск" class="header__input">
                <i class='bx bx-search header__icon'></i>
            </div>

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

                        <a href="index.php" class="nav__link">
                            <i class='bx bx-user nav__icon'></i>
                            <!-- bx-home -->
                            <span class="nav__name">Профиль</span>
                        </a>

                        <a href="forum.php" class="nav__link active">
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

    <main>
        <section class="forum-head">
            <div class="forum-head__container">
                <h1>Вопросы</h1>

                <div class="container">
                    <div style="text-align: center;">
                        <a class="button" href="#openModal">Задать вопрос</a> <!-- Кнопка добавить запись -->
                    </div>
                    <form method="post" action="vendor/publicTopic.php">
                        <div id="openModal" class="modal">
                            <div class="modal-dialog">
                                <div class="modal-body" style="display: flex; flex-direction: column;">
                                    <label for="category" style="color: white;">Раздел:</label>
                                    <select name="category" id="category">
                                        <option value="" selected>--- Выберите раздел ---</option>
                                        <?php
                                        $sql = mysqli_query($connect, "SELECT * FROM categories");
                                        while ($result = mysqli_fetch_array($sql)) {
                                            echo "<option value='{$result["id"]}'>{$result["name"]}</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="title" style="color: white;">Тема:</label>
                                    <input type="title" name="title" placeholder="Введите тему" minlength="10" maxlength="100" required>
                                    <a href="#close" title="Close" class="close" style="height: 3px; margin-left: auto;">×</a>
                                    <textarea name="content"></textarea>
                                    <button class="button" type="submit" style="margin: 10px auto 0 auto;">Создать</button>
                                    <script>
                                        CKEDITOR.replace('content');
                                    </script>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="forum-sort">
                    <!-- <img src="svg/sort.svg" width="64px"> -->
                    <label>
                        <select name="forum-sort">
                            <option value="" selected>Сортировка</option>
                            <option value="1">По дате</option>
                            <option value="2">По ответам</option>
                        </select>
                    </label>
                </div>
            </div>
        </section>

        <section class="forum-table">
            <div class="forum-table__container">
                <table id="info-table" class="table">
                    <thead>
                        <tr>
                            <th width="40%" style="text-align: left">Вопрос</th>
                            <th width="10%" style="text-align: center">Автор</th>
                            <th width="20%" style="text-align: center">Дата создания</th>
                            <th width="20%" style="text-align: center">Последнее сообщение</th>
                            <th width="10%" style="text-align: center">Ответов</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $topic = mysqli_fetch_all($topics, MYSQLI_ASSOC);
                        foreach ($topic as $query) {
                            echo '<tr>';
                            echo "<td style='text-align: left'><span style='font-size:80%'>Раздел:{$query["category_name"]} </span> <br> <strong> <a href='/question.php?topicID={$query["topic_id"]}&page=1'>{$query["topic_title"]}</a></strong>  </td>";
                            echo "<td style='text-align: center; vertical-align: middle;'>{$query["author_name"]}</td>";
                            echo "<td style='text-align: center; vertical-align: middle;'>{$query["created_at"]}</td>";
                            if ($query["last_comment"] !== NULL) {
                                echo "<td style='text-align: center; vertical-align: middle;'>{$query["last_comment"]} <br> <span style='font-size:80%; text-align:left'> Пользователь: {$query["user"]} </span></td>";
                            } else {
                                echo "<td style='text-align: center; vertical-align: middle;'>—</td>";
                            }
                            $num_comments = empty($query["num_comments"]) ? "0" : $query["num_comments"];
                            echo "<td style='text-align: center; vertical-align: middle;'>{$num_comments}</td>";
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!--========== MAIN JS ==========-->
    <script src="assets/js/search.js"></script>
    <script>
        <?php echo "var userID = " . $_SESSION['user']['id'] . ";"; ?>
        setInterval(function() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "vendor/online/autoCheckOnline.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("user_id=" + userID);
        }, 60000);
    </script>
</body>

</html>