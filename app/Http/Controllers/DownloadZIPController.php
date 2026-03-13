<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DownloadZIPController extends Controller
{
    public function download(Request $request, string $chave)
    {
        $dados = Cache::get($chave);

        if (! $dados || $dados['status'] !== 'pronto') {
            abort(404);
        }

        $path = storage_path('app/temp/'.$dados['arquivo']);

        if (! file_exists($path)) {
            abort(404);
        }

        Cache::forget($chave);

        return response()->download($path, 'XMLArquivos.zip', [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }
}
