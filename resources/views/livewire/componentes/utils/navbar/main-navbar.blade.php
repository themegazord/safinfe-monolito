<nav class="sidebar close">
  <header>
    <div class="image-text">
      <div class="logo">
        <span class="iniciais-usuario">{{ $iniciaisUsuarioLogo }}</span>
      </div>
      <div class="text header-text">
        <span class="name">{{$usuario->name}}</span>
        <span class="profession">Admin SF</span>
      </div>
    </div>

    <i class="fa-solid fa-chevron-right toggle"></i>
  </header>
  <div class="menu-bar">
    <div class="menu">
      <ul class="menu-links">
        <li class="navbar-link">
          <a href="/dashboard">
            <i class="fa-solid fa-house icon"></i>
            <span class="text nav-text">Dashboard</span>
          </a>
        </li>
        @if ($usuario->role === 'ADMIN')
        <li class="navbar-link">
          <a href="/contabilidades">
            <i class="fa-solid fa-scale-unbalanced icon"></i>
            <span class="text nav-text">Contabilidades</span>
          </a>
        </li>
        <li class="navbar-link">
          <a href="/contadores">
            <i class="fa-solid fa-calculator icon"></i>
            <span class="text nav-text">Contadores</span>
          </a>
        </li>
        <li class="navbar-link">
          <a href="/clientes">
            <i class="fa-solid fa-mug-hot icon"></i>
            <span class="text nav-text">Clientes</span>
          </a>
        </li>
        <li class="navbar-link">
          <a href="/empresas">
            <i class="fa-solid fa-city icon"></i>
            <span class="text nav-text">Empresas</span>
          </a>
        </li>
        <li class="navbar-link">
          <a href="/administradores">
            <i class="fa-solid fa-users icon"></i>
            <span class="text nav-text">Administradores</span>
          </a>
        </li>
        <li class="navbar-link">
          <a href="/importacao">
            <i class="fa-solid fa-file-import icon"></i>
            <span class="text nav-text">Importação</span>
          </a>
        </li>
        @endif
        <li class="navbar-link">
          <a href="/consultaxml">
            <i class="fa-solid fa-magnifying-glass icon"></i>
            <span class="text nav-text">Consulta XML</span>
          </a>
        </li>
        <li class="navbar-link">
          <a href="/versionamento">
            <i class="fa-solid fa-code-pull-request icon"></i>
            <span class="text nav-text">Versionamento</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="bottom-content">
      <li class="navbar-link">
        <a wire:click="logout">
          <i class="fa-solid fa-right-from-bracket icon"></i>
          <span class="text nav-text">Sair</span>
        </a>
      </li>
    </div>
  </div>
</nav>


