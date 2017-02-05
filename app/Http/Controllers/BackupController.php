<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Traits\S3StorageControllTraits;
use Log;

class BackupController extends Controller
{
    use S3StorageControllTraits;

    public function DatabaseBackup()
    {
        Log::info('Database Dump Succses');
        $fileName = date('Y-d-m_ahi')."_backup.sql";
        $filePath = "public/".$fileName;
        exec("mysqldump -u ".env('DB_USERNAME')." --password=".env('DB_PASSWORD')." ".env('DB_DATABASE')." > "."$filePath ");
//        $this->databaseBackupToS3($fileName);
    }

}
