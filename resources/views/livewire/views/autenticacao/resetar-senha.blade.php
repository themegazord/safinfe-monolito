<div class="container-form-login">
  <form wire:submit="alterarSenha">
    <div class="header-container-form-login">
      <h1>Insira os dados para resetar a senha.</h1>
    </div>
    <div class="form-floating">
      <input type="email" wire:model="resetSenha.email" id="email" class="form-control" placeholder="Insira seu email">
      <label for="email" class="floatingInput">Insira seu email:</label>
      @error('resetSenha.email') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-floating">
      <input type="password" wire:model="resetSenha.oldPassword" id="senha-antiga" class="form-control" placeholder="Insira sua senha antiga">
      <label for="senha-antiga" class="floatingPassword">Insira sua senha antiga:</label>
      @error('resetSenha.oldPassword') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-floating">
      <input type="password" wire:model="resetSenha.newPassword" id="senha-nova" class="form-control" placeholder="Insira sua senha nova">
      <label for="senha-nova" class="floatingPassword">Insira sua senha nova:</label>
      @error('resetSenha.newPassword') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="footer-container-form-login">
      <button type="submit">
        Alterar senha
        <div wire:loading class="loading">
          <i class="fa-solid fa-spinner"></i>
        </div>
      </button>
    </div>
  </form>
  <style>
    .container-form-login {
      width: 100vw;
      height: 90vh;
      background-color: var(--darkblue);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .loading {
      animation: girar 3s linear infinite;
    }

    @keyframes girar {
      from {
        transform: rotate(0deg);
      }

      to {
        transform: rotate(360deg);
      }
    }

    @media screen and (max-width: 2560px) {
      form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        width: 50%;
        padding: calc(4.5rem * 2560 / 1920) calc(7.5rem * 2560 / 1920) calc(4.5rem * 2560 / 1920) calc(7.5rem * 2560 / 1920);
        border-radius: 15px;
        gap: calc(1rem * 2560 / 1920);
      }

      .header-container-form-login h1 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(2rem * 2560 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .header-container-form-login h3 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.75rem * 2560 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .footer-container-form-login {
        width: 100%;
        display: flex;
        justify-content: center;
      }

      .footer-container-form-login button {
        width: 30%;
        background-color: var(--darkblue);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: calc(1rem * 2560 / 1920);
        transition: .7s;
      }

      .footer-container-form-login button:hover {
        background-color: white;
        border: 1px solid var(--darkblue);
        color: var(--darkblue);
        cursor: pointer;
      }

      .form-floating {
        width: 60%;
      }

      .opcoes {
        gap: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .esqueceuSenha,
      .opcoes .form-check input,
      .opcoes .form-check label {
        font-size: calc(1rem * 2560 / 1920);
      }
    }

    @media screen and (max-width: 1920px) {
      form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        width: 50%;
        padding: calc(4.5rem * 1920 / 1920) calc(7.5rem * 1920 / 1920) calc(4.5rem * 1920 / 1920) calc(7.5rem * 1920 / 1920);
        border-radius: 15px;
        gap: calc(1rem * 1920 / 1920);
      }

      .header-container-form-login h1 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(2rem * 1920 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .header-container-form-login h3 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.75rem * 1920 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .footer-container-form-login {
        width: 100%;
        display: flex;
        justify-content: center;
      }

      .footer-container-form-login button {
        width: 30%;
        background-color: var(--darkblue);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: calc(1rem * 1920 / 1920);
        transition: .7s;
      }

      .footer-container-form-login button:hover {
        background-color: white;
        border: 1px solid var(--darkblue);
        color: var(--darkblue);
        cursor: pointer;
      }

      .form-floating {
        width: 60%;
      }

      .opcoes {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .esqueceuSenha,
      .opcoes .form-check input,
      .opcoes .form-check label {
        font-size: calc(1rem * 1920 / 1920);
      }
    }

    @media screen and (max-width: 1600px) {
      form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        width: 50%;
        padding: calc(4.5rem * 1600 / 1920) calc(7.5rem * 1600 / 1920) calc(4.5rem * 1600 / 1920) calc(7.5rem * 1600 / 1920);
        border-radius: 15px;
        gap: calc(1rem * 1600 / 1920);
      }

      .header-container-form-login h1 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(2rem * 1600 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .header-container-form-login h3 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.75rem * 1600 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .footer-container-form-login {
        width: 100%;
        display: flex;
        justify-content: center;
      }

      .footer-container-form-login button {
        width: 30%;
        background-color: var(--darkblue);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: calc(1rem * 1600 / 1920);
        transition: .7s;
      }

      .footer-container-form-login button:hover {
        background-color: white;
        border: 1px solid var(--darkblue);
        color: var(--darkblue);
        cursor: pointer;
      }

      .form-floating {
        width: 60%;
      }

      .opcoes {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .esqueceuSenha,
      .opcoes .form-check input,
      .opcoes .form-check label {
        font-size: calc(1rem * 1600 / 1920);
      }
    }

    @media screen and (max-width: 1440px) {
      form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        width: 50%;
        padding: calc(4.5rem * 1440 / 1920) calc(7.5rem * 1440 / 1920) calc(4.5rem * 1440 / 1920) calc(7.5rem * 1440 / 1920);
        border-radius: 15px;
        gap: calc(1rem * 1440 / 1920);
      }

      .header-container-form-login h3 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(2rem * 1440 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .header-container-form-login h3 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.75rem * 1440 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .footer-container-form-login {
        width: 100%;
        display: flex;
        justify-content: center;
      }

      .footer-container-form-login button {
        width: 30%;
        background-color: var(--darkblue);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: calc(1rem * 1440 / 1920);
        transition: .7s;
      }

      .footer-container-form-login button:hover {
        background-color: white;
        border: 1px solid var(--darkblue);
        color: var(--darkblue);
        cursor: pointer;
      }

      .form-floating {
        width: 60%;
      }

      .opcoes {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .esqueceuSenha,
      .opcoes .form-check input,
      .opcoes .form-check label {
        font-size: calc(1rem * 1440 / 1920);
      }
    }

    @media screen and (max-width: 1366px) {
      form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        width: 50%;
        padding: calc(4.5rem * 1366 / 1920) calc(7.5rem * 1366 / 1920) calc(4.5rem * 1366 / 1920) calc(7.5rem * 1366 / 1920);
        border-radius: 15px;
        gap: calc(1rem * 1366 / 1920);
      }

      .header-container-form-login h1 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(2rem * 1366 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .header-container-form-login h3 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.75rem * 1366 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .footer-container-form-login {
        width: 100%;
        display: flex;
        justify-content: center;
      }

      .footer-container-form-login button {
        width: 30%;
        background-color: var(--darkblue);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: calc(1rem * 1366 / 1920);
        transition: .7s;
      }

      .footer-container-form-login button:hover {
        background-color: white;
        border: 1px solid var(--darkblue);
        color: var(--darkblue);
        cursor: pointer;
      }

      .form-floating {
        width: 60%;
      }

      .opcoes {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .esqueceuSenha,
      .opcoes .form-check input,
      .opcoes .form-check label {
        font-size: calc(1rem * 1366 / 1920);
      }
    }

    @media screen and (max-width: 1280px) {
      form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        width: 50%;
        padding: calc(4.5rem * 1280 / 1920) calc(7.5rem * 1280 / 1920) calc(4.5rem * 1280 / 1920) calc(7.5rem * 1280 / 1920);
        border-radius: 15px;
        gap: calc(1rem * 1280 / 1920);
      }

      .header-container-form-login h1 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(2rem * 1280 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .header-container-form-login h3 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.75rem * 1280 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .footer-container-form-login {
        width: 100%;
        display: flex;
        justify-content: center;
      }

      .footer-container-form-login button {
        width: 30%;
        background-color: var(--darkblue);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: calc(1rem * 1280 / 1920);
        transition: .7s;
      }

      .footer-container-form-login button:hover {
        background-color: white;
        border: 1px solid var(--darkblue);
        color: var(--darkblue);
        cursor: pointer;
      }

      .form-floating {
        width: 60%;
      }

      .opcoes {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .esqueceuSenha,
      .opcoes .form-check input,
      .opcoes .form-check label {
        font-size: calc(1rem * 1280 / 1920);
      }
    }

    @media screen and (max-width: 1024px) {
      form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        width: 50%;
        padding: calc(4.5rem * 1024 / 1920) calc(7.5rem * 1024 / 1920) calc(4.5rem * 1024 / 1920) calc(7.5rem * 1024 / 1920);
        border-radius: 15px;
        gap: calc(1rem * 1024 / 1920);
      }

      .header-container-form-login h1 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(2rem * 1024 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .header-container-form-login h3 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.75rem * 1024 / 1920);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .footer-container-form-login {
        width: 100%;
        display: flex;
        justify-content: center;
      }

      .footer-container-form-login button {
        width: 30%;
        background-color: var(--darkblue);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: calc(1rem * 1024 / 1920);
        transition: .7s;
      }

      .footer-container-form-login button:hover {
        background-color: white;
        border: 1px solid var(--darkblue);
        color: var(--darkblue);
        cursor: pointer;
      }

      .form-floating {
        width: 60%;
      }

      .opcoes {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .esqueceuSenha,
      .opcoes .form-check input,
      .opcoes .form-check label {
        font-size: calc(1rem * 1024 / 1920);
      }
    }

    /* Mobile */

    @media screen and (max-width: 768px) {
      form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        width: 70%;
        padding: calc(2.5rem * 768 / 768) calc(5.5rem * 768 / 768) calc(2.5rem * 768 / 768) calc(5.5rem * 768 / 768);
        border-radius: 15px;
        gap: calc(1rem * 768 / 768);
      }

      .header-container-form-login h1 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.5rem * 768 / 768);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .header-container-form-login h3 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.25rem * 768 / 768);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .footer-container-form-login {
        width: 100%;
        display: flex;
        justify-content: center;
      }

      .footer-container-form-login button {
        width: 60%;
        background-color: var(--darkblue);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: calc(1rem * 768 / 768);
        transition: .7s;
      }

      .footer-container-form-login button:hover {
        background-color: white;
        border: 1px solid var(--darkblue);
        color: var(--darkblue);
        cursor: pointer;
      }

      .form-floating {
        width: 100%;
      }

      .opcoes {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .esqueceuSenha,
      .opcoes .form-check input,
      .opcoes .form-check label {
        font-size: calc(1rem * 768 / 768);
      }
    }

    @media screen and (max-width: 640px) {
      form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        width: 70%;
        padding: calc(2.5rem * 640 / 768) calc(5.5rem * 640 / 768) calc(2.5rem * 640 / 768) calc(5.5rem * 640 / 768);
        border-radius: 15px;
        gap: calc(1rem * 640 / 768);
      }

      .header-container-form-login h1 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.5rem * 640 / 768);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .header-container-form-login h3 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.25rem * 640 / 768);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .footer-container-form-login {
        width: 100%;
        display: flex;
        justify-content: center;
      }

      .footer-container-form-login button {
        width: 60%;
        background-color: var(--darkblue);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: calc(1rem * 640 / 768);
        transition: .7s;
      }

      .footer-container-form-login button:hover {
        background-color: white;
        border: 1px solid var(--darkblue);
        color: var(--darkblue);
        cursor: pointer;
      }

      .form-floating {
        width: 100%;
      }

      .opcoes {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .esqueceuSenha,
      .opcoes .form-check input,
      .opcoes .form-check label {
        font-size: calc(.75rem * 640 / 768);
      }
    }

    @media screen and (max-width: 320px) {
      form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        width: 70%;
        padding: calc(2.5rem * 320 / 768) calc(5.5rem * 320 / 768) calc(2.5rem * 320 / 768) calc(5.5rem * 320 / 768);
        border-radius: 15px;
        gap: calc(1rem * 320 / 768);
      }

      .header-container-form-login h1 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.5rem * 320 / 768);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .header-container-form-login h3 {
        color: var(--darkblue);
        font-family: DM Sans;
        font-size: calc(1.25rem * 320 / 768);
        font-style: normal;
        font-weight: 700;
        line-height: normal;
      }

      .footer-container-form-login {
        width: 100%;
        display: flex;
        justify-content: center;
      }

      .footer-container-form-login button {
        width: 60%;
        background-color: var(--darkblue);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: calc(1rem * 320 / 768);
        transition: .7s;
      }

      .footer-container-form-login button:hover {
        background-color: white;
        border: 1px solid var(--darkblue);
        color: var(--darkblue);
        cursor: pointer;
      }

      .form-floating {
        width: 100%;
      }

      .opcoes {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .esqueceuSenha,
      .opcoes .form-check input,
      .opcoes .form-check label {
        font-size: calc(1rem * 320 / 768);
      }
    }
  </style>
</div>
