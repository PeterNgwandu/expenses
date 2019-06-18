<?php

namespace App\Http\Controllers\Backup;

use Alert;
use Artisan;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function databaseBackup()
    {
        $result = Artisan::call("backup:run", ['--only-db' => 1]);
        alert()->success("Database backup created successfuly", "Good Job");
        return response()->json(['result' => $result]);        
    }

    public function downloadBackup()
    {
        $backupFile = Storage::download('backup-temp/temp/db-dumps/mysql-expenses.sql');
        if($backupFile)
        {
            return $backupFile;
        }else{
            alert()->error("No backup file exists");
        }
    }
}
