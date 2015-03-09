

ALTER TABLE `min_agricultura`.`contingente_det`
  ADD COLUMN contingente_det_tipo_operacion enum('igual','aumento_porcentual','aumento_toneladas') NOT NULL DEFAULT 'igual' AFTER contingente_det_peso_neto,
  CHANGE COLUMN contingente_det_contingente_id contingente_det_contingente_id int(10) unsigned NOT NULL;

ALTER TABLE `min_agricultura`.`declaraexp`
  ADD INDEX id_capitulo (id_capitulo),
  ADD INDEX id_subpartida (id_subpartida),
  ADD INDEX id_partida (id_partida),
  CHANGE COLUMN peso_neto peso_neto decimal(13,2) unsigned NOT NULL,
  CHANGE COLUMN valorcif valorcif decimal(13,2) unsigned NOT NULL,
  CHANGE COLUMN valor_pesos valor_pesos decimal(15,2) NOT NULL,
  CHANGE COLUMN valorfob valorfob decimal(13,2) unsigned NOT NULL;

ALTER TABLE `min_agricultura`.`declaraimp`
  ADD INDEX id_capitulo (id_capitulo),
  ADD INDEX id_subpartida (id_subpartida),
  ADD INDEX id_partida (id_partida);

ALTER TABLE `min_agricultura`.`desgravacion_det`
  ADD COLUMN desgravacion_det_tipo_operacion enum('igual','reduccion_porcentual') NOT NULL DEFAULT 'igual' AFTER desgravacion_det_tasa;

