SELECT * FROM desgravacion_det WHERE `desgravacion_det_desgravacion_acuerdo_det_acuerdo_id` = 8

/*copiar arancel intra a extra*/
UPDATE desgravacion_det 
SET `desgravacion_det_tasa_extra` = `desgravacion_det_tasa_intra`
WHERE `desgravacion_det_desgravacion_acuerdo_det_acuerdo_id` = 8

/*cambiar el arancel intra a cero*/
UPDATE desgravacion_det 
SET `desgravacion_det_tasa_intra` = 0
WHERE `desgravacion_det_desgravacion_acuerdo_det_acuerdo_id` = 8