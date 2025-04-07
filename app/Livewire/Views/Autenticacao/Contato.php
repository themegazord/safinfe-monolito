<?php

namespace App\Livewire\Views\Autenticacao;

use App\Mail\Contato as MailContato;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

#[Validate(rule: [
  'nome' => 'required|max:155',
  'email' => 'required|max:255',
  'telefone' => 'required|max:20',
  'assunto' => 'required',
], message: [
  'required' => 'O campo é obrigatório',
  'max:155' => 'O :attribute deve conter no máximo 155 caracteres',
  'max:255' => 'O :attribute deve conter no máximo 255 caracteres',
  'max:20' => 'O :attribute deve conter no máximo 20 caracteres',
])]
class Contato extends Component
{
  use Toast;

  public ?string $nome = null;
  public ?string $email = null;
  public ?string $telefone = null;
  public ?string $assunto = null;

  #[Title('SAFI NFE - Contato')]
  #[Layout('components.layouts.autenticacao')]
  public function render()
  {
    return view('livewire.views.autenticacao.contato');
  }

  public function contatar(): void
  {
    $this->validate();
    try {
      Mail::to("fatima@sfsistemas.com")
          ->sendNow(new MailContato($this->nome, $this->email, $this->telefone, $this->assunto));
      $this->success('Tudo deu certo, iremos entrar em contato em breve.');
    } catch (\Exception $e) {
      Log::info("Erro ao enviar o email: {$e->getMessage()}");
      $this->warning('Ops, ocorreu um erro ao enviar o email: ' . $e->getMessage());
    }
  }
}
