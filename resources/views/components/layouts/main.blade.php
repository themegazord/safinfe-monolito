<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $title ?? 'Page Title' }}</title>
    {{-- Flatpickr  --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>

    {{-- Chart.js  --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    {{-- TinyMCE --}}
    <script src="https://cdn.tiny.cloud/1/32qv1xwv8jwk9zf35myf0qjbh02xz1kmll6dopo1a3byxlmp/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        flatpickr.localize(flatpickr.l10ns.pt);
    </script>
</head>

<body>
    <x-toast />
    <x-main full-width>
        <x-slot:sidebar drawer="main-drawer" class="bg-base-100 lg:bg-inherit">
            <div class="ml-5 pt-5">SAFI NFE</div>

            <x-menu activate-by-route>

                @if ($usuario = auth()->user())
                <x-menu-separator />

                <x-list-item :item="$usuario" value="name" sub-value="email" no-separator no-hover class="-mx-2 !-my-2 rounded">
                    <x-slot:actions>

                    </x-slot:actions>
                </x-list-item>
                @endif

                <x-menu-item title="Dashboard" icon="o-home" link="{{ route('dashboard') }}" no-wire-navigate />

                @can('viewAny', App\Models\Contabilidade::class)
                    <x-menu-item title="Contabilidades" icon="o-building-library" link="{{ route('contabilidades') }}" no-wire-navigate />
                @endcan

                @can('viewAny', App\Models\Contador::class)
                    <x-menu-item title="Contadores" icon="o-identification" link="{{ route('contadores') }}" no-wire-navigate />
                @endcan

                @can('viewAny', App\Models\Cliente::class)
                    <x-menu-item title="Clientes" icon="o-users" link="{{ route('clientes') }}" no-wire-navigate />
                @endcan

                @can('viewAny', App\Models\Empresa::class)
                    <x-menu-item title="Empresas" icon="o-building-office" link="{{ route('empresas') }}" no-wire-navigate />
                @endcan

                @can('viewAny', App\Models\User::class)
                    <x-menu-item title="Administradores" icon="o-shield-check" link="{{ route('administradores') }}" no-wire-navigate />
                @endcan

                @can('viewAny', App\Models\User::class)
                    <x-menu-item title="Importação" icon="o-arrow-down-tray" link="{{ route('importacao') }}" no-wire-navigate />
                @endcan

                <x-menu-item title="Consulta de XML" icon="o-magnifying-glass" link="{{ route('consultaxml') }}" no-wire-navigate />
                <x-menu-item title="Versionamento" icon="o-clock" link="{{ route('versionamento') }}" no-wire-navigate />

                <x-menu-sub title="Relatórios" icon="o-book-open">
                    <x-menu-sub title="Faturamento" icon="o-document-currency-dollar">
                        <x-menu-item title="Movimento" link="{{ route('relatorios.faturamento.movimento') }}" no-wire-navigate />
                    </x-menu-sub>
                </x-menu-sub>


                <!-- Botão de logout -->

                <x-menu-item title="Sair" icon="o-arrow-left-end-on-rectangle"
                    link="{{ route('logout') }}" no-wire-navigate />

            </x-menu>

        </x-slot:sidebar>
        {{-- The `$slot` goes here --}}
        <x-slot:content>
            <div class="w-full flex flex-row-reverse">
                <label for="main-drawer" class="btn btn-ghost drawer-button lg:hidden">
                    <x-icon name="o-bars-3-bottom-right" class="w-8 h-8" />
                </label>
            </div>
            {{ $slot }}
        </x-slot:content>
    </x-main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/187a0c0ba5.js" crossorigin="anonymous"></script>
    <script src="https://cdn.canvasjs.com/ga/canvasjs.min.js"></script>
</body>

</html>
