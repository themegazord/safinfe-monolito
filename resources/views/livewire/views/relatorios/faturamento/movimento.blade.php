<div class="p-4 md:p-6 flex flex-col">
    <div class="flex flex-col md:grid md:grid-cols-4 gap-4 mb-8">
        @if ($usuario->getAttribute('role') === 'CONTADOR')
        <div class="md:col-span-4">
            <label for="empresaContador" class="block text-sm font-medium text-gray-200 mb-1">Selecione a empresa:</label>
            <select
                wire:model="consulta.empresa_id"
                id="empresaContador"
                class="w-full bg-gray-800 border border-gray-600 text-white rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option selected>Selecione a empresa...</option>
                @foreach ($empresasContador as $empresa)
                <option value="{{ $empresa->getAttribute('empresa_id') }}">{{ $empresa->getAttribute('fantasia') }}</option>
                @endforeach
            </select>
        </div>
        @endif

        @if ($usuario->getAttribute('role') === 'ADMIN')
        <div class="md:col-span-4">
            <x-select
                wire:model="consulta.empresa_id"
                label="Selecione a empresa:"
                :options="$empresasGeral"
                option-value="empresa_id"
                option-label="fantasia"
                id="empresaGeral" />
        </div>
        @endif

        @if ($usuario->getAttribute('role') === 'CLIENTE')
        <div class="md:col-span-4">
            <label for="empresaCliente" class="block text-sm font-medium text-gray-200 mb-1">Sua empresa:</label>
            <input
                type="text"
                id="empresaCliente"
                readonly
                value="{{ $usuario->cliente->empresa->getAttribute('fantasia') }}"
                class="w-full bg-gray-800 border border-gray-600 text-white rounded-md px-3 py-2 text-sm" />
        </div>
        @endif

        @php
        $configRangeDatePicker = ['altFormat' => 'd/m/Y', 'mode' => 'range', 'locale' => 'pt'];
        $modelos = [
        ["id" => "TODAS", "name" => "Todas"],
        ["id" => "55", "name" => "NF-e"],
        ["id" => "65", "name" => "NFC-e"],
        ];
        $status = [
        ["id" => "TODAS", "name" => "Todas"],
        ["id" => "AUTORIZADO", "name" => "Autorizadas"],
        ["id" => "CANCELADO", "name" => "Canceladas"],
        ["id" => "INUTILIZADO", "name" => "Inutilizadas"],
        ];
        @endphp

        <div class="flex flex-col  md:col-span-4  md:grid md:grid-cols-4 gap-4">
            <div class="w-full md:col-span-2 md:w-auto">
                <x-datepicker label="Data inicio - Data fim" placeholder="Data inicio - Data fim" wire:model="consulta.data_inicio_fim" icon="o-calendar" :config="$configRangeDatePicker" inline />
            </div>
            <div class="w-full md:col-span-1 md:w-auto">
                <x-input label="Série" placeholder="Insira a série das notas fiscais..." wire:model="consulta.serie" autocomplete="off" inline />
            </div>
            <div class="w-full md:col-span-1 md:w-auto">
                <x-select label="Modelo" placeholder="Selecione o tipo da nota fiscal" placeholder-value="TODAS" wire:model="consulta.modelo" :options="$modelos" inline />
            </div>
        </div>

        <div class="flex flex-col md:col-span-4 md:grid md:grid-cols-4 gap-4">
            <div class="w-full md:col-span-2 md:w-auto">
                <x-select label="Selecione o status" placeholder="Selecione o status da nota fiscal..." placeholder-value="TODAS" wire:model="consulta.status" :options="$status" inline />
            </div>
            <div class="w-full md:col-span-1 md:w-auto">
                <x-input label="Numero inicial:" placeholder="Insira o número inicial a ser consultado" wire:model="consulta.numeroInicial" inline />
            </div>
            <div class="w-full md:col-span-1 md:w-auto">
                <x-input label="Numero final:" placeholder="Insira o número final a ser consultado" wire:model="consulta.numeroFinal" inline />
            </div>
        </div>
    </div>
    <div class="flex gap-4 flex-row-reverse items-end">
        <x-button
            wire:click="consultar"
            label="Gerar relatório"
            class="btn-primary"
            spinner="consultar" />
        <x-button
            wire:click="exportarPDF"
            class="btn-info"
            spinner="exportarPDF"
            wire:loading.attr="disabled"
            :disabled="empty($dadosXML)">
            <x-slot:label>
                <div class="flex gap-2">
                    <livewire:icons.repo.pdf />
                    Exportar para PDF
                </div>
            </x-slot:label>
        </x-button>
        <x-button
            wire:click="exportar('xlsx')"
            class="btn-info"
            spinner="exportar"
            wire:loading.attr="disabled"
            :disabled="empty($dadosXML)">
            <x-slot:label>
                <div class="flex gap-2">
                    <livewire:icons.repo.xlsx />
                    Exportar para XLSX
                </div>
            </x-slot:label>
        </x-button>
    </div>

    @if ($dadosXML !== null)
    @include('components.relatorios.faturamento.movimento', ['dadosXML' => $dadosXML])
    @endif
</div>
