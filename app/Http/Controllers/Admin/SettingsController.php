<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SettingService::all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'university_name' => 'required|string|max:255',
            'admin_contact' => 'required|email|max:255',
            'registration_status' => 'required|in:open,closed',
            'maintenance_mode' => 'required|in:active,inactive',
        ]);

        SettingService::saveMany($request->only([
            'app_name',
            'university_name',
            'admin_contact',
            'registration_status',
            'maintenance_mode'
        ]));

        return back()->with('success', 'Konfigurasi sistem berhasil diperbarui!');
    }

    public function backup()
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $dbName = DB::getDatabaseName();
            $keyName = 'Tables_in_' . $dbName;
            
            $sqlDump = "-- Database Backup: " . $dbName . "\n";
            $sqlDump .= "-- Generated: " . now() . "\n";
            $sqlDump .= "-- ------------------------------------------------------\n\n";
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$keyName;
                
                // Get table structure
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sqlDump .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sqlDump .= $createTable[0]->{'Create Table'} . ";\n\n";
                
                // Get table data
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $columns = array_map(function($col) {
                        return "`{$col}`";
                    }, array_keys($rowArray));
                    
                    $values = array_map(function($val) {
                        if (is_null($val)) return "NULL";
                        return "'" . addslashes($val) . "'";
                    }, array_values($rowArray));
                    
                    $sqlDump .= "INSERT INTO `{$tableName}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                }
                $sqlDump .= "\n";
            }
            
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            $filename = 'backup_ukm_system_' . date('Y_m_d_His') . '.sql';
            
            return response($sqlDump, 200, [
                'Content-Type' => 'application/sql',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membackup database: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file'
        ]);

        $file = $request->file('backup_file');
        
        if (!$file->isValid()) {
            return back()->with('error', 'File upload tidak valid.');
        }

        $sql = file_get_contents($file->getRealPath());

        if (!$sql) {
            return back()->with('error', 'Gagal membaca isi file backup.');
        }

        try {
            DB::unprepared($sql);
            return back()->with('success', 'Database berhasil dipulihkan dari file backup!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memulihkan database: ' . $e->getMessage());
        }
    }
}
