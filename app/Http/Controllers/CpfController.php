<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CpfService;

class CpfController extends Controller
{
    protected $cpfService;

    public function __construct(CpfService $cpfService)
    {
        $this->cpfService = $cpfService;
    }

    public function processCsv(Request $request)
    {
        $path = storage_path('app/public/cpfs-teste.csv');
        $this->cpfService->processCsv($path);
        return response()->json(['message' => 'Process initiated'], 200);
    }
}
