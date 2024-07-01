-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2024 at 12:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `23189636`
--

-- --------------------------------------------------------

--
-- Table structure for table `diaries`
--

CREATE TABLE `diaries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diaries`
--

INSERT INTO `diaries` (`id`, `user_id`, `title`, `description`) VALUES
(1, 1, 'Database and Web Application', 'I learned php and mysql.'),
(2, 1, 'June🛒🎨🎡🎢', 'A month full of surprises.'),
(3, 1, 'Day-out with my friends🎀', 'a beautiful landscape'),
(4, 1, 'Java ', 'Doing code together for final assessment.'),
(5, 1, 'Basketball🏀', 'Jersey no.4'),
(6, 2, 'Nature', 'Landscapes, waterfall are beautiful.'),
(7, 2, 'Art', 'being creative');

-- --------------------------------------------------------

--
-- Table structure for table `entries`
--

CREATE TABLE `entries` (
  `id` int(11) NOT NULL,
  `diary_id` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `title` varchar(100) NOT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `entries`
--

INSERT INTO `entries` (`id`, `diary_id`, `date_created`, `title`, `content`) VALUES
(1, 1, '2024-06-20 14:45:52', 'php', 'Today, I delved into the world of PHP, a versatile scripting language widely used for web development. PHP, standing for Hypertext Preprocessor, impressed me with its ability to embed directly into HTML and generate dynamic web content seamlessly.'),
(2, 1, '2024-06-20 14:45:59', 'mysql', 'Today I focused on MySQL tasks, refining database structure and optimizing queries for efficiency. Addressed security concerns and resolved a minor data synchronization issue promptly.&nbsp;'),
(3, 1, '2024-06-20 14:50:06', 'xamp', 'Today I downloaded xamp in my laptop.&nbsp;'),
(4, 5, '2024-06-20 15:07:48', 'match', 'Today I went to hoops to play basketball. I had fun playing it with my friends and I won it.'),
(5, 2, '2024-06-20 15:14:39', 'Day 1', 'Today started with a mysterious letter at my door. It said,&nbsp;<div><b>Get ready for surprises this month.</b></div><div>&nbsp;I felt excited and curious about what was to come.</div>'),
(6, 2, '2024-06-20 15:16:20', 'concert', 'I stumbled upon a surprise concert in the park today. The music was so good that I ended up dancing with new friends. It reminded me how fun unexpected moments can be.'),
(7, 2, '2024-06-20 15:17:21', 'art show', 'I got invited to an art show with really cool and unusual art. It was amazing to see how creative people can be. It showed me that art can surprise and inspire us.'),
(8, 2, '2024-06-20 15:18:18', 'small village', 'I took a spontaneous trip and discovered a beautiful small village. The people there were so friendly and welcoming. It reminded me how great it is to explore new places.'),
(9, 3, '2024-06-20 17:48:44', 'friends', '<div><div><span var(--bs-body-bg);=\\\"\\\" color:=\\\"\\\" var(--bs-body-color);=\\\"\\\" font-family:=\\\"\\\" var(--bs-body-font-family);=\\\"\\\" font-size:=\\\"\\\" var(--bs-body-font-size);=\\\"\\\" font-weight:=\\\"\\\" var(--bs-body-font-weight);=\\\"\\\" text-align:=\\\"\\\" var(--bs-body-text-align);\\\\\\\"=\\\"\\\">Today was an amazing day out with friends. We started with breakfast at our favorite café, where I enjoyed a delicious stack of pancakes and freshly brewed coffee. The weather was perfect, sunny and warm, setting a great tone for the day. We headed to the local park and rented bikes, spending a few hours riding the trails, laughing, and enjoying the beautiful scenery. We stopped by the lake for a picnic lunch, Sarah had made some amazing sandwiches, and we shared snacks and stories. In the afternoon, we checked out the new art exhibit downtown. The gallery was impressive, and we explored various installations, sparking interesting conversations and debates among us. We wrapped up the day with dinner at a cozy Italian restaurant. The pasta was incredible, and we reminisced about old times while making plans for the summer. We finished the evening with gelato and a walk along the river, enjoying the city lights. I got back home feeling grateful for such a fun day with friends. After quickly reviewing some notes for tomorrow’s classes, I relaxed with a chapter from&nbsp;<i>The Night Circus&nbsp;</i>before bed.&nbsp;</span></div><div><span var(--bs-body-bg);=\\\"\\\" color:=\\\"\\\" var(--bs-body-color);=\\\"\\\" font-family:=\\\"\\\" var(--bs-body-font-family);=\\\"\\\" font-size:=\\\"\\\" var(--bs-body-font-size);=\\\"\\\" font-weight:=\\\"\\\" var(--bs-body-font-weight);=\\\"\\\" text-align:=\\\"\\\" var(--bs-body-text-align);\\\\\\\"=\\\"\\\">Goodnight.&nbsp;&nbsp;</span></div></div>'),
(10, 4, '2024-06-20 21:55:15', 'Coding', 'As the final week are approaching we need to submit our final assessment. We need to work in a group of 2. Today as usual,&nbsp; we are coding to make flight management system. I hope we can finish this on time😭.'),
(11, 6, '2024-06-20 22:29:16', 'Hike', 'Today I went to hike with my friends. We decided to go for a short hiking. I had a lot of fun and the scenarios where so good like it was so beautiful.&nbsp;'),
(12, 7, '2024-06-21 15:08:39', 'modern art', '');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `tag_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag_name`) VALUES
(1, 'code'),
(2, 'vs'),
(3, 'programming_language'),
(4, 'comment'),
(5, 'xampp'),
(6, 'download'),
(7, 'new_month'),
(8, 'park'),
(9, 'music'),
(10, 'fun'),
(11, 'art'),
(12, 'creative'),
(13, 'trip'),
(14, 'new place'),
(15, 'explore'),
(16, 'surpise'),
(17, 'task'),
(18, 'ball'),
(19, 'sport'),
(20, 'athlete'),
(21, 'language'),
(22, 'nature'),
(23, 'colours');

