<?php

/**
 * Name: DataSync
 * Description: Actualiza la información de las tablas de importaciones y exportaciones
 *
 * @author Julian Hernández R.
 **/

include "../../public/lib/config.php";
require '../../vendor/autoload.php';
require PATH_APP.'lib/connection/Connection.php';

use stringEncode\Encode;

class DataSync extends Connection {

    private $FtpHost;
    private $FtpPort;
    private $FtpUsername;
    private $FtpPassword;

    function __construct($ftpHost, $ftpPort, $ftpUsername, $ftpPassword) {

        parent::__construct('min_agricultura');

        $this->FtpHost = $ftpHost;
        $this->FtpPort = $ftpPort;
        $this->FtpUsername = $ftpUsername;
        $this->FtpPassword = $ftpPassword;

    }

    public function Syncronize($table) {

        echo "Inicia proceso para actualizar datos en la tabla " . $table . "\xA";
        $flag = $this->IsNullOrEmptyString($table);
        if ($flag === TRUE) {
            echo "No se ha proporcionado el nombre de la tabla a actualizar" . "\xA";
            return;
        }

        echo "Tratando de establecer conexión con el servidor FTP " . $this->FtpHost . "\xA";
        $ftpSession = ftp_connect($this->FtpHost, $this->FtpPort);

        $flag = ftp_login($ftpSession, $this->FtpUsername, $this->FtpPassword);
        if ($flag != TRUE) {
            echo "No se ha podido establecer conexión con el servidor FTP" . "\xA";
            return;
        }

        $ftpFileName = $table . ".zip";
        $localFileName = $this->TempFileName = tempnam(sys_get_temp_dir(), $table);
        $arrayTemps = array($localFileName);

        echo "Inicia descarga de archivo " . $ftpFileName . "\xA";
        $flag = ftp_get($ftpSession, $localFileName, $ftpFileName, FTP_BINARY);
        if ($flag != TRUE) {
            echo "Falló la descarga del archivo" . "\xA";
            $this->DeleteTemps($arrayTemps);
            return;
        }

        $localFolderName = $localFileName . "FOLDER";
        array_push($arrayTemps, $localFolderName);
        $flag = mkdir($localFolderName, 0777);
        if ($flag != TRUE) {
            $this->DeleteTemps($arrayTemps);
            return;
        }

        echo "Descomprimiendo datos" . "\xA";
        $flag = $this->ExtactData($localFileName, $localFolderName);
        if ($flag != TRUE) {
            echo "Fallo durante la descompresión de datos" . "\xA";
            $this->DeleteTemps($arrayTemps);
            return;
        }
        echo "Bien" . "\xA";

        $files = $this->dirToArray($localFolderName);
        foreach ($files as $file) {
            $this->LoadData($table, $file, $localFolderName);
        }

        $this->DeleteTemps($arrayTemps);

        echo "Cerrando conexión con el servidor FTP" . "\xA";
        $flag = ftp_close($ftpSession);
        if ($flag != TRUE) {
            return;
        }
    }

    protected function ExtactData($zipFileName, $extractTo) {
        $zip = new ZipArchive;

        $flag = $zip->open($zipFileName);
        if ($flag != TRUE) {
            return FALSE;
        }

        $flag = $zip->extractTo($extractTo);
        if ($flag != TRUE) {
            return FALSE;
        }

        $flag = $zip->close();
        if ($flag != TRUE) {
            return FALSE;
        }

        return TRUE;
    }

    protected function LoadData($table, $file, $tempFolder) {

        /*$tempfile = tempnam($tempFolder, $file);
        $fp = fopen($tempfile, "r");

        while(!feof($fp)) {
            $line = fgets($fp);

            $encode = new Encode;
            $encode->detect($line);
            $newstr = $encode->convert($line);
            $newstr = preg_replace('/[^a-zA-Z0-9_\.\,\|-]/', '', $newstr);
            

            $arch = fopen($tempfile, "a+");
            fwrite($arch, $cont);
            fclose($arch);
        }
        fclose($fp);*/

        $tempfile = tempnam($tempFolder, $file);
        for ($i = -1; $i < filesize($file); $i += 1048576) {
            $cont = file_get_contents($file, FALSE, NULL, $i, 1048576);
            $cont = utf8_encode($cont);
            $cont = preg_replace('/[^a-zA-Z0-9_\n\r\.\,\|-]/', '', $cont);

            $arch = fopen($tempfile, "a+");
            fwrite($arch, $cont);
            fclose($arch);
        }

        $tempfile = str_replace(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, $tempfile);
        /*$sqlString = file_get_contents("./../../sql/load data template.sql");
        $sqlString = str_replace("@<table>@", $table, $sqlString);
        $sqlString = str_replace("@<file>@", $tempfile, $sqlString);

        $sqlFile = fopen($file . ".sql", "w");
        fwrite($sqlFile, $sqlString);
        fclose($sqlFile);*/

        $this->MySqlExecuteScript($table, $tempfile);

        return TRUE;
    }

    protected function MySqlExecuteScript($table, $tempfile) {

        $conn = $this->getConnection();

        $conn->debug = true;

        $conn->BeginTrans();

        $sql = "
            DROP TEMPORARY TABLE IF EXISTS ".$table."_TEMP;
        ";
        
        $ok = $conn->Execute($sql);

        if ($ok) {

            $sql = "
                CREATE TEMPORARY TABLE ".$table."_TEMP LIKE ".$table.";
            ";
            
            $ok = $conn->Execute($sql);

            if ($ok) {
                $sql = "
                    LOAD DATA LOCAL INFILE '".$tempfile."'
                    INTO TABLE ".$table."_TEMP
                    CHARACTER SET utf8
                    FIELDS TERMINATED BY '|'
                    OPTIONALLY ENCLOSED BY '\"'
                    LINES TERMINATED BY '\n' STARTING BY '';
                ";
                $ok = $conn->Execute($sql);

                if ($ok) {
                    $sql = "
                        DELETE tbl FROM ".$table." tbl
                        INNER JOIN ".$table."_TEMP inn on inn.id = tbl.id;
                    ";
                    $ok = $conn->Execute($sql);

                    if ($ok) {
                        $sql = "
                            INSERT INTO ".$table."_load SELECT * FROM ".$table."_TEMP;
                        ";
                        $ok = $conn->Execute($sql);

                        if ($ok) {
                            $sql = "
                                DROP TEMPORARY TABLE IF EXISTS ".$table."_TEMP;
                            ";
                            $ok = $conn->Execute($sql);

                        }
                    }

                }

            }
        }

        if ($ok) {
            $conn->CommitTrans();
        } else {
            $conn->RollbackTrans();
        }

        /*echo "<pre>$sql</pre>";

        
        $conn->debug = true;
        $resultSet = $conn->Execute($sql);

        var_dump($resultSet);

        $mysqli = mysqli_connect($this->host, $this->username, $this->password, $this->database);

         comprobar la conexión 
        if ($mysqli->connect_errno) {
            echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        echo 'processing file <br />';
        echo $sql;
        if (!$mysqli->multi_query($sql)) {
            echo "Falló la multiconsulta: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        echo 'done.';
        $mysqli->close();*/
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

    // Function for basic field validation (present and neither empty nor only white space
    protected function IsNullOrEmptyString($question) {
        return (!isset($question) || trim($question) === '');
    }

    protected function rmdir_recursive($dir) {
        foreach (scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) {
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

$dataSync = new DataSync("190.60.211.98", 21, "fs1atam4", "fsagr1");
$dataSync->Syncronize("declaraexp");
//$dataSync->Syncronize("declaraimp");
