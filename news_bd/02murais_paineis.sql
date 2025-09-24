-- Estrutura para tabela `paineis`
--

CREATE TABLE `murais_paineis` (
  `id` int NOT NULL,
  `panelnome` int NOT NULL,
  `panel` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `mural_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `paineis`
--
ALTER TABLE `murais_paineis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mural_id_foreing` (`mural_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `paineis`
--
ALTER TABLE `murais_paineis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `paineis`
--
ALTER TABLE `murais_paineis`
  ADD CONSTRAINT `mural_id_foreing` FOREIGN KEY (`mural_id`) REFERENCES `murais` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `murais`
  ADD CONSTRAINT `mural_start_painel_id_foreign` FOREIGN KEY (`start_painel_id`) REFERENCES `murais_paineis` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;



