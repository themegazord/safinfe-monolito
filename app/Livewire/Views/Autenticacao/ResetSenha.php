<?php

namespace App\Livewire\Views\Autenticacao;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

class ResetSenha extends Component
{
    use Toast;

    public ?string $token = null;

    public ?string $email = null;

    #[Validate(['required'], message: [
        'required' => 'O campo é obrigatório.',
    ])]
    public ?string $senha = null;

    #[Validate(['required', 'same:senha'], message: [
        'required' => 'O campo é obrigatório.',
        'same' => 'A confirmação não é igual a senha informada',
    ])]
    public ?string $novaSenha = null;

    public function mount(?string $token = null, ?string $email = null): void
    {
        if (! $token || ! $email) {
            $this->warning(title: 'Dados invalidos', redirectTo: '/');
        }
        $this->token = $token;
        $this->email = $email;
    }

    #[Layout('components.layouts.autenticacao')]
    #[Title('Redefinir a sua senha')]
    public function render()
    {
        return view('livewire.views.autenticacao.reset-senha');
    }

    public function alterarSenha(): void
    {
        $this->validateOnly('senha');
        $this->validateOnly('novaSenha');

        User::whereEmail($this->email)->update([
            'password' => Hash::make($this->senha),
        ]);

        DB::table('password_reset_tokens')->where('email', $this->email)->delete();

        $this->success('A sua senha foi alterada.', redirectTo: route('login'));
    }
}
