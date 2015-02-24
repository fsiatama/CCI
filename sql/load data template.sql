/*DECLARE _rollback BOOL DEFAULT 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET _rollback = 1;*/
START TRANSACTION;


	DROP TEMPORARY TABLE IF EXISTS @<table>@_TEMP;
	CREATE TEMPORARY TABLE @<table>@_TEMP LIKE @<table>@;

	LOAD DATA LOCAL INFILE '@<file>@'
	INTO TABLE @<table>@_TEMP
	CHARACTER SET utf8
	FIELDS TERMINATED BY '|'
	OPTIONALLY ENCLOSED BY '"'
	LINES TERMINATED BY '\n' STARTING BY '';

	/*
	DELETE tbl FROM @<table>@ tbl
	INNER JOIN (
		SELECT anio, periodo 
		FROM @<table>@_TEMP inn
		GROUP BY anio, periodo
	) per ON per.anio = tbl.anio AND per.periodo = tbl.periodo;
	*/
	
	DELETE tbl FROM @<table>@ tbl
	INNER JOIN @<table>@_TEMP inn on inn.id = tbl.id;

	INSERT INTO @<table>@ SELECT * FROM @<table>@_TEMP;

	/*
	INSERT INTO update_info 
			(update_info_product, update_info_trade, update_info_from, update_info_to)
	 VALUES (
		'aduanas',
		'@<table>@',
		(select STR_TO_DATE(CONCAT(anio, periodo, 01), '%c/%e/%Y') from @<table>@_TEMP order by anio, periodo limit 0,1),
		(select STR_TO_DATE(CONCAT(anio, periodo, LAST_DAY(STR_TO_DATE(CONCAT(anio, periodo, 01), '%c/%e/%Y'))), '%c/%e/%Y') from @<table>@_TEMP order by anio desc, periodo desc limit 0,1)
	);
	*/

	DROP TEMPORARY TABLE IF EXISTS @<table>@_TEMP;

COMMIT;
/*IF _rollback THEN
	ROLLBACK;
ELSE
	COMMIT;
END IF;
*/