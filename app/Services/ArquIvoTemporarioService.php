<?php

namespace App\Services;

class ArquivoTemporarioService
{
  public function limparDiretorioUsuario(string $path): void
  {
    if (!is_dir($path)) return;

    $arquivos = glob($path . '/*.xml');
    foreach ($arquivos as $arquivo) {
      if (is_file($arquivo)) {
        unlink($arquivo);
      }
    }

    @rmdir($path); // tenta remover o diretório (somente se estiver vazio)
  }

  public function apagarArquivo(string $caminho): void
  {
    if (file_exists($caminho)) {
      unlink($caminho);
    }
  }
}
