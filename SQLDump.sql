-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 14 2023 г., 04:29
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `forum1`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(3, 'Программирование', ''),
(4, 'Бытовое', ''),
(5, 'Купля/продажа', '');

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `topic_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `topic_id`, `user_id`, `content`, `creation_date`) VALUES
(1, 2, 8, 'Вот так вот', '2023-01-11 22:34:56'),
(2, 2, 10, '<p><strong>фывфыв</strong></p>\r\n\r\n<p><em><strong>ываыв</strong></em></p>\r\n\r\n<p>&nbsp;</p>\r\n', '2023-01-11 22:37:56'),
(3, 2, 10, 'Вот так вот', '2023-01-11 22:39:56'),
(4, 2, 11, 'Вот так вот', '2023-01-11 22:43:56'),
(11, 2, 7, '<p>sdfsdf</p>\r\n', '2023-01-12 12:49:27'),
(12, 2, 7, '<p>оршщрщ</p>\r\n', '2023-01-12 12:50:11'),
(13, 2, 7, '<p>sdfsd</p>\r\n', '2023-01-12 12:51:29'),
(14, 2, 7, '<p><strong>sdfsdfsdfsdfsd <em>sdf </em><s>s</s></strong></p>\r\n', '2023-01-12 12:51:48'),
(15, 2, 7, '<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width:500px\">\r\n	<tbody>\r\n		<tr>\r\n			<td>sdf</td>\r\n			<td>sdf</td>\r\n		</tr>\r\n		<tr>\r\n			<td>sdf</td>\r\n			<td>sdf</td>\r\n		</tr>\r\n		<tr>\r\n			<td>sdf</td>\r\n			<td>sdf</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>dfgdfgdgh</p>\r\n\r\n<p>d</p>\r\n', '2023-01-12 16:02:35'),
(16, 2, 7, '<p>gjghj</p>\r\n', '2023-01-12 17:55:17'),
(17, 2, 7, '<p>dfgdfg</p>\r\n', '2023-01-12 17:56:23'),
(18, 5, 7, '<p>Ну вынесите проверку в&nbsp;<code>while</code>&nbsp;- типа</p>\r\n\r\n<blockquote>\r\n<pre>\r\n<code>do {\r\n    ...\r\n    cin &gt;&gt; o;\r\n} while (o != &quot;exit&quot;);\r\nexit(0);   // Если это в функции main(), то можно просто выйти из нее</code>\r\n</pre>\r\n</blockquote>\r\n\r\n<p>Примерно так...</p>\r\n', '2023-01-13 14:09:00');

-- --------------------------------------------------------

--
-- Структура таблицы `topics`
--

CREATE TABLE `topics` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `topics`
--

INSERT INTO `topics` (`id`, `category_id`, `user_id`, `title`, `content`, `creation_date`) VALUES
(2, 3, 7, 'Помогите с программированием', '<p><strong>фывфыв</strong></p>\r\n\r\n<p><em><strong>ываы</strong></em></p>\r\n', '2023-01-11 21:39:28'),
(5, 3, 12, 'Помогите с С++', '<p>Я хочу сделать так, чтобы после использования калькулятора я смог в консоль ввести exit и закрыть, тем самым, консоль. Данный код не компилируется, написано, что есть ошибка в последней фигурной скобки, требуется while, но я не понимаю как его ввести</p>\r\n\r\n<hr />\r\n<blockquote>\r\n<pre>\r\n<code>#include &lt;iostream&gt;\r\n#include &lt;stdio.h&gt;\r\n#include &lt;cstdlib&gt;\r\n#include &lt;locale&gt;\r\n#include &lt;math.h&gt;\r\nusing namespace std;\r\nint main()\r\n{\r\n    setlocale(LC_ALL, &quot;RUSSIAN&quot;);\r\n    string o;\r\n\r\n    do\r\n    {\r\n        cout &lt;&lt; &quot;Выполните действие&quot; &lt;&lt; endl;\r\n        float x, y, z;\r\n        char q;\r\n        cin &gt;&gt; x;\r\n        cin &gt;&gt; q;\r\n        cin &gt;&gt; y;\r\n        {\r\n            switch (q)\r\n            {\r\n                case &#39;^&#39;:\r\n                    z = pow(x, y);\r\n\r\n                case &#39;+&#39;:\r\n                    (z = x + y);\r\n\r\n                case &#39;-&#39;:\r\n                    (z = x - y);\r\n\r\n                case &#39;/&#39;:\r\n                    (z = x / y);\r\n\r\n                case &#39;*&#39;:\r\n                    (z = x * y);\r\n            }\r\n        }\r\n        cout &lt;&lt; (z) &lt;&lt; endl;\r\n        cin &gt;&gt; o;\r\n\r\n        if (o == &quot;exit&quot;)\r\n            exit(0);\r\n        else\r\n        {\r\n            exit(0);\r\n        }\r\n    }\r\n}</code></pre>\r\n</blockquote>\r\n', '2023-01-13 12:22:34');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(7, 'Probel', 'snovida2003@gmail.com', '$2y$10$EUkmMHoDza2Q0aHDQwFrf.wTaOV/cXmxrAk6vV5sUTQicYaHMmS1q'),
(8, 'User1', 'user1@mail.ru', '123123$2a$10$D2wT8rbqb2rYUHXGlETCOu6OWq93RGLKjenb1xDDfgPQDTMXoCw4m'),
(9, 'User2', 'user2@mail.ru', '$2a$10$/7c/nMJ5u6TgeMnaKYM11.sT1uaDmnabhPoIJhKKdkw3hg1yYbs8C'),
(10, 'User3', 'user3@mail.ru', '$2a$10$vd.UPbsHptmUc5ibApkHuOCNBxZ1TuTbG.L93LeDw6F1CZIv03qpy'),
(11, 'User4', 'user4@mail.ru', '$2a$10$IRAK1h/aau9gKfMF2LgQ8OdYMqyQgIVumWND6D0LdJmAzPAKcOO4W'),
(12, 'Snovida', 'snovida2003@gmail.com', '$2y$10$evvwFV8Ittq6RtjSxPmyUOhfKm3lrqXLSZNpVlxEfg6UQseEw4ZRi');

-- --------------------------------------------------------

--
-- Структура таблицы `users_info`
--

CREATE TABLE `users_info` (
  `id` bigint UNSIGNED NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `online_date` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) NOT NULL,
  `gender` varchar(64) DEFAULT NULL,
  `birthday` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users_info`
--

INSERT INTO `users_info` (`id`, `registration_date`, `online_date`, `avatar`, `gender`, `birthday`) VALUES
(7, '2023-01-11 20:39:34', '2023-01-13 19:33:57', 'uploads/1673469574rHJSEINOeo4.jpg', 'Мужской', '2023-01-12 21:00:00'),
(8, '2023-01-11 22:41:35', NULL, 'default/1.png', 'Мужской', '2022-12-31 22:41:06'),
(9, '2023-01-11 22:42:13', NULL, 'default/1.png', 'Мужской', '2023-01-02 22:41:47'),
(10, '2023-01-11 22:42:13', NULL, 'default/1.png', 'Мужской', '2022-12-31 22:41:47'),
(11, '2023-01-11 22:42:28', NULL, 'default/1.png', 'Мужской', '2023-01-04 22:42:20'),
(12, '2023-01-13 12:10:39', '2023-01-14 00:18:30', 'default/1.png', 'Мужской', '2003-02-12 21:00:00');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `users_info`
--
ALTER TABLE `users_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `topics`
--
ALTER TABLE `topics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `users_info`
--
ALTER TABLE `users_info`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `topics_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `users_info`
--
ALTER TABLE `users_info`
  ADD CONSTRAINT `users_info_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
