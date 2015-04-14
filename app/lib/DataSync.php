<?php

/**
 * Name: DataSync
 * Description: Actualiza la información de las tablas de importaciones y exportaciones
 *
 * @author Julian Hernández R.
 **/
 
include "../../public/lib/config.php";

ini_set('display_errors', true);
error_reporting(-1);

require PATH_APP.'../vendor/autoload.php';
//require PATH_APP.'lib/connection/Connection.php';
require_once PATH_MODELS.'Repositories/AcuerdoRepo.php';

class DataSync extends Connection {

    private $FtpHost;
    private $FtpPort;
    private $FtpUsername;
    private $FtpPassword;

    public function __construct() {

        parent::__construct('min_agricultura');

        $this->setFtpConnection();
    }

    private function setFtpConnection()
    {
        $linesConfig   = file_get_contents(PATH_APP.'lib/ftpConfig.php');
        $ftpConnection = unserialize(base64_decode(str_rot13($linesConfig)));

        $this->FtpHost     = $ftpConnection->ftpHost;
        $this->FtpPort     = $ftpConnection->ftpPort;
        $this->FtpUsername = $ftpConnection->ftpUsername;
        $this->FtpPassword = $ftpConnection->ftpPassword;
    }

    public function Syncronize($table) {

        $this->Log("Inicia proceso para actualizar datos en la tabla " . $table);
        $ok = $this->IsNullOrEmptyString($table);
        if ($ok === TRUE) {
            $this->Log("ERROR. No se ha proporcionado el nombre de la tabla a actualizar");
            $this->Log("====================================================================================");
            $this->Log("====================================================================================");
            return;
        }

        $this->Log("Tratando de establecer conexión con el servidor FTP " . $this->FtpHost);
        $ftpSession = ftp_connect($this->FtpHost, $this->FtpPort);
        $ok = ftp_login($ftpSession, $this->FtpUsername, $this->FtpPassword);
        if ($ok != TRUE) {
            $this->Log("ERROR. No se ha podido establecer conexión con el servidor FTP");
            $this->Log("====================================================================================");
            $this->Log("====================================================================================");
            return;
        }

        $this->Log("Comprueba si existe un archivo disponible");
        $ftpFileName = $table . ".zip";
        $ok = (ftp_size($ftpSession, $ftpFileName) != -1);
        if ($ok != TRUE) {
            $this->Log("No hay archivo disponible");
            $this->Log("Cerrando conexión con el servidor FTP");
            ftp_close($ftpSession);
            $this->Log("====================================================================================");
            $this->Log("====================================================================================");
            return;
        }

        $localFileName = tempnam(sys_get_temp_dir(), $table);
        $arrayTemps = array($localFileName);

        $this->Log("Inicia descarga de archivo " . $ftpFileName);
        $ok = ftp_get($ftpSession, $localFileName, $ftpFileName, FTP_BINARY);
        if ($ok != TRUE) {
            $this->Log("Cerrando conexión con el servidor FTP");
            ftp_close($ftpSession);
            
            $this->Log("ERROR. Falló la descarga del archivo");
            $this->DeleteTemps($arrayTemps);
            $this->Log("====================================================================================");
            $this->Log("====================================================================================");
            return;
        }
        
        $this->Log("Cerrando conexión con el servidor FTP");
        ftp_close($ftpSession);


        $this->Log("Preparando carpeta temporal");
        $localFolderName = $localFileName . "FOLDER";
        array_push($arrayTemps, $localFolderName);
        $ok = mkdir($localFolderName, 0777);
        if ($ok != TRUE) {
            $this->Log("ERROR. Ha ocurrido un error inesperado");
            $this->DeleteTemps($arrayTemps);
            $this->Log("====================================================================================");
            $this->Log("====================================================================================");
            return;
        }

        $this->Log("Descomprimiendo datos");
        $ok = $this->ExtactData($localFileName, $localFolderName);
        if ($ok != TRUE) {
            $this->Log("ERROR. Fallo durante la descompresión de datos");
            $this->DeleteTemps($arrayTemps);
            $this->Log("====================================================================================");
            $this->Log("====================================================================================");
            return;
        }

        $this->Log("Inicia carga de datos nuevos");
        $files = $this->dirToArray($localFolderName);
        foreach ($files as $file) {
            $ok = $this->LoadData($table, $file, $localFolderName);
            if ($ok != TRUE) {
                $this->Log("ERROR. Fallo durante carga de datos nuevos");
                $this->DeleteTemps($arrayTemps);
                $this->Log("====================================================================================");
                $this->Log("====================================================================================");
                return;
            }
        }

        $this->Log("Elimina archivos y carpetas temporales");
        $this->DeleteTemps($arrayTemps);

        
        $this->Log("Tratando de establecer conexión con el servidor FTP " . $this->FtpHost);
        $ftpSession = ftp_connect($this->FtpHost, $this->FtpPort);
        $ok = ftp_login($ftpSession, $this->FtpUsername, $this->FtpPassword);
        if ($ok != TRUE) {
            $this->Log("ERROR. No se ha podido establecer conexión con el servidor FTP");
            $this->Log("====================================================================================");
            $this->Log("====================================================================================");
            return;
        }
        
        $this->Log("Renombrando archivo el servidor FTP");
        $ok = ftp_rename($ftpSession, $ftpFileName, $ftpFileName."_".date("YmdHis"));
        if ($ok != TRUE) {
            $this->Log("ERROR. No se ha podido renombrar el archivo en el servidor FTP");
            $this->Log("====================================================================================");
            $this->Log("====================================================================================");
            return;
        }

        $this->Log("Cerrando conexión con el servidor FTP");
        $ok = ftp_close($ftpSession);

        $this->Log("Finaliza proceso de actualización para la tabla ". $table);
        

        //una vez cargada la informacion nueva, dispara las alertas para los contingentes
        //$this->Log("Inicia proceso de alertas para los contingentes de ". $table);
        //$result = $this->acuerdo_detRepo->gridDetailed($postParams);



        $this->Log("====================================================================================");
        $this->Log("====================================================================================");
        return;
    }

