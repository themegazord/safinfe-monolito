<div>
  <div class="valores-nota">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Valor total das notas:</h5>
        <p class="card-text">{{ $this->formataValoresMonetarios(floatval($informacoesTotaisNotas['Totais das notas']), 'BRL') }}</p>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Valor total da ICMS das nota:</h5>
        <p class="card-text">{{ $this->formataValoresMonetarios(floatval($informacoesTotaisNotas['Total de ICMS das notas']), 'BRL') }}</p>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Valor total de ICMS ST das nota:</h5>
        <p class="card-text">{{ $this->formataValoresMonetarios(floatval($informacoesTotaisNotas['Total de ICMS ST das notas']), 'BRL') }}</p>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Valor total de PIS das nota:</h5>
        <p class="card-text">{{ $this->formataValoresMonetarios(floatval($informacoesTotaisNotas['Valor total do PIS']), 'BRL') }}</p>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Valor total de COFINS das nota:</h5>
        <p class="card-text">{{ $this->formataValoresMonetarios(floatval($informacoesTotaisNotas['Valor total do COFINS']), 'BRL') }}</p>
      </div>
    </div>
  </div>
  <style>
    .valores-nota {
      display: flex;
      gap: 1rem;
    }
  </style>
</div>
