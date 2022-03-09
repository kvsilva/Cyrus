-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09-Mar-2022 às 14:50
-- Versão do servidor: 10.4.22-MariaDB
-- versão do PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cyrus`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `account_plans`
--

CREATE TABLE `account_plans` (
  `id` int(3) NOT NULL,
  `name` varchar(40) NOT NULL,
  `duration` int(16) NOT NULL,
  `price` double(12,2) NOT NULL,
  `stack` int(3) NOT NULL DEFAULT 1,
  `maximum` int(3) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `account_purchase`
--

CREATE TABLE `account_purchase` (
  `id` int(16) NOT NULL,
  `user` int(16) NOT NULL,
  `plan` int(3) NOT NULL,
  `price` double(12,2) NOT NULL,
  `purchased_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `duration` int(16) NOT NULL,
  `revoked_by` int(16) DEFAULT NULL,
  `revoked_reason` varchar(4000) DEFAULT NULL,
  `revoked_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rescued_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `anime`
--

CREATE TABLE `anime` (
  `id` int(12) NOT NULL,
  `title` varchar(120) DEFAULT NULL,
  `original_title` varchar(120) DEFAULT NULL,
  `synopsis` text DEFAULT NULL,
  `start_date` date DEFAULT current_timestamp(),
  `end_date` date DEFAULT NULL,
  `mature` int(1) DEFAULT 0 CHECK (`mature` in (0,1)),
  `launch_day` int(1) DEFAULT NULL CHECK (`launch_day` in (1,2,3,4,5,6,7)),
  `source` int(2) NOT NULL,
  `audience` int(2) NOT NULL,
  `trailer` varchar(1000) DEFAULT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `anime_status`
--

CREATE TABLE `anime_status` (
  `id` int(2) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `audience`
--

CREATE TABLE `audience` (
  `id` int(2) NOT NULL,
  `name` varchar(40) NOT NULL,
  `age` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `dubbing`
--

CREATE TABLE `dubbing` (
  `id` int(18) NOT NULL,
  `video` int(16) NOT NULL,
  `language` int(3) NOT NULL,
  `path` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `gender`
--

CREATE TABLE `gender` (
  `id` int(3) NOT NULL,
  `name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `global_settings`
--

