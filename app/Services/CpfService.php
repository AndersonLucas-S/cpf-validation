<?php

namespace App\Services;

use App\Jobs\ProcessCpf;

class CpfService
{
    protected $batchSize = 5000; // Ajuste o tamanho do lote conforme necessário

    public function processCsv(string $path)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(3600);

        $file = fopen($path, 'r');
        $header = fgetcsv($file); // Ignora o cabeçalho
        $cpfs = [];
        $totalRecords = 0;

        while (($row = fgetcsv($file, 1000, ',')) !== FALSE) {
            $cpf = $row[0];
            $cpfs[] = [
                'cpf' => $cpf
            ];

            if (count($cpfs) >= $this->batchSize) {
                ProcessCpf::dispatch($cpfs);
                $cpfs = [];
            }

            $totalRecords++;
            if ($totalRecords >= 20000000) {
                break;
            }
        }

        if (count($cpfs) > 0) {
            ProcessCpf::dispatch($cpfs);
        }

        fclose($file);
    }
}
