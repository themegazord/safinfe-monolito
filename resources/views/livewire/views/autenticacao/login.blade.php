<div class="container-form-login">
  <form wire:submit="logar">
    <div class="header-container-form-login">
      <h1>Que bom te ver de novo!</h1>
      <h3>Acesse e use agora mesmo!</h3>
    </div>
    <div class="form-floating">
      <input type="email" wire:model="login.email" id="email" class="form-control">
      <label for="email" class="floatingInput">Insira seu email:</label>
      @error('login.email') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-floating">
      <input type="password" wire:model="login.senha" id="senha" class="form-control">
      <label for="senha" class="floatingPassword">Insira seu email:</label>
      @error('login.senha') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="opcoes">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" wire:model="lembraSenha" id="lembraSenha" checked>
        <label class="form-check-label" for="lembraSenha">
          Lembrar senha?
        </label>
      </div>
      <a href="#" class="esqueceuSenha">Esqueceu sua senha?</a>
    </div>
    <div class="footer-container-form-login">
      <button type="submit">
        Entrar
        <div wire:loading class="loading">
          <i class="fa-solid fa-spinner"></i>
        </div>
      </button>
    </div>
  </form>
</div>