-- --------------------------------------------------------

--
-- Table structure for table `tags_entries`
--

CREATE TABLE `tags_entries` (
  `tag_id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags_entries`
--

INSERT INTO `tags_entries` (`tag_id`, `entry_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(17, 2),
(6, 3),
(18, 4),
(19, 4),
(20, 4),
(7, 5),
(16, 5),
(8, 6),
(9, 6),
(11, 7),
(12, 7),
(13, 8),
(14, 8),
(15, 8),
(21, 10),
(10, 11),
(22, 11),
(23, 12);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `about_user` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `about_user`) VALUES
(1, 'Prasansha.Tamang@mail.bcu.ac.uk', '$2y$10$upjPvzva08PQagJmTANp/uv1pdflPnTzAvNWEg5nBVVGfvfhg28Ti', 'Hello!\nI am a student currently studying Bachelor of Science with Honours Computer and Data Science in   Birmingham City University. '),
(2, 'prasansha@gmail.com', '$2y$10$XaoxxVW2YCeYoefyjEw1DuitgwY3KBf.vO6K7et0gG4oB..sWGk96', 'Someone who loves to watch sunsets.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diaries`
--
ALTER TABLE `diaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `entries`
--
ALTER TABLE `entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `diary_id` (`diary_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags_entries`
--
ALTER TABLE `tags_entries`
  ADD PRIMARY KEY (`entry_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `diaries`
--
ALTER TABLE `diaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `entries`
--
ALTER TABLE `entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diaries`
--
ALTER TABLE `diaries`
  ADD CONSTRAINT `diaries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `entries`
--
ALTER TABLE `entries`
  ADD CONSTRAINT `entries_ibfk_1` FOREIGN KEY (`diary_id`) REFERENCES `diaries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tags_entries`
--
ALTER TABLE `tags_entries`
  ADD CONSTRAINT `tags_entries_ibfk_1` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tags_entries_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
