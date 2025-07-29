<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: /');
}
$id = $_SESSION['user']['id'];

require_once "vendor/connect.php";
require_once "vendor/online/updateOnline.php";
require_once "vendor/online/outputOnline.php";

updateOnline($connect, $id);

$per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

$total_comment = mysqli_query($connect, "SELECT COUNT(*) FROM comments WHERE topic_id = " . (int)$_GET['topicID'] . "");
$total_comment = mysqli_fetch_row($total_comment)[0];
$total_pages = ceil($total_comment / $per_page);

$_SESSION['currentPage'] = $_GET['page'];
$_SESSION['currentTopic'] = $_GET['topicID'];

$mainInfo = mysqli_fetch_array(mysqli_query($connect, "SELECT
                                    users.id AS `user_id`,
                                    topics.id AS `topic_id`,
                                    topics.title AS `topic_title`,
                                    topics.category_id AS `category_id`,
                                    topics.content AS `topic_content`,
                                    topics.creation_date AS `topic_creation_date`,
                                    users_info.avatar AS `author_avatar`,
                                    users_info.online_date AS `author_online_date`,
                                    users.username as `author_username`
                                FROM
                                `topics`
                                    INNER JOIN `users` ON topics.user_id = users.id
                                    JOIN `users_info` ON users.id = users_info.id
                                    WHERE topics.id = " . (int)$_GET['topicID'] . ""));

$comments = mysqli_query($connect, "SELECT
                                        users.id AS `user_id`,
                                        users.username AS `comment_username`,
                                        users_info.avatar AS `comment_avatar`,
                                        users_info.online_date AS `online_date`,
                                        comments.id AS `comment_id`,
                                        comments.content AS `comment_content`,
                                        comments.creation_date AS `comment_creation_date`
                                    FROM
                                        `comments`
                                    INNER JOIN `users` ON comments.user_id = users.id
                                    INNER JOIN `users_info` ON users.id = users_info.id
                                    INNER JOIN `topics` ON topics.id = comments.topic_id 
                                    WHERE topics.id = " . (int)$_GET['topicID'] . "
                                    ORDER BY `comment_creation_date`
                                    LIMIT {$start}, {$per_page}");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--========== BOX ICONS ==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!--========== CSS ==========-->
    <script type="text/javascript" src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>

    <link rel="stylesheet" href="assets/css/styles.css">

    <title>5PDA</title>
</head>

<body>
    <!--========== HEADER ==========-->
    <header class="header">
        <div class="header__container">
            <img src="<?= $_SESSION['user']['avatar'] ?>" alt="" class="header__img">

            <a href="#" class="header__logo">Форум</a>

            <!-- <div class="header__search">
                <input type="search" placeholder="Search" class="header__input">
                <i class='bx bx-search header__icon'></i>
            </div> -->

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

    <!--========== CONTENTS ==========-->
    <main>
        <section>
            <div class="second_info noperenos" style="border: 1px solid var(--first-color);">
                <table class="table" style="background: linear-gradient(to bottom, #5c5cff 0%, #7a7aff 100%); border:0">
                    <tbody>
                        <tr style="background: none;">
                            <td colspan=2 style="text-align: left; color:white; background: none; font-weight:bold" id="data-topic-title"><?= $mainInfo['topic_title'] ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table">
                    <thead>
                        <tr>
                            <th width="10%"><a href="/profile.php?userID=<?= $mainInfo['user_id'] ?>"><?= $mainInfo['author_username'] ?></a></th>
                            <th width="90%" style="text-align: left"><?= $mainInfo['topic_creation_date'] ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><img src="<?= $mainInfo['author_avatar'] ?>"><br>
                                <p style="font-size: 10px;"> <? echo getLastTimeActivity($mainInfo["author_online_date"]) ?></p>
                            </th>
                            <td style="position: relative;">
                                <div id='topic-content'>
                                    <?= $mainInfo['topic_content'] ?>
                                </div>
                                <?php
                                if ($mainInfo["user_id"] == $id) {
                                    echo "<div style='text-align: center;position: absolute; right: 0; bottom: 0;'> 
                                        <a class='button, edit-topic-btn' data-topic-id='{$mainInfo["topic_id"]}' href='#modalUpdateTopic'>Редактировать</a>
                                    </div>";
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr style="margin: 0; height: 10px; background: var(--first-color); border: 0">

                <?php
                $comment = mysqli_fetch_all($comments, MYSQLI_ASSOC);
                foreach ($comment as $result) {
                    echo "<table class='table'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th width='10%'><a href='/profile.php?userID={$result["user_id"]}'>{$result["comment_username"]}</a></th>";
                    echo "<th width='90%'' style='text-align: left'>{$result["comment_creation_date"]}</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    echo "<tr>";
                    // if ($result["user_id"] == $id) {
                    //     $status = ($time_elapsed < 60) ? "ONLINE" : $time_elapsed_string;
                    // }else{
                    //     $status = 4;
                    // }
                    $status = getLastTimeActivity($result["online_date"]);
                    echo "<th><img src={$result["comment_avatar"]}> <br> <p style='font-size: 10px;'>$status</p></th>";
                    echo "<td style='position: relative'><div id='comment-content'>{$result["comment_content"]}</div>";
                    if ($result["user_id"] == $id) {
                        echo "<div style='text-align: center;position: absolute; right: 0; bottom: 0;'> 
                                    <a class='button, edit-comment-btn' data-comment-id='{$result["comment_id"]}' href='#modalUpdateComment'>Редактировать</a>
                                </div>";
                    }
                    echo "</td>";
                    echo "</tr>";
                    echo "</tbody>";
                    echo "</table>";
                }
                // echo '<br>';
                ?>
            </div>
            <div style="text-align: center;">
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo $i . ' ';
                    } else {
                        echo '<a style="text-decoration: underline; color: cornflowerblue;font-weight: bolder;" href="?topicID=' . $_GET['topicID'] . '&page=' . $i . '">' . $i . '</a> ';
                    }
                }
                ?>
            </div>
        </section>
        <section>
            <form method="post" action="vendor/publicComment.php">
                <div class="modal-body" style="display: flex; flex-direction: column;">
                    <input type="hidden" name="topic_id" value="<?= $_GET['topicID']; ?>">
                    <textarea name="text"></textarea>
                    <button class="button" type="submit" style="margin: 10px auto 0 auto;">Ответить</button>
                    <script>
                        CKEDITOR.replace('text');
                    </script>
                </div>
            </form>
        </section>
        <form method="post" action="vendor/updateComment.php">
            <div id="modalUpdateComment" class="modal">
                <div class="modal-dialog">
                    <input type="hidden" name="comment-id" id="comment-id" value="">
                    <div class="modal-body" style="display: flex; flex-direction: column;">
                        <a href="#close" title="Close" class="close editCommentText" style="height: 3px; margin-left: auto;">×</a>
                        <textarea name="editCommentText"></textarea>
                        <button class="button" type="submit" style="margin: 10px auto 0 auto;">Отправить</button>
                        <script>
                            CKEDITOR.replace('editCommentText');
                        </script>
                    </div>
                </div>
            </div>
        </form>
        <form method="post" action="vendor/updateTopic.php">
            <div id="modalUpdateTopic" class="modal">
                <div class="modal-dialog">
                    <input type="hidden" name="topic-id" id="topic-id" value="">
                    <div class="modal-body" style="display: flex; flex-direction: column;">
                        <label for="category" style="color: white;">Раздел:</label>
                        <select name="category" id="topic-category">
                            <option value="" selected>--- Выберите раздел ---</option>
                            <?php
                            $sql = mysqli_query($connect, "SELECT * FROM categories");
                            while ($result = mysqli_fetch_array($sql)) {
                                echo "<option value='{$result["id"]}'>{$result["name"]}</option>";
                            }
                            ?>
                        </select>
                        <label for="title" style="color: white;">Тема:</label>
                        <input type="title" name="title" id="topic-title" placeholder="Введите тему" minlength="10" maxlength="100" required>
                        <a href="#close" title="Close" class="close editTopicText" style="height: 3px; margin-left: auto;">×</a>
                        <textarea name="editTopicText"></textarea>
                        <button class="button" type="submit" style="margin: 10px auto 0 auto;">Отправить</button>
                        <script>
                            CKEDITOR.replace('editTopicText');
                        </script>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <!--========== MAIN JS ==========-->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script>
        var editBtnsTopic = document.getElementsByClassName("edit-topic-btn");
        for (var i = 0; i < editBtnsTopic.length; i++) {
            editBtnsTopic[i].addEventListener("click", function() {
                var topicId = this.getAttribute("data-topic-id");
                var topicText = this.parentElement.parentElement.getElementsByTagName("div")[0].innerHTML;
                var topicTitle = document.getElementById("data-topic-title").innerHTML;

                // console.log(topicId);
                // console.log(topicText);
                //console.log(topicTitle);

                document.getElementById('topic-category').value=<?= $mainInfo["category_id"] ?>;
                document.getElementById("topic-title").value = topicTitle;
                document.getElementById("topic-id").value = topicId;
                CKEDITOR.instances.editTopicText.setData(topicText);
            });
        }

        var closeTopic = document.getElementsByClassName("close editTopicText")[0];
        closeTopic.addEventListener("click", function() {
            document.getElementById("topic-id").value = "";
            CKEDITOR.instances.editTopicText.setData("");
        });
    </script>

    <script>
        var currentUrl = window.location.href;
        if (currentUrl.indexOf('#') > -1) {
            var newUrl = currentUrl.substring(0, currentUrl.indexOf("#"));
            window.history.replaceState({}, document.title, newUrl);
        }

        var editBtnsComment = document.getElementsByClassName("edit-comment-btn");
        for (var i = 0; i < editBtnsComment.length; i++) {
            editBtnsComment[i].addEventListener("click", function() {
                var commentId = this.getAttribute("data-comment-id");
                var commentText = this.parentElement.parentElement.getElementsByTagName("div")[0].innerHTML;
                
                // console.log(commentId);
                // console.log(commentText);
                
                document.getElementById("comment-id").value = commentId;
                CKEDITOR.instances.editCommentText.setData(commentText);
            });
        }

        var closeComment = document.getElementsByClassName("close editCommentText")[0];
        closeComment.addEventListener("click", function() {
            document.getElementById("comment-id").value = "";
            CKEDITOR.instances.editCommentText.setData("");
        });
    </script>



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