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
  <script>
    const body = document.querySelector('body'),
      sidebar = document.querySelector('.sidebar'),
      toggle = document.querySelector('.toggle'),
      searchBtn = document.querySelector('.search-box');


    toggle.addEventListener("click", () => {
      sidebar.classList.toggle('close');
    })
  </script>

  <style>
    body {
      height: 100vh;
      background-color: var(--body-color);
    }

    /* ===== Sidebar ===== */

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 250px;
      padding: 10px 14px;
      background-color: var(--sidebar-color);
      transition: var(--tran-05);
      z-index: 100;
    }

    .sidebar.close {
      width: 86px;
    }

    /* ==== CSS Reutilizavel ==== */

    .sidebar .text {
      font-size: 1rem;
      font-weight: 500;
      color: var(--text-color);
      transition: var(--tran-03);
      white-space: nowrap;
      display: block;
    }

    .sidebar.close .text {
      display: none;
    }

    .sidebar .logo {
      min-width: 3.75rem;
      display: flex;
      align-items: center;
    }

    .sidebar li {
      height: 50px;
      margin-top: 10px;
      list-style: none;
      display: flex;
      align-items: center;
    }

    .sidebar li .icon {
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      min-width: 60px;
    }

    .sidebar li .icon,
    .sidebar li .text {
      color: var(--text-color);
      transition: var(--tran-02);
    }

    .sidebar header {
      position: relative;
    }

    .sidebar header .image-text .logo span {
      font-size: 1.5rem;
      padding: .5rem;
      border-radius: 10px;
      background-color: var(--primary-color);
      color: var(--sidebar-color);
      font-weight: 600;
    }

    .sidebar header .image-text {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    header .image-text .header-text {
      display: flex;
      flex-direction: column;
    }

    .header-text .name {
      font-weight: 600;
    }

    .header-text .profession {
      margin-top: -2px;
    }

    .sidebar header .toggle {
      position: absolute;
      top: 50%;
      right: -25px;
      transform: translateY(-50%) rotate(180deg);
      height: 25px;
      width: 25px;
      background-color: var(--primary-color);
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      color: var(--sidebar-color);
      font-size: .8rem;
      transition: var(--tran-03);
      cursor: pointer;
    }

    .sidebar.close header .toggle {
      transform: translateY(-50%);
    }

    .sidebar .search-box {
      background-color: var(--primary-color-light);
      border-radius: 6px;
    }

    .search-box input {
      height: 100%;
      width: 100%;
      outline: none;
      border: none;
      border-radius: 6px;
      background-color: var(--primary-color-light);
    }

    .sidebar li a {
      height: 100%;
      width: 100%;
      display: flex;
      align-items: center;
      text-decoration: none;
      transition: var(--tran-04);
      border-radius: 6px;
    }

    .sidebar li a:hover {
      background-color: var(--primary-color);
    }

    .sidebar li a:hover .icon,
    .sidebar li a:hover .text {
      color: var(--sidebar-color);
    }

    .sidebar .menu-bar {
      height: calc(100% - 50px);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .main {
      position: relative;
      height: 100vh;
      left: 100px;
      width: calc(100%);
      margin-left: 0;
      transition: var(--tran-05);
      background-color: var(--body-color);
      z-index: 50;
    }

    .sidebar.close~.main {
      margin-left: 0;
      left: 100px;
      width: calc(100% - 100px);
    }
  </style>
</nav>
