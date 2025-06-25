<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use PDO;

use mysqli;
use mysqli_result;

class BackupController extends Controller{

    public function databaseBackup() {
        $dbBackup = base_path("backup/databaseBackup.sql");

        $tables = DB::select('show tables');

        //dd($tables);
        $key = "Tables_in_" . DB::getDatabaseName();

        $databaseName = DB::getDatabaseName();

        $dbBackupContent = "";
        $dbInsert = "";
        $dbFK = "";

        $views = DB::table('information_schema.views')
            ->where('table_schema', $databaseName)
            ->pluck('TABLE_NAME')
            ->toArray();

        // Monta lista de tabelas
        $tableNames = [];
        foreach ($tables as $table) {
            $tableNames[] = $table->$key;
        }

        // Monta grafo para ordenação topológica
        $graph = [];
        foreach ($tableNames as $table) {
            $graph[$table] = [];
            $fks = DB::select("SELECT REFERENCED_TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL", [$databaseName, $table]);
            foreach ($fks as $fk) {
                $graph[$table][] = $fk->REFERENCED_TABLE_NAME;
            }
        }

        $visited = [];
        $tempMark = [];
        $sorted = [];

        // Função para ordenação topológica
        $visit = function($node) use (&$visit, &$graph, &$visited, &$tempMark, &$sorted) {
            if (isset($tempMark[$node])) {
                throw new \Exception("Ciclo detectado nas FKs envolvendo a tabela $node");
            }
            if (!isset($visited[$node])) {
                $tempMark[$node] = true;
                if (isset($graph[$node])) {
                    foreach ($graph[$node] as $neighbor) {
                        $visit($neighbor);
                    }
                }
                $visited[$node] = true;
                unset($tempMark[$node]);
                $sorted[] = $node;
            }
        };

        // Tenta ordenar, se ciclo ignoramos e usamos ordem original
        try {
            foreach ($tableNames as $table) {
                if (!isset($visited[$table])) {
                    $visit($table);
                }
            }
        } catch (\Exception $e) {
            // Ciclo detectado, ignoramos e mantemos ordem original
            $sorted = $tableNames;
        }

        // Mostrar os create table
        foreach ($sorted as $tableName) {

            $createTable = DB::select("SHOW CREATE TABLE `$tableName`");
            $createSql = array_values((array)$createTable[0])[1];
            $lines = explode("\n", $createSql);

            // Remove linhas de FK do CREATE TABLE
            $filteredLines = [];
            foreach ($lines as $line) {
                if (stripos($line, 'foreign key') === false) {
                    $filteredLines[] = $line;
                }
            }
            // Remove vírgula extra na penúltima linha se houver
            $countLines = count($filteredLines);
            if ($countLines > 1) {
                $filteredLines[$countLines - 2] = rtrim($filteredLines[$countLines - 2], ',');
            }
            $createWithoutFK = implode("\n", $filteredLines);

            $dbBackupContent .= $createWithoutFK . ";\n\n";

            // Gerar INSERT dos dados
            $insertInto = DB::table($tableName)->get();
            $rowsCount = count($insertInto);
            if ($rowsCount > 0 && !in_array($tableName, $views)) {
                $dbBackupContent .= "DELETE FROM `$tableName`;\n";
                $dbBackupContent .= "INSERT INTO `$tableName` VALUES\n";

                $currentIndex = 0;
                foreach ($insertInto as $singleRow) {
                    $values = [];
                    foreach ($singleRow as $value) {
                        $values[] = is_null($value)
                            ? "NULL"
                            : "'" . addslashes(is_array($value) ? json_encode($value) : $value) . "'";
                    }
                    $currentIndex++;
                    $dbBackupContent .= "(" . implode(", ", $values) . ")";
                    $dbBackupContent .= ($currentIndex < $rowsCount) ? ",\n" : ";\n";
                }
                $dbBackupContent .= "\n";
            }
        }

        // Agora adiciona todas as FKs juntas no final do arquivo
        foreach ($sorted as $tableName) {
            $foreignKeys = DB::select("
                SELECT 
                    kcu.CONSTRAINT_NAME as constraint_name, 
                    GROUP_CONCAT(kcu.COLUMN_NAME ORDER BY kcu.ORDINAL_POSITION) as columns,
                    kcu.REFERENCED_TABLE_NAME as referenced_table_name, 
                    GROUP_CONCAT(kcu.REFERENCED_COLUMN_NAME ORDER BY kcu.POSITION_IN_UNIQUE_CONSTRAINT) as referenced_columns,
                    rc.DELETE_RULE as delete_rule,
                    rc.UPDATE_RULE as update_rule
                FROM information_schema.KEY_COLUMN_USAGE kcu
                JOIN information_schema.REFERENTIAL_CONSTRAINTS rc 
                    ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME 
                    AND kcu.TABLE_SCHEMA = rc.CONSTRAINT_SCHEMA
                WHERE kcu.TABLE_SCHEMA = ? 
                    AND kcu.TABLE_NAME = ? 
                    AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
                GROUP BY kcu.CONSTRAINT_NAME, kcu.REFERENCED_TABLE_NAME, rc.DELETE_RULE, rc.UPDATE_RULE
            ", [$databaseName, $tableName]);


            $addConstraints = [];
            foreach ($foreignKeys as $fk) {
                $constraintName = $fk->constraint_name;
                $columns = implode('`, `', explode(',', $fk->columns));
                $refColumns = implode('`, `', explode(',', $fk->referenced_columns));
                $refTable = $fk->referenced_table_name;
                $onDelete = $fk->delete_rule;
                $onUpdate = $fk->update_rule;

                $addConstraints[] = "ADD CONSTRAINT `$constraintName` FOREIGN KEY (`$columns`) REFERENCES `$refTable` (`$refColumns`) ON DELETE $onDelete ON UPDATE $onUpdate";
            }


            if (!empty($addConstraints)) {
                $dbBackupContent .= "ALTER TABLE `$tableName`\n" . implode(",\n", $addConstraints) . ";\n\n";
            }
        }


        file_put_contents($dbBackup, $dbBackupContent);

        return $dbBackup;
    }


    public function createZip(){

        File::deleteDirectory(base_path('backup/marcadores'));
        File::deleteDirectory(base_path('backup/mind'));
        File::deleteDirectory(base_path('backup/modelos3d'));
        File::deleteDirectory(base_path('backup/midiasPainel'));
        File::delete(base_path("backup/databaseBackup.sql"));

        $this->copyFolder(public_path('marcadores'),base_path('backup/marcadores'));
        $this->copyFolder(public_path('mind'),base_path('backup/mind'));
        $this->copyFolder(public_path('modelos3d'),base_path('backup/modelos3d'));
        $this->copyFolder(public_path('midiasPainel'),base_path('backup/midiasPainel'));
        $this->databaseBackup();

        $zip = new \ZipArchive();
        $date = date("d-m-Y");

        $publicBackupFolder = public_path('backupEducaar');
        if(!File::exists($publicBackupFolder)){
            File::makeDirectory($publicBackupFolder, 0755, true);
        }
        
        $filename = public_path('backupEducaar/'.$date.'.zip');
        $basePath = base_path('backup');

        if ($zip->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $files = File::allFiles($basePath);

            foreach ($files as $file) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($basePath) + 1);

                $zip->addFile($filePath, $relativePath);
            }

            $zip->close();

        } else {
            \Log::error('Erro ao criar o ZIP.');
        }

        $backupPath = public_path('backupEducaar');
        $backupFiles = File::allFiles($backupPath);

        foreach ($backupFiles as $backupFile) {
            $fileName = $backupFile->getFilename();

            if($backupFile->getExtension() == 'zip'){
                $fileActualName = pathinfo($fileName, PATHINFO_FILENAME);

                $fileDate = strtotime($fileActualName);
                $date = strtotime(date('d-m-Y'));
                if($fileDate < $date){
                    File::delete($backupFile->getPathname());
                }
            }
        }

        $response = response()->download($filename);

        File::deleteDirectory(base_path('backup/marcadores'));
        File::deleteDirectory(base_path('backup/mind'));
        File::deleteDirectory(base_path('backup/modelos3d'));
        File::deleteDirectory(base_path('backup/midiasPainel'));
        File::delete(base_path("backup/databaseBackup.sql"));

        return $response;
    }

    public function copyFolder($source, $destination){
        if (File::exists($destination)) {
            File::deleteDirectory($destination);
        }

        File::makeDirectory($destination, 0755, true);

        $files = File::files($source);
        foreach ($files as $file) {
            File::copy($file, $destination . '/' . $file->getFilename());
        }
    }
}
