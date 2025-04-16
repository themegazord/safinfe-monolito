<div class="p-4 md:p-6 flex flex-col gap-4">
  <h1 class="font-bold text-2xl">Cadastro de Versão</h1>
  <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
    <form wire:submit="cadastrar" class="flex flex-col gap-4">
      <x-input label="Insira o patch da versão" placeholder="Insira o patch da versão..." wire:model="versao.patch" inline />
      <x-editor wire:model="versao.detalhe" label="Insira o que foi feito" />
      <div class="flex flex-col-reverse md:flex-row-reverse gap-4">
        <x-button type="submit" wire:loading.attr="disabled" spinner="cadastrar" label="Cadastrar" class="btn btn-success w-full md:w-auto" />
        <x-button type="button" wire:click="mostrarPreview" wire:loading.attr="disabled" spinner="mostrarPreview" label="Preview" class="btn btn-primary w-full md:w-auto" />
        <x-button type="button" wire:click="voltar" wire:loading.attr="disabled" spinner="voltar" label="Voltar" class="btn btn-error w-full md:w-auto" />
      </div>
    </form>
    <div wire:key="preview-card">
      <div class="preview">
        {!! $versao->detalhe !!}
      </div>
    </div>
  </div>

  <style>
    /* Restaura estilos padrão do HTML dentro de .preview */
    .preview {
      font-family: inherit;
      color: inherit;
      line-height: 1.6;
    }

    .preview h1 {
      font-size: 2rem;
      /* 32px */
      font-weight: 700;
      margin-top: 2rem;
      margin-bottom: 1rem;
    }

    .preview h2 {
      font-size: 1.75rem;
      /* 28px */
      font-weight: 700;
      margin-top: 1.75rem;
      margin-bottom: 1rem;
    }

    .preview h3 {
      font-size: 1.5rem;
      /* 24px */
      font-weight: 600;
      margin-top: 1.5rem;
      margin-bottom: 0.75rem;
    }

    .preview h4 {
      font-size: 1.25rem;
      /* 20px */
      font-weight: 600;
      margin-top: 1.25rem;
      margin-bottom: 0.5rem;
    }

    .preview h5 {
      font-size: 1.125rem;
      /* 18px */
      font-weight: 500;
      margin-top: 1rem;
      margin-bottom: 0.5rem;
    }

    .preview h6 {
      font-size: 1rem;
      /* 16px */
      font-weight: 500;
      margin-top: 0.75rem;
      margin-bottom: 0.5rem;
    }

    .preview p {
      margin-bottom: 1rem;
    }

    .preview ul {
      list-style-type: disc;
      margin-left: 1.5rem;
      margin-bottom: 1rem;
    }

    .preview ol {
      list-style-type: decimal;
      margin-left: 1.5rem;
      margin-bottom: 1rem;
    }

    .preview li {
      margin-bottom: 0.25rem;
    }

    .preview strong {
      font-weight: bold;
    }

    .preview em {
      font-style: italic;
    }

    .preview blockquote {
      margin: 1rem 0;
      padding-left: 1rem;
      border-left: 4px solid #ccc;
      color: #666;
      font-style: italic;
    }
  </style>
</div>
