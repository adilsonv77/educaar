-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db:3307
-- Tempo de geração: 08/08/2023 às 21:09
-- Versão do servidor: 8.0.33
-- Versão do PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `educaar`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `activities`
--

CREATE TABLE `activities` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_id` bigint UNSIGNED DEFAULT NULL,
  `glb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marcador` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `professor_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `alunos_turmas`
--

CREATE TABLE `alunos_turmas` (
  `aluno_id` bigint UNSIGNED NOT NULL,
  `turma_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `alunos_turmas`
--

INSERT INTO `alunos_turmas` (`aluno_id`, `turma_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(3, 1, NULL, NULL),
(2, 2, NULL, NULL),
(1, 2, NULL, NULL),
(5, 5, NULL, NULL),
(4, 4, NULL, NULL),
(2, 4, NULL, NULL),
(3, 4, NULL, NULL),
(1, 5, NULL, NULL),
(1, 1, NULL, NULL),
(3, 1, NULL, NULL),
(2, 2, NULL, NULL),
(1, 2, NULL, NULL),
(5, 5, NULL, NULL),
(4, 4, NULL, NULL),
(2, 4, NULL, NULL),
(3, 4, NULL, NULL),
(1, 5, NULL, NULL),
(11, 5, '2023-06-07 17:54:03', '2023-08-03 17:05:05'),
(12, 5, '2023-06-07 17:54:03', '2023-08-03 17:05:06'),
(13, 5, '2023-06-07 17:54:03', '2023-08-03 17:05:06'),
(14, 5, '2023-06-07 17:54:03', '2023-08-03 17:05:06'),
(15, 5, '2023-06-07 17:54:03', '2023-08-03 17:05:07'),
(16, 5, '2023-06-07 17:54:03', '2023-08-03 17:05:07'),
(17, 5, '2023-06-07 17:54:03', '2023-08-03 17:05:07'),
(18, 5, '2023-06-07 17:54:03', '2023-08-03 17:05:08'),
(19, 5, '2023-06-07 17:54:03', '2023-08-03 17:05:08'),
(20, 5, '2023-06-07 17:54:03', '2023-08-03 17:05:09'),
(21, 5, '2023-06-07 17:54:03', '2023-08-03 17:05:09'),
(22, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:09'),
(23, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:09'),
(24, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:10'),
(25, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:10'),
(26, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:10'),
(27, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:11'),
(28, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:11'),
(29, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:11'),
(30, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:12'),
(31, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:12'),
(32, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:12'),
(33, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:13'),
(34, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:13'),
(35, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:13'),
(36, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:14'),
(37, 5, '2023-06-07 17:54:04', '2023-08-03 17:05:14'),
(38, 5, '2023-06-07 17:54:05', '2023-08-03 17:05:14'),
(39, 5, '2023-06-07 17:54:05', '2023-08-03 17:05:15'),
(40, 5, '2023-06-07 17:54:05', '2023-08-03 17:05:15'),
(41, 5, '2023-06-07 17:54:05', '2023-08-03 17:05:15'),
(42, 5, '2023-06-07 17:54:05', '2023-08-03 17:05:15'),
(44, 7, NULL, NULL),
(78, 8, '2023-07-13 14:36:57', '2023-07-13 14:36:57'),
(79, 8, '2023-07-13 14:36:57', '2023-07-13 14:36:57'),
(113, 6, '2023-07-13 14:43:09', '2023-07-13 14:52:14'),
(109, 8, '2023-07-13 14:49:23', '2023-07-13 14:49:23');

-- --------------------------------------------------------

--
-- Estrutura para tabela `anos_letivos`
--

CREATE TABLE `anos_letivos` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bool_atual` tinyint(1) NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `anos_letivos`
--

INSERT INTO `anos_letivos` (`id`, `name`, `bool_atual`, `school_id`, `created_at`, `updated_at`) VALUES
(1, '2021', 0, 1, NULL, NULL),
(2, '2022', 0, 1, NULL, NULL),
(3, '2023', 0, 1, NULL, NULL),
(4, '2024', 1, 1, NULL, NULL),
(5, '9999', 0, 1, '2023-06-02 19:46:39', '2023-06-02 19:46:39');

-- --------------------------------------------------------

--
-- Estrutura para tabela `contents`
--

CREATE TABLE `contents` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `disciplina_id` bigint UNSIGNED NOT NULL,
  `turma_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fechado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `contents`
--

INSERT INTO `contents` (`id`, `name`, `user_id`, `disciplina_id`, `turma_id`, `created_at`, `updated_at`, `fechado`) VALUES
(1, 'Fisica quantica', 1, 1, 1, '2023-05-12 19:08:13', '2023-06-29 17:36:22', 0),
(2, 'Eletromagnetismo', 1, 1, 1, '2023-05-12 19:08:42', '2023-07-14 17:42:49', 0),
(4, 'Tensão', 1, 2, 1, '2023-06-07 17:32:26', '2023-07-14 17:52:23', 0),
(5, 'O Filho do Grúfalo', 43, 8, 10, '2023-06-09 01:07:29', '2023-06-09 01:08:18', 0),
(6, 'Testeanimacao', 1, 1, 1, '2023-07-12 14:54:40', '2023-07-14 17:52:23', 0),
(8, 'Pensante', 3, 4, 14, '2023-07-14 16:18:59', '2023-07-14 16:18:59', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `disciplinas`
--

CREATE TABLE `disciplinas` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `disciplinas`
--

INSERT INTO `disciplinas` (`id`, `name`, `school_id`, `created_at`, `updated_at`) VALUES
(1, 'Fisica', 1, NULL, NULL),
(2, 'Quimica', 1, NULL, NULL),
(3, 'Biologia', 1, NULL, NULL),
(4, 'Filosofia', 1, NULL, NULL),
(5, 'Matemática', 1, NULL, NULL),
(6, 'Comunicação e Sociedade', 1, NULL, NULL),
(7, 'História', 1, '2023-05-18 16:40:46', '2023-05-18 16:40:46'),
(8, 'Língua Portuguesa', 1, '2023-06-09 01:03:08', '2023-06-09 01:03:08');

-- --------------------------------------------------------

--
-- Estrutura para tabela `disciplinas_turmas_modelos`
--

CREATE TABLE `disciplinas_turmas_modelos` (
  `disciplina_id` bigint UNSIGNED NOT NULL,
  `turma_modelo_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `disciplinas_turmas_modelos`
--

INSERT INTO `disciplinas_turmas_modelos` (`disciplina_id`, `turma_modelo_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(1, 2, NULL, NULL),
(1, 3, NULL, NULL),
(1, 4, NULL, NULL),
(1, 12, '2023-07-14 14:52:29', '2023-07-14 14:52:29'),
(2, 1, NULL, NULL),
(2, 2, NULL, NULL),
(2, 3, NULL, NULL),
(2, 4, NULL, NULL),
(3, 1, NULL, NULL),
(3, 12, '2023-07-14 14:52:29', '2023-07-14 14:52:29'),
(3, 15, '2023-08-02 17:25:01', '2023-08-02 17:25:01'),
(4, 14, '2023-07-14 16:14:19', '2023-07-14 16:14:19'),
(4, 15, '2023-08-02 17:25:01', '2023-08-02 17:25:01'),
(5, 12, '2023-07-14 14:52:29', '2023-07-14 14:52:29'),
(6, 11, '2023-07-13 14:11:51', '2023-07-13 14:11:51'),
(7, 12, '2023-07-14 14:52:29', '2023-07-14 14:52:29'),
(8, 9, '2023-06-09 01:03:47', '2023-06-09 01:03:47'),
(8, 10, '2023-06-09 01:04:58', '2023-06-09 01:04:58'),
(8, 11, '2023-07-13 14:11:51', '2023-07-13 14:11:51'),
(8, 13, '2023-07-14 14:56:20', '2023-07-14 14:56:20');

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2021_02_11_133120_create_school_table', 1),
(4, '2021_02_12_162919_create_ano_letivo_table', 1),
(5, '2021_02_19_153110_create_turma_modelo_table', 1),
(6, '2021_02_22_143857_create_turma_table', 1),
(7, '2021_02_23_162128_create_aluno_turma_table', 1),
(8, '2021_04_25_133247_create_disciplina_table', 1),
(9, '2021_04_27_153636_create_disciplina_turma_modelo_table', 1),
(10, '2021_04_28_140659_create_turma_disciplina_table', 1),
(11, '2021_07_06_154729_create_contents_table', 1),
(12, '2021_07_06_154736_create_activities_table', 1),
(13, '2021_07_08_031430_create_questions_table', 1),
(14, '2021_07_11_125527_create_student_answers_table', 1),
(15, '2021_07_25_091359_create_student_access_activities_table', 1),
(16, '2021_07_25_092009_create_student_grades_table', 1),
(17, '2021_07_25_185418_create_student_time_activities_table', 1),
(18, '2021_07_25_200457_add_new_fields_student_answer', 1),
(19, '2021_07_25_202844_add_number_questions', 1),
(20, '2022_05_06_131838_add_school_id_on_users_table', 1),
(21, '2022_11_25_140000_alter_school_table', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `questions`
--

CREATE TABLE `questions` (
  `id` bigint UNSIGNED NOT NULL,
  `question` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `d` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity_id` bigint UNSIGNED DEFAULT NULL,
  `answer` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `schools`
--

CREATE TABLE `schools` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `qr_letra` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_numero` int UNSIGNED NOT NULL,
  `prof_atual_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `schools`
--

INSERT INTO `schools` (`id`, `name`, `created_at`, `updated_at`, `qr_letra`, `qr_numero`, `prof_atual_id`) VALUES
(1, 'Escola X', NULL, '2023-08-08 17:53:36', 'A', 1, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `student_access_activities`
--

CREATE TABLE `student_access_activities` (
  `id` bigint UNSIGNED NOT NULL,
  `activity_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `timesAccessActivity` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `student_answers`
--

CREATE TABLE `student_answers` (
  `id` bigint UNSIGNED NOT NULL,
  `question_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `alternative_answered` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `correct` tinyint(1) DEFAULT NULL,
  `activity_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `student_grades`
--

CREATE TABLE `student_grades` (
  `id` bigint UNSIGNED NOT NULL,
  `activity_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `correctQuestions` int DEFAULT NULL,
  `wrongQuestions` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `numberQuestions` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `student_time_activities`
--

CREATE TABLE `student_time_activities` (
  `id` bigint UNSIGNED NOT NULL,
  `timeEnterActivity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timeLeaveActivity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timeGeneral` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas`
--

CREATE TABLE `turmas` (
  `id` bigint UNSIGNED NOT NULL,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `turma_modelo_id` bigint UNSIGNED NOT NULL,
  `ano_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `turmas`
--

INSERT INTO `turmas` (`id`, `nome`, `turma_modelo_id`, `ano_id`, `school_id`, `created_at`, `updated_at`) VALUES
(1, '1a Serie 1', 1, 1, 1, NULL, NULL),
(2, '1a Serie 2', 1, 2, 1, NULL, NULL),
(3, '2a Serie 1', 2, 3, 1, NULL, NULL),
(4, '1a Serie 1', 1, 4, 1, NULL, NULL),
(5, '2a Serie 1', 2, 4, 1, NULL, NULL),
(6, 'Turma 1', 9, 4, 1, '2023-06-09 01:04:20', '2023-06-09 01:04:20'),
(7, '2º Ano - Turma 1', 10, 4, 1, '2023-06-09 01:05:15', '2023-06-09 01:48:23'),
(8, 'Teste', 1, 4, 1, '2023-07-13 14:36:02', '2023-07-13 14:36:02'),
(9, 'ana2', 1, 4, 1, '2023-08-08 18:01:21', '2023-08-08 18:01:21'),
(10, 'ana2', 1, 4, 1, '2023-08-08 18:01:56', '2023-08-08 18:01:56'),
(11, 'ana2', 1, 4, 1, '2023-08-08 18:03:09', '2023-08-08 18:03:09'),
(12, 'ana2', 1, 4, 1, '2023-08-08 18:06:29', '2023-08-08 18:06:29'),
(13, 'ana2', 1, 4, 1, '2023-08-08 18:06:47', '2023-08-08 18:06:47'),
(14, 'ana2', 1, 4, 1, '2023-08-08 18:07:09', '2023-08-08 18:07:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas_disciplinas`
--

CREATE TABLE `turmas_disciplinas` (
  `turma_id` bigint UNSIGNED NOT NULL,
  `disciplina_id` bigint UNSIGNED NOT NULL,
  `professor_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `turmas_disciplinas`
--

INSERT INTO `turmas_disciplinas` (`turma_id`, `disciplina_id`, `professor_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(1, 2, 4, NULL, NULL),
(1, 3, 5, NULL, NULL),
(2, 1, 1, NULL, NULL),
(2, 2, 4, NULL, NULL),
(2, 3, 5, NULL, NULL),
(4, 1, 1, NULL, NULL),
(4, 2, 1, NULL, NULL),
(4, 3, 5, NULL, NULL),
(3, 1, 1, NULL, NULL),
(3, 2, 4, NULL, NULL),
(5, 1, 1, NULL, NULL),
(5, 2, 4, NULL, NULL),
(1, 1, 1, NULL, NULL),
(1, 2, 4, NULL, NULL),
(1, 3, 5, NULL, NULL),
(2, 1, 1, NULL, NULL),
(2, 2, 4, NULL, NULL),
(2, 3, 5, NULL, NULL),
(4, 1, 1, NULL, NULL),
(4, 2, 1, NULL, NULL),
(4, 3, 5, NULL, NULL),
(3, 1, 1, NULL, NULL),
(3, 2, 4, NULL, NULL),
(5, 1, 1, NULL, NULL),
(5, 2, 4, NULL, NULL),
(6, 8, 1, '2023-06-09 01:04:20', '2023-06-09 01:04:20'),
(7, 8, 43, '2023-06-09 01:05:15', '2023-06-09 01:05:15'),
(8, 1, 1, '2023-07-13 14:36:02', '2023-07-13 14:36:02'),
(8, 2, 1, '2023-07-13 14:36:02', '2023-07-13 14:36:02'),
(8, 3, 1, '2023-07-13 14:36:02', '2023-07-13 14:36:02'),
(8, 1, 1, '2023-07-13 14:36:02', '2023-07-13 14:36:02'),
(8, 2, 1, '2023-07-13 14:36:02', '2023-07-13 14:36:02'),
(8, 3, 1, '2023-07-13 14:36:02', '2023-07-13 14:36:02'),
(14, 1, 5, '2023-08-08 18:07:09', '2023-08-08 18:07:09'),
(14, 2, 5, '2023-08-08 18:07:09', '2023-08-08 18:07:09'),
(14, 3, 5, '2023-08-08 18:07:09', '2023-08-08 18:07:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas_modelos`
--

CREATE TABLE `turmas_modelos` (
  `id` bigint UNSIGNED NOT NULL,
  `serie` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `turmas_modelos`
--

INSERT INTO `turmas_modelos` (`id`, `serie`, `school_id`, `created_at`, `updated_at`) VALUES
(1, '1a Serie', 1, NULL, NULL),
(2, '2a Serie', 1, NULL, NULL),
(3, '3a Serie', 1, NULL, NULL),
(4, '1a Serie Integral', 1, NULL, NULL),
(5, '1a Serie', 1, NULL, NULL),
(6, '2a Serie', 1, NULL, NULL),
(7, '3a Serie', 1, NULL, NULL),
(8, '1a Serie Integral', 1, NULL, NULL),
(9, '1º Ano Ensino Fundamental', 1, '2023-06-09 01:03:47', '2023-06-09 01:03:47'),
(10, '2º Ano Ensino Fundamental', 1, '2023-06-09 01:04:58', '2023-06-09 01:04:58'),
(11, 'Teste', 1, '2023-07-13 14:11:51', '2023-07-13 14:11:51'),
(12, 'testemodelo', 1, '2023-07-14 14:52:29', '2023-07-14 14:52:29'),
(13, 'maisuma', 1, '2023-07-14 14:56:20', '2023-07-14 14:56:20'),
(14, 'semnada', 1, '2023-07-14 14:57:18', '2023-07-14 14:57:18'),
(15, 'testeturmamodelo', 1, '2023-08-02 17:25:01', '2023-08-02 17:25:01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'student',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `school_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `type`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `school_id`) VALUES
(1, 'Cauê', 'prof', 'teacher', 'caue@gmail.com', NULL, '$2y$10$yGK2ulIXjXD92BSJek1ajeEiyVrGW0zv30UxFJXcyxwUFKhv0Rqri', NULL, NULL, NULL, 1),
(2, 'Joao', 'aluno', 'student', 'joao@gmail.com', NULL, '$2y$10$I32XkY.D3RMXf0oyqSYQquhfxKfL17CfQuZ6GFckDXLSqoeMPRXQa', NULL, NULL, NULL, 1),
(3, 'Pedro', 'admin', 'admin', 'pedro@gmail.com', NULL, '$2y$10$1Lt033y0DlVnevko0fHUXevhIUjrocZQ42.jcZAya5eeuIaCo9DLC', NULL, NULL, NULL, 1),
(4, 'prof01', 'prof01', 'teacher', 'prof01@gmail.com', NULL, '$2y$10$5JHGYXKWub1c4/mUfbWI/.p.djo.weY2WQHjjObSDo5SN00cfhgHO', NULL, NULL, NULL, 1),
(5, 'prof02', 'prof02', 'teacher', 'prof02@gmail.com', NULL, '$2y$10$0WSGY0sn1pxOxZbXZ31DU.cS5X/yWYnPkQPZjsxkc2OlDk6piV2dS', NULL, NULL, NULL, 1),
(6, 'prof03', 'prof03', 'teacher', 'prof03@gmail.com', NULL, '$2y$10$6KOOidgCbr/wN55EK5rD1eqzBXMDu3Xy6xkAtJGO6p6v1LcX.qfYS', NULL, NULL, NULL, 1),
(7, 'xico01', 'xico01', 'student', 'xico01@gmail.com', NULL, '$2y$10$de9BUKONTrayZKNXY9IYlOFb/RTQr2NbTT3.wfMxkfgvsbV8f5oxy', NULL, NULL, NULL, 1),
(8, 'xico02', 'xico02', 'student', 'xico02@gmail.com', NULL, '$2y$10$78B5qI3C3OrRKsCBp1Dg3..FB0j94upmsMgNF0cbnpR65qShb.7ee', NULL, NULL, NULL, 1),
(9, 'xico03', 'xico03', 'student', 'xico03@gmail.com', NULL, '$2y$10$V8hLaCI/XQzGLQdu.jU1.eMtd5wWaiEHZPIlQ9Aej/U.1kLuEvDyu', NULL, NULL, NULL, 1),
(10, 'xico04', 'xico04', 'student', 'xico04@gmail.com', NULL, '$2y$10$QuPfZ4lXorMv3qUHedgUTe5kXTt7a5dq8iO2EfCKrSYL0FK5wbHe2', NULL, NULL, NULL, 1),
(11, 'ADEMIR GUILHERME KRENKEL EGER', '4541808470', 'student', '@', NULL, '$2y$10$UBJIGz8t4B22vXf99mclyuomHF6MDjEZPNFQqJVPwYDdiQ5dVoZ.O', NULL, '2023-06-07 17:54:03', '2023-06-07 17:54:03', 1),
(12, 'AMANDA CRISTINA SARDAGNA', '4541466472', 'student', '@', NULL, '$2y$10$QUbqxCHWP9hh85wTViaZXu7NiPAqfVdUJ3vTF6u6uQEjyac9pQRWS', NULL, '2023-06-07 17:54:03', '2023-06-07 17:54:03', 1),
(13, 'ANA CLARA DE FREITAS PENA', '4545161285', 'student', '@', NULL, '$2y$10$4OhUOWA.eFNedETq0.nAeuzakWc/LYiSYCxmQyFposMaau4iol89C', NULL, '2023-06-07 17:54:03', '2023-06-07 17:54:03', 1),
(14, 'ANA JULIA BRITO COELHO DE MELLO', '4543705261', 'student', '@', NULL, '$2y$10$mSr566PbyqqCaKMP0IMG2e0wh8tq6aby6bJaQJg63T1g4kquJFQz2', NULL, '2023-06-07 17:54:03', '2023-06-07 17:54:03', 1),
(15, 'ANTONY HOBUS', '4545755312', 'student', '@', NULL, '$2y$10$VZg429cO/MR62Xllyg0WluLBu1I4PUibicl4XJWtbg7lojHiKGzt6', NULL, '2023-06-07 17:54:03', '2023-06-07 17:54:03', 1),
(16, 'BEATRIZ CORREIA', '4541884827', 'student', '@', NULL, '$2y$10$6dLyXWFhAZ44bjOEsXlsl.Z2npzdk6AFlenCYh2GswDRQW90g2uXK', NULL, '2023-06-07 17:54:03', '2023-06-07 17:54:03', 1),
(17, 'DJONATHAN GABRIEL DOMBROSKI DE ALMEIDA', '503819549', 'student', '@', NULL, '$2y$10$Z5flSwsDNbroWUvkjmDlSuWEUkVc2DS1REPwuXpEP7WOtu8sNRodi', NULL, '2023-06-07 17:54:03', '2023-06-07 17:54:03', 1),
(18, 'ELOÁ GABRIELLI FANO DE MACEDO', '4551021422', 'student', '@', NULL, '$2y$10$aLcuGrHo3tBNjI56pfNs6eFbj4cQNIiCh3ev2bQxkAfXXYX2/nUCC', NULL, '2023-06-07 17:54:03', '2023-06-07 17:54:03', 1),
(19, 'ERICK MATHEUS FRITEZ', '4500627927', 'student', '@', NULL, '$2y$10$Inyg3FqJe1jL.DGf/tv5CuxeVoloWqvfC5fz.XO.I4ezDe9XVP2lm', NULL, '2023-06-07 17:54:03', '2023-06-07 17:54:03', 1),
(20, 'EVELYN AMANDA FELÍCIO', '4546977734', 'student', '@', NULL, '$2y$10$Ih59MDUaekqKYuZtDpFYiuAOKAKZaWRmk1ypl3ihj5VpKtDpaXBa.', NULL, '2023-06-07 17:54:03', '2023-06-07 17:54:03', 1),
(21, 'FELIPE DE SOUSA LIMA', '4545754731', 'student', '@', NULL, '$2y$10$rvH90bGNG.Cenerm3Xv4Ruxk5VsOBDwUbaXeo7cSiA1kebIkyYhGO', NULL, '2023-06-07 17:54:03', '2023-06-07 17:54:03', 1),
(22, 'GUSTAVO DOMINGOS ROCHA SCISTOWSKI', '4549481560', 'student', '@', NULL, '$2y$10$sYxcN2DMzZL9a9Oadd23xumyApYjUc7wXw9aSjncNgmUmZulGwsHS', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(23, 'HENRY VIRGINIO MELO DUTRA ALVES', '1000904773', 'student', '@', NULL, '$2y$10$pBaeBUmwJyX5GUUyIs5I0eMrKWSELt1vxxj8GSVFA4rSQhdVemp/G', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(24, 'IGOR RENAN DE MATTOS', '4540964800', 'student', '@', NULL, '$2y$10$J3ggEIIvZyxwSlUetOQmN.WKQrq7Dt1d4xaCHISN0yO4I2u6XWIiu', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(25, 'INDIANARA BORGES MEDEIROS', '4547010519', 'student', '@', NULL, '$2y$10$pgfQ9GlblAB25ReuKg7bruS.HLrbajC.cYF7r/QSUa.i4fQlg9izm', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(26, 'JAQUELINE HOBUS', '4547011922', 'student', '@', NULL, '$2y$10$P7V8LQ4qdCtsHvGjiSrq8.PCk.uQc4.5.k8OnevEU1.VEiqqn8AF.', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(27, 'JENIFFER KAMILY SOARES DE SOUZA', '4548843336', 'student', '@', NULL, '$2y$10$P8zqUn8FYqbQ2P3v7XNfhOXfMPTfI6qTitALZ083aQdOhwKbg1t9.', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(28, 'JULIA EDUARDA MÄNNCHEN', '4547015790', 'student', '@', NULL, '$2y$10$3pQf2nxKHKPY5YgABwLRF.0DdJHuFXKNfcY69uOCN0HZV4IFuRPPy', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(29, 'KAUANE DA SILVA', '4500515290', 'student', '@', NULL, '$2y$10$2ZRTPJ.t4n.kIvDKcsOHpeLw/5FV0nKiyu2W1qK2oaRzldsLpd7IW', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(30, 'LARISSA KRUGER ARRUDA DE OLIVEIRA', '4547018498', 'student', '@', NULL, '$2y$10$uLGdOQAKaObHaYYM62egYejx5FQmxbqtOecin/i3KSjoRfwTsVdkO', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(31, 'LETÍCIA RAMOS DE JESUS', '4551672660', 'student', '@', NULL, '$2y$10$tOhb9qQPYiGaHBAHARIwA.a1ljz7C/Vdy66eH9miDQQvwx/JwlXIC', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(32, 'LETÍCIA TEIXEIRA', '4501679475', 'student', '@', NULL, '$2y$10$RgWLAd60T3PpcPZtorUyC.3j/Bz1ltZrJLUUsB9cpivBgu.H4eEua', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(33, 'LUCAS PAGNONCELLI', '4540656189', 'student', '@', NULL, '$2y$10$MR76RfFH1vFeMqL/csj/pOdfRGjWdj/he9ZwqZT/Nr3U.7Eaolqgy', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(34, 'LUIZA EDUARDA DE ANDRADE', '4542533203', 'student', '@', NULL, '$2y$10$9c5N54RxPEVoO/9V.Mqn5OaZB8Nvn/CgZGLS.Mru6cZbz.DaQTk9G', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(35, 'MARIA ISADORA ANDRADE STEIN', '4547036518', 'student', '@', NULL, '$2y$10$bD71ucy0/AE8eOg/WSQlYOmPxASlXmdRWpYdKmFv7vSeEcDqLT9k2', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(36, 'MARIA LUIZA DO PINHO', '4541267151', 'student', '@', NULL, '$2y$10$Vtp0s0kBL/HWAgJpLTygvulXaB1uPA6icxdesy9YMLSqeiF05VOEO', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(37, 'PABLO GUSTAVO DE SOUZA', '4548843301', 'student', '@', NULL, '$2y$10$O2a.MhH3pv5ZPzNppR0n4ec6HKQnE/UfMuDX2qiELt8vUuzuOUOOW', NULL, '2023-06-07 17:54:04', '2023-06-07 17:54:04', 1),
(38, 'PAULO ANTÔNIO MORETTO BAUMANN', '4547037476', 'student', '@', NULL, '$2y$10$/hf2zU2w5/FpJ1wlOnARfO7/Hn3bGo5U.25tyxPQHB5nkPCDWXR4S', NULL, '2023-06-07 17:54:05', '2023-06-07 17:54:05', 1),
(39, 'RYAN VIRGINIO MELO DUTRA ALVES', '1000904757', 'student', '@', NULL, '$2y$10$9K3JXIsU0eb5g1TQKSe5wegZczPiXaalGsMAhaWf0JTMZmwm8l6pS', NULL, '2023-06-07 17:54:05', '2023-06-07 17:54:05', 1),
(40, 'SANDRA SUELEN VON ZESCHAU', '4544197367', 'student', '@', NULL, '$2y$10$MhnxgJZhfl2DYfFXQCIx5O/A4nXFC9QmWnZYEQyti8dcmaKtXHCmm', NULL, '2023-06-07 17:54:05', '2023-06-07 17:54:05', 1),
(41, 'VINICIUS ALEXANDRE SCHROEDER', '4547046467', 'student', '@', NULL, '$2y$10$DPojP8VaDcCkVlg69nxfCu.Yj9R62oShfGlaZ0f.5QGyWvy54bOva', NULL, '2023-06-07 17:54:05', '2023-06-07 17:54:05', 1),
(42, 'VINICIUS LUCAS CHAPPO', '4501676948', 'student', '@', NULL, '$2y$10$a0s0Fp9NFhkfdD3K/bBcvusBNv6MwpNzpxPeG6Q5B5UjNXqcy8i42', NULL, '2023-06-07 17:54:05', '2023-06-07 17:54:05', 1),
(43, 'Rui Barbosa', 'ruibarbosa', 'teacher', 'rui@rui', NULL, '$2y$10$igqlz3lahDwv.cBvX7nxr.H2fKHlHsL5aw4La01rRqcEcaFDhdQ6u', NULL, '2023-06-09 01:02:38', '2023-06-09 01:02:38', 1),
(44, 'João Zito', 'joaozinho', 'student', 'j@j', NULL, '$2y$10$ZGJ0YvcQF/6SV/uhWQZpBuFZVv2oDFmAh4g1aH1FO2d9IRvZZR7mO', NULL, '2023-06-09 01:45:57', '2023-06-09 01:47:13', 1),
(78, 'NOVO ALUNO', '9999999', 'student', '@', NULL, '$2y$10$vlF6ZjTqVlzTcbkvI271N.Asss4qt.d6ds7ncoCyeJULCn.59F9NS', NULL, '2023-07-13 14:36:57', '2023-07-13 14:36:57', 1),
(79, '', '', 'student', '@', NULL, '$2y$10$OYanmwyk18DsKkErM32rs.6G1EqYo8Yea3i2NzjjkW6JbwM/UYV4a', NULL, '2023-07-13 14:36:57', '2023-07-13 14:36:57', 1),
(109, 'ALUNO SEM TURMA', '888', 'student', 'A@A.COM', NULL, '$2y$10$TsNAFWcfXRK0X3AOG.PgheIQg2G3f6fhZo2xvO.c1NWKPTJcx.TAK', NULL, '2023-07-13 14:40:09', '2023-07-13 14:40:09', 1),
(113, 'SUPER NOVO ALUNO', '7777', 'student', '@', NULL, '$2y$10$53L17yr2WIpTpT.Bx4lZxOPJP1c86uSS74SlvKStifaiLIGaZwSvu', NULL, '2023-07-13 14:43:09', '2023-07-13 14:43:09', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_content_id_foreign` (`content_id`),
  ADD KEY `activities_professor_id_foreign` (`professor_id`);

--
-- Índices de tabela `alunos_turmas`
--
ALTER TABLE `alunos_turmas`
  ADD KEY `alunos_turmas_aluno_id_foreign` (`aluno_id`),
  ADD KEY `alunos_turmas_turma_id_foreign` (`turma_id`);

--
-- Índices de tabela `anos_letivos`
--
ALTER TABLE `anos_letivos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anos_letivos_school_id_foreign` (`school_id`);

--
-- Índices de tabela `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contents_user_id_foreign` (`user_id`),
  ADD KEY `contents_disciplina_e_turma_idx` (`disciplina_id`,`turma_id`);

--
-- Índices de tabela `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disciplinas_school_id_foreign` (`school_id`);

--
-- Índices de tabela `disciplinas_turmas_modelos`
--
ALTER TABLE `disciplinas_turmas_modelos`
  ADD UNIQUE KEY `disciplinas_turmas_unique` (`disciplina_id`,`turma_modelo_id`),
  ADD KEY `disciplinas_turmas_modelos_disciplina_id_foreign` (`disciplina_id`),
  ADD KEY `disciplinas_turmas_modelos_turma_modelo_id_foreign` (`turma_modelo_id`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_resets_email_index` (`email`);

--
-- Índices de tabela `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_activity_id_foreign` (`activity_id`);

--
-- Índices de tabela `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_prof_atual_id` (`prof_atual_id`);

--
-- Índices de tabela `student_access_activities`
--
ALTER TABLE `student_access_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_access_activities_activity_id_foreign` (`activity_id`),
  ADD KEY `student_access_activities_user_id_foreign` (`user_id`);

--
-- Índices de tabela `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_answers_question_id_foreign` (`question_id`),
  ADD KEY `student_answers_user_id_foreign` (`user_id`),
  ADD KEY `student_answers_activity_id_foreign` (`activity_id`);

--
-- Índices de tabela `student_grades`
--
ALTER TABLE `student_grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_grades_activity_id_foreign` (`activity_id`),
  ADD KEY `student_grades_user_id_foreign` (`user_id`);

--
-- Índices de tabela `student_time_activities`
--
ALTER TABLE `student_time_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_time_activities_activity_id_foreign` (`activity_id`),
  ADD KEY `student_time_activities_user_id_foreign` (`user_id`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turmas_turma_modelo_id_foreign` (`turma_modelo_id`),
  ADD KEY `turmas_ano_id_foreign` (`ano_id`),
  ADD KEY `turmas_school_id_foreign` (`school_id`);

--
-- Índices de tabela `turmas_disciplinas`
--
ALTER TABLE `turmas_disciplinas`
  ADD KEY `turmas_disciplinas_turma_id_foreign` (`turma_id`),
  ADD KEY `turmas_disciplinas_disciplina_id_foreign` (`disciplina_id`),
  ADD KEY `turmas_disciplinas_professor_id_foreign` (`professor_id`);

--
-- Índices de tabela `turmas_modelos`
--
ALTER TABLE `turmas_modelos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turmas_modelos_school_id_foreign` (`school_id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_school_id_foreign` (`school_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `anos_letivos`
--
ALTER TABLE `anos_letivos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `contents`
--
ALTER TABLE `contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `schools`
--
ALTER TABLE `schools`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `student_access_activities`
--
ALTER TABLE `student_access_activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `student_grades`
--
ALTER TABLE `student_grades`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `student_time_activities`
--
ALTER TABLE `student_time_activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `turmas_modelos`
--
ALTER TABLE `turmas_modelos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_professor_id_foreign` FOREIGN KEY (`professor_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `alunos_turmas`
--
ALTER TABLE `alunos_turmas`
  ADD CONSTRAINT `alunos_turmas_aluno_id_foreign` FOREIGN KEY (`aluno_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alunos_turmas_turma_id_foreign` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `anos_letivos`
--
ALTER TABLE `anos_letivos`
  ADD CONSTRAINT `anos_letivos_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Restrições para tabelas `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `contents_disciplina_e_turma_foreign` FOREIGN KEY (`disciplina_id`,`turma_id`) REFERENCES `disciplinas_turmas_modelos` (`disciplina_id`, `turma_modelo_id`),
  ADD CONSTRAINT `contents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD CONSTRAINT `disciplinas_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Restrições para tabelas `disciplinas_turmas_modelos`
--
ALTER TABLE `disciplinas_turmas_modelos`
  ADD CONSTRAINT `disciplinas_turmas_modelos_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`),
  ADD CONSTRAINT `disciplinas_turmas_modelos_turma_modelo_id_foreign` FOREIGN KEY (`turma_modelo_id`) REFERENCES `turmas_modelos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `schools`
--
ALTER TABLE `schools`
  ADD CONSTRAINT `fk_prof_atual_id` FOREIGN KEY (`prof_atual_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Restrições para tabelas `student_access_activities`
--
ALTER TABLE `student_access_activities`
  ADD CONSTRAINT `student_access_activities_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`),
  ADD CONSTRAINT `student_access_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `student_answers_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `student_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `student_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `student_grades`
--
ALTER TABLE `student_grades`
  ADD CONSTRAINT `student_grades_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_grades_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `student_time_activities`
--
ALTER TABLE `student_time_activities`
  ADD CONSTRAINT `student_time_activities_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_time_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `turmas`
--
ALTER TABLE `turmas`
  ADD CONSTRAINT `turmas_ano_id_foreign` FOREIGN KEY (`ano_id`) REFERENCES `anos_letivos` (`id`),
  ADD CONSTRAINT `turmas_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`),
  ADD CONSTRAINT `turmas_turma_modelo_id_foreign` FOREIGN KEY (`turma_modelo_id`) REFERENCES `turmas_modelos` (`id`);

--
-- Restrições para tabelas `turmas_disciplinas`
--
ALTER TABLE `turmas_disciplinas`
  ADD CONSTRAINT `turmas_disciplinas_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `turmas_disciplinas_professor_id_foreign` FOREIGN KEY (`professor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `turmas_disciplinas_turma_id_foreign` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `turmas_modelos`
--
ALTER TABLE `turmas_modelos`
  ADD CONSTRAINT `turmas_modelos_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Restrições para tabelas `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
