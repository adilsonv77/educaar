

CREATE TABLE `murais` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(300) NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `disciplina_id` bigint UNSIGNED DEFAULT NULL,
  `start_painel_id` int DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  `canvasTop` double NOT NULL DEFAULT '448',
  `canvasLeft` double NOT NULL DEFAULT '588',
  `scale` double NOT NULL DEFAULT '0.7',
  `centroTop` varchar(255) NOT NULL DEFAULT '39984px',
  `centroLeft` varchar(255) NOT NULL DEFAULT '39984px'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


ALTER TABLE `murais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mural_author_id_foreign` (`author_id`),
  ADD KEY `mural_disciplina_id_foreign` (`disciplina_id`),
  ADD KEY `mural_start_painel_id_foreign` (`start_painel_id`);


ALTER TABLE `murais`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `murais`
  ADD CONSTRAINT `mural_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mural_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
  
COMMIT;


