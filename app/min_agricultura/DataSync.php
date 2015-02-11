<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataSync
 *
 * @author Julian Hernández
 */
require './../lib/config.php';

class DataSync {

    private $FtpHost;
    private $FtpUsername;
    private $FtpPassword;

    function __construct($ftpHost, $ftpUsername, $ftpPassword) {
        $this->FtpHost = $ftpHost;
        $this->FtpUsername = $ftpUsername;
        $this->FtpPassword = $ftpPassword;
    }

    public function Syncronize($table) {

        $tempFileName = $this->TempFileName = tempnam(sys_get_temp_dir(), $table);
        print_r($tempFileName);

        $ftpSession = $this->OpenFtpSession();
        $arrayList = ftp_nlist($ftpSession->ConnectionId, "/");
        print_r($arrayList);

        if (ftp_get($ftpSession->ConnectionId, $tempFileName, $arrayList[1], FTP_BINARY) === TRUE) {
            $this->ExtactData($tempFileName);
            print_r("Bien");
        }

        $this->CloseFtpSession($ftpSession);
    }

    function OpenFtpSession() {
        $ftpSession = new stdClass();
        $ftpSession->ConnectionId = ftp_connect($this->FtpHost);
        $ftpSession->LoginResult = ftp_login($ftpSession->ConnectionId, $this->FtpUsername, $this->FtpPassword);
        return $ftpSession;
    }

    function CloseFtpSession($ftpSession) {
        ftp_close($ftpSession->ConnectionId);
    }

    function ExtactData($zipFileName) {
        $zip = new ZipArchive;
        if ($zip->open($zipFileName) === TRUE) {
            $tempFolderName = $zipFileName . "_zip";
            mkdir($tempFolderName, 0777);
            $zip->extractTo($tempFolderName);
            $zip->close();
            echo 'ok';
        } else {
            echo 'failed';
        }
    }

}

$dataSync = new DataSync("190.60.211.98", "fs1atam4", "fsagr1");
$dataSync->Syncronize("");
