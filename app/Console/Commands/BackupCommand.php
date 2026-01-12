<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class BackupCommand extends Command
{
    protected $signature = 'db:backup {--compress} {--cloud}';
    protected $description = 'Faz backup do banco de dados';

    public function handle()
    {
        $this->info('Iniciando backup do banco de dados...');

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $filename = 'backup_' . $database . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups');

        // Cria diretório se não existir
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $filepath = $path . '/' . $filename;

        // Comando mysqldump
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($database),
            escapeshellarg($filepath)
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('Erro ao fazer backup!');
            return 1;
        }

        $size = filesize($filepath);
        $this->info("Backup criado com sucesso: {$filename}");
        $this->info("Tamanho: " . $this->formatBytes($size));

        // Compacta se solicitado
        if ($this->option('compress')) {
            $this->info('Compactando backup...');
            exec("gzip {$filepath}");
            $filepath .= '.gz';
            $this->info('Backup compactado!');
        }

        return 0;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    private function cleanOldBackups($path)
    {
        $files = glob($path . '/backup_*.sql*');
        $now = time();

        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 60 * 60 * 24 * 7) { // 7 dias
                    unlink($file);
                    $this->info('Removido backup antigo: ' . basename($file));
                }
            }
        }
    }
}