CREATE TABLE `global_settings` (
  `name` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `value_binary` blob DEFAULT NULL,
  `data_type` varchar(50) NOT NULL DEFAULT 'string'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `language`
--

CREATE TABLE `language` (
  `id` int(3) NOT NULL,
  `code` varchar(25) NOT NULL,
  `name` varchar(50) NOT NULL,
  `original_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `log`
--

CREATE TABLE `log` (
  `id` int(30) NOT NULL,
  `user` int(16) NOT NULL,
  `action_type` int(4) DEFAULT NULL,
  `arguments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_action`
--

CREATE TABLE `log_action` (
  `id` int(6) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `permission`
--

CREATE TABLE `permission` (
  `id` int(4) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `name` varchar(60) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `punishment`
--

CREATE TABLE `punishment` (
  `id` int(24) NOT NULL,
  `user` int(16) NOT NULL,
  `punishment_type` int(3) NOT NULL,
  `reason` varchar(4000) NOT NULL,
  `lasts_until` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `performed_by` int(16) NOT NULL,
  `revoked_by` int(16) DEFAULT NULL,
  `revoked_reason` varchar(4000) DEFAULT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `punishment_type`
--

CREATE TABLE `punishment_type` (
  `id` int(3) NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `resource`
--

CREATE TABLE `resource` (
  `id` int(24) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` varchar(800) DEFAULT NULL,
  `extension` varchar(10) NOT NULL,
  `path` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `role`
--

CREATE TABLE `role` (
  `id` int(3) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `role_permission`
--

CREATE TABLE `role_permission` (
  `role` int(3) NOT NULL,
  `permission` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `season`
--

CREATE TABLE `season` (
  `anime` int(16) NOT NULL,
  `numeration` int(3) NOT NULL,
  `name` varchar(200) NOT NULL,
  `synopsis` text DEFAULT NULL,
  `release_date` date NOT NULL DEFAULT current_timestamp(),
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `source_type`
--

CREATE TABLE `source_type` (
  `id` int(2) NOT NULL,
  `name` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `subtitle`
--

CREATE TABLE `subtitle` (
  `id` int(18) NOT NULL,
  `video` int(16) NOT NULL,
  `language` int(3) NOT NULL,
  `path` varchar(500) DEFAULT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ticket`
--

CREATE TABLE `ticket` (
  `id` int(16) NOT NULL,
  `user` int(16) NOT NULL,
  `title` varchar(100) NOT NULL,
  `performed_by` int(16) NOT NULL,
  `status` int(2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `closed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `closed_by` int(16) DEFAULT NULL,
  `evaluation` int(2) NOT NULL DEFAULT 0 CHECK (`evaluation` >= 0 and `evaluation` <= 10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ticket_message`
--

CREATE TABLE `ticket_message` (
  `id` int(16) NOT NULL,
  `ticket` int(16) NOT NULL,
  `author` int(16) NOT NULL,
  `content` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ticket_message_attachment`
--

CREATE TABLE `ticket_message_attachment` (
  `message` int(16) NOT NULL,
  `resource` int(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ticket_status`
--

CREATE TABLE `ticket_status` (
  `id` int(2) NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `id` int(16) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(40) NOT NULL,
  `birthdate` date NOT NULL,
  `sex` int(1) DEFAULT NULL CHECK (`sex` in (1,2,3)),
  `creation_date` date DEFAULT current_timestamp(),
  `status` varchar(240) DEFAULT NULL,
  `profile_image` int(24) DEFAULT NULL,
  `profile_background` int(24) DEFAULT NULL,
  `about_me` text DEFAULT NULL,
  `verified` int(1) DEFAULT 0 CHECK (`verified` in (0,1)),
  `display_language` int(3) DEFAULT NULL,
  `email_communication_language` int(3) DEFAULT NULL,
  `translation_language` int(3) DEFAULT NULL,
  `night_mode` int(1) DEFAULT 0 CHECK (`night_mode` in (0,1)),
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_anime_status`
--

CREATE TABLE `user_anime_status` (
  `user` int(16) NOT NULL,
  `anime` int(16) NOT NULL,
  `status` int(2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_role`
--

CREATE TABLE `user_role` (
  `user` int(16) NOT NULL,
  `role` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `video`
--

CREATE TABLE `video` (
  `id` int(16) NOT NULL,
  `anime` int(16) NOT NULL,
  `season` int(3) DEFAULT NULL,
  `video_type` int(2) NOT NULL,
  `numeration` int(6) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `synopsis` text DEFAULT NULL,
  `duration` int(7) NOT NULL,
  `opening_start` int(7) DEFAULT NULL,
  `opening_end` int(7) DEFAULT NULL,
  `ending_start` int(7) DEFAULT NULL,
  `ending_end` int(7) DEFAULT NULL,
  `available` int(1) DEFAULT 1 CHECK (`available` in (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `account_plans`
--
ALTER TABLE `account_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Índices para tabela `account_purchase`
--
ALTER TABLE `account_purchase`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `anime`
--
ALTER TABLE `anime`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `anime_status`
--
ALTER TABLE `anime_status`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `audience`
--
ALTER TABLE `audience`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `dubbing`
--
ALTER TABLE `dubbing`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `global_settings`
--
ALTER TABLE `global_settings`
  ADD PRIMARY KEY (`name`);

--
-- Índices para tabela `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `log_action`
--
ALTER TABLE `log_action`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tag` (`tag`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Índices para tabela `punishment`
--
ALTER TABLE `punishment`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `punishment_type`
--
ALTER TABLE `punishment_type`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `resource`
--
ALTER TABLE `resource`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Índices para tabela `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`role`,`permission`);

--
-- Índices para tabela `season`
--
ALTER TABLE `season`
  ADD PRIMARY KEY (`anime`,`numeration`);

--
-- Índices para tabela `source_type`
--
ALTER TABLE `source_type`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `subtitle`
--
ALTER TABLE `subtitle`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `ticket_message`
--
ALTER TABLE `ticket_message`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `ticket_message_attachment`
--
ALTER TABLE `ticket_message_attachment`
  ADD PRIMARY KEY (`message`,`resource`);

--
-- Índices para tabela `ticket_status`
--
ALTER TABLE `ticket_status`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `user_anime_status`
--
ALTER TABLE `user_anime_status`
  ADD PRIMARY KEY (`user`,`anime`,`status`);

--
-- Índices para tabela `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user`,`role`);

--
-- Índices para tabela `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `account_plans`
--
ALTER TABLE `account_plans`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `account_purchase`
--
ALTER TABLE `account_purchase`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `anime`
--
ALTER TABLE `anime`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `anime_status`
--
ALTER TABLE `anime_status`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `audience`
--
ALTER TABLE `audience`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `dubbing`
--
ALTER TABLE `dubbing`
  MODIFY `id` int(18) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `gender`
--
ALTER TABLE `gender`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `language`
--
ALTER TABLE `language`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `log`
--
ALTER TABLE `log`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `log_action`
--
ALTER TABLE `log_action`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `punishment`
--
ALTER TABLE `punishment`
  MODIFY `id` int(24) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `punishment_type`
--
ALTER TABLE `punishment_type`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `resource`
--
ALTER TABLE `resource`
  MODIFY `id` int(24) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `role`
--
ALTER TABLE `role`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `source_type`
--
ALTER TABLE `source_type`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `subtitle`
--
ALTER TABLE `subtitle`
  MODIFY `id` int(18) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ticket_message`
--
ALTER TABLE `ticket_message`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ticket_status`
--
ALTER TABLE `ticket_status`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `video`
--
ALTER TABLE `video`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