    protected function ExtactData($zipFileName, $extractTo) {

        $zip = new ZipArchive;
        $ok = $zip->open($zipFileName);
        if ($ok != TRUE) {
            return $ok;
        }
        $ok = $zip->extractTo($extractTo);
        if ($ok != TRUE) {
            return $ok;
        }
        $ok = $zip->close();
        if ($ok != TRUE) {
            return $ok;
        }
        return TRUE;
        
    }

    protected function LoadData($table, $file, $tempFolder) {

        //En caso de que el archivo tenga una codificación distinta a UTF-8 es necesario realizar la transformación del archivo
        $tempfile = tempnam($tempFolder, $file);
        for ($i = -1; $i < filesize($file); $i += 2097152) {
            $cont = file_get_contents($file, FALSE, NULL, $i, 2097152);
            $cont = utf8_encode($cont);
            $cont = preg_replace("/[^a-zA-Z0-9_\n\r\.\,\|, -]/", "", $cont);
            //
            $arch = fopen($tempfile, "a+");
            fwrite($arch, $cont);
            fclose($arch);
        }
        $tempfile = str_replace(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, $tempfile);

        $conn = $this->getConnection();
        $conn->debug = true;
        $conn->BeginTrans();
        
        $sql   = [];
        $sql[] = "DROP TEMPORARY TABLE IF EXISTS ".$table."_TEMP;";
        $sql[] = "CREATE TEMPORARY TABLE ".$table."_TEMP LIKE ".$table.";";
        $sql[] = "
            LOAD DATA LOCAL INFILE '".$tempfile."'
            INTO TABLE ".$table."_TEMP
            CHARACTER SET utf8
            FIELDS TERMINATED BY '|'
            OPTIONALLY ENCLOSED BY '\"'
            LINES TERMINATED BY '\n' STARTING BY '';
        ";
        
        if ($table == "declaraimp" or $table == "declaraexp") {
            $sql[] = "
                DELETE tbl FROM ".$table." tbl
                INNER JOIN (
                    select sub.anio, sub.periodo 
                    from ".$table."_TEMP sub 
                    group by sub.anio, sub.periodo
                ) as tmp on tmp.anio = tbl.anio and tmp.periodo = tbl.periodo;
            ";

            $columns = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`=(SELECT DATABASE()) AND `TABLE_NAME`='".$table."';";
            $rs = $conn->Execute($columns);
            $columns = "";
            foreach ($rs->GetRows() as $row) {
                if ($row["COLUMN_NAME"] != "id") {
                    $columns = $columns . "`" . $row["COLUMN_NAME"] . "`,";
                }
            }
            $columns = substr($columns, 0, strlen($columns) - 1);
            
            $sql[] = "INSERT INTO ".$table." (".$columns.") SELECT ".$columns." FROM ".$table."_TEMP;";
            
            $trade = ($table == "declaraimp") ? "impo" : "expo" ;
            $sql[] = "
                UPDATE update_info 
                SET update_info_to = ( 
                    SELECT MAX( LAST_DAY(CONCAT(tab.anio,'-',tab.periodo,'-01')) ) FROM ".$table." tab 
                ) 
                WHERE update_info_product = 'aduanas' 
                  AND update_info_trade = '".$trade."';
            ";
        }
        else {
            $sql[] = "
                DELETE tbl FROM ".$table." tbl;
            ";
            if ($table == "subpartida") {
                $sql[] = "INSERT INTO ".$table." SELECT id_subpartida, subpartida, SUBSTR(id_subpartida,1,2), SUBSTR(id_subpartida,1,4) FROM ".$table."_TEMP;";
            } elseif ($table == "posicion") {
                $sql[] = "INSERT INTO ".$table." SELECT id_posicion, posicion, SUBSTR(id_posicion,1,2), SUBSTR(id_posicion,1,4), SUBSTR(id_posicion,1,6) FROM ".$table."_TEMP;";
            } else {
                $sql[] = "INSERT INTO ".$table." SELECT * FROM ".$table."_TEMP;";
            }
        }
        
        $sql[] = "DROP TEMPORARY TABLE IF EXISTS ".$table."_TEMP;";
            
        $ok = TRUE;
        foreach ($sql as $comm) {
            if ($ok) {
                $ok = $conn->Execute($comm);
            }
        }

        if ($ok) {
            $conn->CommitTrans();
        } else {
            $conn->RollbackTrans();
        }
        
        return $ok;
    }
    
    protected function Log($message) {

        $filename = PATH_REPORTS."update_info_log.txt";
        $fp = fopen($filename, "a+");

        chmod($filename, 0777);
        $text = date("Y-m-d H:i:s")." - ". $message."\xA";
        if($fp) {
            fwrite($fp, $text, strlen($text));
            fclose($fp);
        }

        //echo date("Y-m-d H:i:s")." -> ".$message."\xA";
    }

    // Function for basic field validation (present and neither empty nor only white space
    protected function IsNullOrEmptyString($question) {
        return (!isset($question) || trim($question) === "");
    }

    protected function DeleteTemps($arrayTemps) {
        foreach ($arrayTemps as $temp) {
            if (is_dir($temp)) {
                $this->rmdir_recursive($temp);
            } else if (file_exists($temp)) {
                unlink($temp);
            }
        }
    }

    protected function rmdir_recursive($dir) {
        foreach (scandir($dir) as $file) {
            if ("." === $file || ".." === $file) {
                continue;
            }
            if (is_dir("$dir/$file")) {
                $this->rmdir_recursive("$dir/$file");
            } else if (file_exists("$dir/$file")) {
                unlink("$dir/$file");
            }
        }
        rmdir($dir);
    }

    protected function dirToArray($dir) {

        $result = array();

        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    $result[] = $dir . DIRECTORY_SEPARATOR . $value;
                }
            }
        }

        return $result;
    }

}

$dataSync = new DataSync();
$dataSync->Syncronize("arancel");
$dataSync->Syncronize("posicion");
$dataSync->Syncronize("subpartida");
$dataSync->Syncronize("empresa");
$dataSync->Syncronize("departamento");
$dataSync->Syncronize("pais");
$dataSync->Syncronize("declaraexp");
$dataSync->Syncronize("declaraimp");
