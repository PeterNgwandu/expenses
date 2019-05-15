<?php

namespace App\Http\Controllers\Backup;

use Alert;
use Artisan;
use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BackupController extends Controller
{
    public function databaseBackup()
    {
        Artisan::call("backup:run", ['--only-db' => 1]);
        alert()->success("Database backup created successfuly", "Good Job");
        return redirect()->back();        
    }

    public function downloadBackup()
    {
        $file= public_path(). "/storage/mysql-expenses.sql";

        // $headers = array(
        //         'Content-Type: application/pdf',
        //         );

        return Response::download($file, 'mysql-expenses.sql');
    }
}
