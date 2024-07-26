<?php

namespace App\Jobs;

use App\Models\Cpf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCpf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cpfs;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $cpfs)
    {
        $this->cpfs = $cpfs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            foreach ($this->cpfs as &$cpf) {
                $isValid = $this->isValidCpf($cpf['cpf']);
                $cpf['valid'] = $isValid ? 'Valido' : 'Invalido';
                $cpf['created_at'] = now();
                $cpf['updated_at'] = now();
            }

            DB::table('cpfs')->insertOrIgnore($this->cpfs);

            Log::info('Processed ' . count($this->cpfs) . ' CPFs');

            unset($this->cpfs);
            gc_collect_cycles();
        } catch (\Exception $e) {
            Log::error('Error processing CPFs: ' . $e->getMessage());
            throw $e;
        }
    }

    private function isValidCpf(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
