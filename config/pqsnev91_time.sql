-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 08, 2019 at 04:07 PM
-- Server version: 5.5.61-38.13-log
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pqsnev91_time`
--

-- --------------------------------------------------------

--
-- Table structure for table `projectmembers`
--

CREATE TABLE `projectmembers` (
  `id` int(10) NOT NULL,
  `project_id` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projectmembers`
--

INSERT INTO `projectmembers` (`id`, `project_id`, `userid`, `created`, `modified`) VALUES
(2, 4, 14, '2019-05-14 09:10:15', '2019-05-14 09:10:15'),
(3, 4, 15, '2019-05-14 09:10:18', '2019-05-14 09:10:18'),
(4, 4, 16, '2019-05-14 09:10:24', '2019-05-14 09:10:24'),
(5, 4, 17, '2019-05-14 09:10:27', '2019-05-14 09:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `created_userid` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_desc` varchar(500) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `status_change_date` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `created_userid`, `project_name`, `project_desc`, `status`, `status_change_date`, `created`, `modified`) VALUES
(2, 10, 'project-1', '{\"ops\":[{\"insert\":\"hurry\\n\"}]}', 0, NULL, '2019-03-07 17:54:57', '2019-03-07 18:35:37'),
(3, 13, 'farhan project-1', NULL, 0, NULL, '2019-03-22 16:39:19', '2019-03-22 16:39:19'),
(4, 13, 'farhan project-2', NULL, 0, NULL, '2019-03-22 16:40:02', '2019-03-22 16:40:02'),
(5, 12, 'saiful project-1', NULL, 0, NULL, '2019-03-22 16:40:59', '2019-03-22 16:40:59'),
(6, 12, 'saiful project-2', NULL, 0, NULL, '2019-03-22 16:41:10', '2019-03-22 16:41:10');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `name` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`name`, `value`, `created`, `modified`) VALUES
('email_config', 'phpmail', '2018-12-03 00:00:00', '2019-04-22 11:14:21'),
('footer_text', 'Copyright © 2019. Time Tracker', '2018-12-12 12:49:39', '2019-04-22 11:14:21'),
('smtp_config', 'smtp.gmail.com,fnfsoftplay@gmail.com,T$hoh@xD27,587,tls', '2018-12-15 15:56:26', '2019-04-22 11:14:21'),
('system_email', 'support@tracker.com', '2018-12-14 17:54:30', '2019-04-22 11:14:21'),
('system_logo', 'http://codex.pqsnetwork.com/time/img/uploads/logo-1219.jpg', '2019-04-22 11:14:21', '2019-04-22 11:14:21'),
('system_title', 'Time Tracker', '2018-12-12 12:48:26', '2019-04-22 11:14:21');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `task_title` varchar(255) NOT NULL,
  `task_content` varchar(1500) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `task_title`, `task_content`, `created`, `modified`) VALUES
(2, 2, 'lin e up', '{\"ops\":[{\"insert\":\"do the work\\n\"}]}', '2019-03-07 17:55:50', '2019-03-07 17:55:50'),
(3, 2, 'sdfsdf', '{\"ops\":[{\"insert\":\"sdf\\n\"}]}', '2019-03-08 17:33:51', '2019-03-08 17:33:51'),
(4, 5, 'Saiful', '{\"ops\":[{\"insert\":\"sdfdf\\n\"}]}', '2019-05-14 09:03:08', '2019-05-14 09:03:08'),
(5, 5, 'Saifulsdfsd', '{\"ops\":[{\"insert\":\"dsfsafds\\n\"}]}', '2019-05-14 09:09:38', '2019-05-14 09:09:38');

-- --------------------------------------------------------

--
-- Table structure for table `timesheets`
--

