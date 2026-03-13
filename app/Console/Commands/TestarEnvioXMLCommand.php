<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class TestarEnvioXMLCommand extends Command
{
    protected $signature = 'xml:testar-envio
                            {cnpj : CNPJ da empresa (somente números)}
                            {--email= : E-mail do usuário para autenticação}
                            {--senha= : Senha do usuário para autenticação}
                            {--zip= : Caminho do ZIP (padrão: storage/app/test/xmls.zip)}
                            {--url= : URL da API (padrão: APP_URL)}
                            {--limite=0 : Limitar quantidade de XMLs enviados (0 = todos)}';

    protected $description = 'Testa o envio de XMLs via API a partir de um arquivo ZIP';

    private \Illuminate\Log\Logger $log;

    private string $token = '';

    public function handle(): int
    {
        $this->log = Log::channel('envio_xml');

        $cnpj    = preg_replace('/\D/', '', $this->argument('cnpj'));
        $zipPath = $this->option('zip') ?? storage_path('app/test/xmls.zip');
        $baseUrl = rtrim($this->option('url') ?? config('app.url'), '/');
        $limite  = (int) $this->option('limite');

        if (! file_exists($zipPath)) {
            $this->error("Arquivo ZIP não encontrado: {$zipPath}");
            $this->line("Coloque o ZIP em: <comment>storage/app/test/xmls.zip</comment>");

            return self::FAILURE;
        }

        // Autenticação
        $email = $this->option('email') ?? env('XML_TEST_EMAIL') ?? $this->ask('E-mail do usuário');
        $senha = $this->option('senha') ?? env('XML_TEST_PASSWORD') ?? $this->secret('Senha');

        $this->info('Autenticando...');
        $tokenResult = $this->autenticar($baseUrl, $email, $senha);

        if (! $tokenResult['ok']) {
            $this->error("Falha na autenticação: {$tokenResult['mensagem']}");

            return self::FAILURE;
        }

        $this->token = $tokenResult['token'];
        $this->info('Autenticado com sucesso.');

        $zip = new ZipArchive;
        if ($zip->open($zipPath) !== true) {
            $this->error('Não foi possível abrir o ZIP.');

            return self::FAILURE;
        }

        $total = $zip->numFiles;
        $sessao = now()->format('Y-m-d H:i:s');

        $this->info("ZIP aberto: {$total} arquivo(s) encontrado(s)");
        $this->info("CNPJ: {$cnpj}");
        $this->info("URL: {$baseUrl}/api/enviaxmltexto");
        $this->newLine();

        $this->log->info("INICIO", [
            'sessao'   => $sessao,
            'cnpj'     => $cnpj,
            'zip'      => basename($zipPath),
            'total'    => $total,
            'limite'   => $limite ?: 'sem limite',
        ]);

        $enviados  = 0;
        $erros     = 0;
        $ignorados = 0;

        $bar = $this->output->createProgressBar($limite > 0 ? min($limite, $total) : $total);
        $bar->start();

        for ($i = 0; $i < $total; $i++) {
            if ($limite > 0 && ($enviados + $erros) >= $limite) {
                break;
            }

            $nome = $zip->getNameIndex($i);

            if (str_ends_with($nome, '/')) {
                $ignorados++;
                continue;
            }

            $status = $this->detectaStatus(basename($nome));

            if (! $status) {
                $ignorados++;
                $this->log->debug("IGNORADO", ['arquivo' => basename($nome), 'motivo' => 'status não identificado']);
                continue;
            }

            $conteudo = $zip->getFromIndex($i);
            if ($conteudo === false) {
                $erros++;
                $this->log->error("ERRO_LEITURA", ['arquivo' => basename($nome)]);
                $bar->advance();
                continue;
            }

            $resultado = $this->enviaXML($baseUrl, $cnpj, $status, $conteudo);

            if ($resultado['ok']) {
                $enviados++;
                $this->log->info("OK", [
                    'arquivo' => basename($nome),
                    'status'  => $status,
                    'http'    => $resultado['http_code'],
                ]);
            } else {
                $erros++;
                $this->log->error("ERRO_ENVIO", [
                    'arquivo'  => basename($nome),
                    'status'   => $status,
                    'http'     => $resultado['http_code'],
                    'resposta' => $resultado['mensagem'],
                ]);
                $this->newLine();
                $this->warn("  [{$status}] {$nome} → {$resultado['mensagem']}");
            }

            unset($conteudo);
            $bar->advance();
        }

        $zip->close();
        $bar->finish();
        $this->newLine(2);

        $this->log->info("FIM", [
            'sessao'    => $sessao,
            'enviados'  => $enviados,
            'erros'     => $erros,
            'ignorados' => $ignorados,
        ]);

        $this->table(['Resultado', 'Quantidade'], [
            ['Enviados com sucesso', $enviados],
            ['Erros',               $erros],
            ['Ignorados',           $ignorados],
        ]);

        return $erros > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function detectaStatus(string $nome): ?string
    {
        if (str_contains($nome, 'ProcNfe') || str_contains($nome, 'procNfe')) {
            return 'AUTORIZADO';
        }
        if (str_contains($nome, 'Can') || str_contains($nome, 'can')) {
            return 'CANCELADO';
        }
        if (str_contains($nome, 'inu') || str_contains($nome, 'Inu')) {
            return 'INUTILIZADO';
        }

        return null;
    }

    private function autenticar(string $baseUrl, string $email, string $senha): array
    {
        $ch = curl_init("{$baseUrl}/api/autenticacao");

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_POSTFIELDS     => http_build_query(['email' => $email, 'password' => $senha]),
        ]);

        $resposta  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return ['ok' => false, 'mensagem' => "cURL error: {$curlError}", 'token' => ''];
        }

        $json = json_decode($resposta, true);

        if ($httpCode !== 200 || empty($json['token'])) {
            $mensagem = is_string($json) ? $json : ($json['mensagem'] ?? $resposta);

            return ['ok' => false, 'mensagem' => $mensagem, 'token' => ''];
        }

        return ['ok' => true, 'mensagem' => '', 'token' => $json['token']];
    }

    private function enviaXML(string $baseUrl, string $cnpj, string $status, string $conteudo): array
    {
        $ch = curl_init("{$baseUrl}/api/enviaxmltexto");

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => [
                'empresa: '.$cnpj,
                'status: '.$status,
                'Authorization: Bearer '.$this->token,
                'Content-Type: application/x-www-form-urlencoded',
            ],
            CURLOPT_POSTFIELDS => http_build_query([
                'xml' => base64_encode($conteudo),
            ]),
        ]);

        $resposta  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return ['ok' => false, 'http_code' => 0, 'mensagem' => "cURL error: {$curlError}"];
        }

        $json     = json_decode($resposta, true);
        $mensagem = $json['mensagem'] ?? $resposta;

        return [
            'ok'        => $httpCode === 200,
            'http_code' => $httpCode,
            'mensagem'  => $mensagem,
        ];
    }
}
