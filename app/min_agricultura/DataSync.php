<?php

/**
 * Name: DataSync
 * Description: Actualiza la información de las tablas de importaciones y exportaciones
 *
 * @author Julian Hernández R.
 */
$dataSync = new DataSync("190.60.211.98", 21, "fs1atam4", "fsagr1");
$dataSync->Syncronize("declaraexp");
$dataSync->Syncronize("declaraimp");

require './../lib/config.php';

class DataSync {

    private $FtpHost;
    private $FtpPort;
    private $FtpUsername;
    private $FtpPassword;

    function __construct($ftpHost, $ftpPort, $ftpUsername, $ftpPassword) {
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
            $this->LoadData($table, $file);
        }

        //$this->DeleteTemps($arrayTemps);

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

    protected function LoadData($table, $file) {
        
        $file = str_replace(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, $file);
        $sqlString = file_get_contents("./../../sql/load data template.sql");
        $sqlString = str_replace("@<table>@", $table, $sqlString);
        $sqlString = str_replace("@<file>@", $file, $sqlString);
        
        $sqlFile = fopen($file . ".sql", "w");
        fwrite($sqlFile, $sqlString);
        fclose($sqlFile);
        
        return TRUE;
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