CREATE TABLE `timesheets` (
  `timeid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `day` date NOT NULL,
  `time_slot` datetime NOT NULL,
  `minutes` int(2) DEFAULT '0',
  `project_id` int(10) NOT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `screenshot` varchar(255) DEFAULT NULL,
  `screenshot_time` datetime DEFAULT NULL,
  `keystrokes_count` int(5) DEFAULT NULL,
  `mousemove_count` int(5) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timesheets`
--

INSERT INTO `timesheets` (`timeid`, `userid`, `day`, `time_slot`, `minutes`, `project_id`, `project_name`, `screenshot`, `screenshot_time`, `keystrokes_count`, `mousemove_count`, `created`, `modified`) VALUES
(1, 11, '2019-03-08', '2019-03-08 00:00:00', 10, 2, '', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-03-08 00:00:00', NULL, NULL, '2019-03-07 18:10:19', '2019-03-07 18:10:19'),
(2, 11, '2019-03-08', '2019-03-08 00:10:00', 10, 2, '', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-03-08 00:10:00', NULL, NULL, '2019-03-07 18:10:19', '2019-03-07 18:10:19'),
(3, 11, '2019-03-08', '2019-03-08 00:20:00', 10, 2, '', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-03-08 00:20:00', NULL, NULL, '2019-03-07 18:33:55', '2019-03-07 18:33:55'),
(4, 14, '2019-04-22', '2019-04-22 00:00:00', 10, 2, 'project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-04-22 00:00:00', NULL, NULL, '2019-04-22 11:04:39', '2019-04-22 11:04:39'),
(5, 14, '2019-04-22', '2019-04-22 00:10:00', 10, 2, 'project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-04-22 00:10:00', NULL, NULL, '2019-04-22 11:04:39', '2019-04-22 11:04:39'),
(6, 14, '2019-04-22', '2019-04-22 00:20:00', 10, 2, 'project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-04-22 00:20:00', NULL, NULL, '2019-04-22 11:04:39', '2019-04-22 11:04:39'),
(7, 14, '2019-04-22', '2019-04-22 00:30:00', 10, 2, 'project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-04-22 00:30:00', NULL, NULL, '2019-04-22 11:04:39', '2019-04-22 11:04:39'),
(8, 14, '2019-04-22', '2019-04-22 00:40:00', 10, 2, 'project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-04-22 00:40:00', NULL, NULL, '2019-04-22 11:04:39', '2019-04-22 11:04:39'),
(9, 14, '2019-04-22', '2019-04-22 00:50:00', 10, 2, 'project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-04-22 00:50:00', NULL, NULL, '2019-04-22 11:04:39', '2019-04-22 11:04:39'),
(10, 14, '2019-04-22', '2019-04-22 01:00:00', 10, 2, 'project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-04-22 01:00:00', NULL, NULL, '2019-04-22 11:04:39', '2019-04-22 11:04:39'),
(11, 14, '2019-04-22', '2019-04-22 01:10:00', 10, 2, 'project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-04-22 01:10:00', NULL, NULL, '2019-04-22 11:04:39', '2019-04-22 11:04:39'),
(12, 14, '2019-05-14', '2019-05-14 00:00:00', 10, 3, 'farhan project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-05-14 00:00:00', NULL, NULL, '2019-05-14 09:01:17', '2019-05-14 09:01:17'),
(13, 14, '2019-05-14', '2019-05-14 00:10:00', 10, 3, 'farhan project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-05-14 00:10:00', NULL, NULL, '2019-05-14 09:01:17', '2019-05-14 09:01:17'),
(14, 14, '2019-05-14', '2019-05-14 00:20:00', 10, 3, 'farhan project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-05-14 00:20:00', NULL, NULL, '2019-05-14 09:01:17', '2019-05-14 09:01:17'),
(15, 14, '2019-05-14', '2019-05-14 00:30:00', 10, 3, 'farhan project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-05-14 00:30:00', NULL, NULL, '2019-05-14 09:01:17', '2019-05-14 09:01:17'),
(16, 14, '2019-05-14', '2019-05-14 00:40:00', 10, 3, 'farhan project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-05-14 00:40:00', NULL, NULL, '2019-05-14 09:01:17', '2019-05-14 09:01:17'),
(17, 14, '2019-05-14', '2019-05-14 00:50:00', 10, 3, 'farhan project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-05-14 00:50:00', NULL, NULL, '2019-05-14 09:01:17', '2019-05-14 09:01:17'),
(18, 14, '2019-05-14', '2019-05-14 01:00:00', 10, 3, 'farhan project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-05-14 01:00:00', NULL, NULL, '2019-05-14 09:01:17', '2019-05-14 09:01:17'),
(19, 14, '2019-05-14', '2019-05-14 01:10:00', 10, 3, 'farhan project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-05-14 01:10:00', NULL, NULL, '2019-05-14 09:01:17', '2019-05-14 09:01:17'),
(20, 14, '2019-05-14', '2019-05-14 01:20:00', 10, 3, 'farhan project-1', 'http://codex.pqsnetwork.com/time/img/manual-screenshot.png', '2019-05-14 01:20:00', NULL, NULL, '2019-05-14 09:01:17', '2019-05-14 09:01:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `designation` varchar(50) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `invite_email` varchar(50) DEFAULT NULL,
  `failed_login_count` int(2) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `type`, `email`, `password`, `fname`, `lname`, `designation`, `status`, `invite_email`, `failed_login_count`, `thumb`, `timezone`, `created`, `modified`) VALUES
(4, 'admin', 'admin@time.com', '$2y$10$54fq8tBHM1Cjh91JF75ffer4tua6dw9raIgM55qc/jcGkTK.9NRAq', 'JUBAER', 'ROKON', NULL, 1, '', 5, 'http://codex.pqsnetwork.com/time/img/uploads/pp-3688.jpg', 'America/Los_Angeles', '2018-11-22 11:58:55', '2019-03-22 15:07:56'),
(12, 'client', 'saiful@gmail.com', '$2y$10$2mXeXIdUWiWA03vtsbzrIeF8eixKsjR5RuYkoj5dYDw6aNw12HIe2', 'MD. SAIFUL ', 'ISLAM', NULL, 1, NULL, NULL, 'http://codex.pqsnetwork.com/time/img/uploads/saiful-3153.jpg', NULL, '2019-03-22 15:02:02', '2019-03-22 15:15:24'),
(13, 'client', 'farhan@gmail.com', '$2y$10$xIaANbL1C9zlfV4.WeamHOfsqEMTuEiUMdFno5py1Jvnli94SvfX.', 'FARHAN ', 'ELAHI', NULL, 1, NULL, NULL, 'http://codex.pqsnetwork.com/time/img/uploads/farhan-7286.jpg', NULL, '2019-03-22 15:02:57', '2019-03-22 15:13:18'),
(14, 'employee', 'mahir@gmail.com', '$2y$10$m.k2s/1OqREOZGCPPZyl1.AfiUOENT2PktZUGrLXSus.0WnVTXdge', 'MAHIR', 'AZRAF', NULL, 1, NULL, NULL, 'http://codex.pqsnetwork.com/time/img/uploads/sadaf-5441.jpg', NULL, '2019-03-22 15:04:01', '2019-03-22 15:17:51'),
(15, 'employee', 'ahmed@gmail.com', '$2y$10$c1M/O0Mq7Qy1i1VQwJPQ0eqIvTXxdhbZ3VcZqNrz9ev4l7pz8cmba', 'KOUSHIK', 'AHMED', NULL, 1, NULL, NULL, 'http://codex.pqsnetwork.com/time/img/uploads/bappy-5248.jpg', NULL, '2019-03-22 15:04:45', '2019-03-22 15:32:40'),
(16, 'employee', 'ahad@gmail.com', '$2y$10$Y24vzROB96mz2t.35LQBaeUGy5MKveP6Tb7MIetVfmX.jwmd7rlx6', 'LABIB', 'AHAD', NULL, 1, NULL, NULL, 'http://codex.pqsnetwork.com/time/img/uploads/labibn-6139.jpg', NULL, '2019-03-22 15:05:23', '2019-03-22 15:28:55'),
(17, 'employee', 'biswas@gmail.com', '$2y$10$NAm85bO2AmLG77er494KgOBdfPj8gJbjWFunAZ.F054Fi8oMlCLJO', 'ZUNAED', 'BISWAS', NULL, 1, NULL, NULL, 'http://codex.pqsnetwork.com/time/img/uploads/ruman-7508.jpg', NULL, '2019-03-22 15:06:09', '2019-03-22 15:18:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projectmembers`
--
ALTER TABLE `projectmembers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheets`
--
ALTER TABLE `timesheets`
  ADD PRIMARY KEY (`timeid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projectmembers`
--
ALTER TABLE `projectmembers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `timesheets`
--
ALTER TABLE `timesheets`
  MODIFY `timeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
