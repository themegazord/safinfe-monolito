<?php

namespace App\Trait;

use App\Models\User;
use App\Notifications\SolicitacaoResetSenhaNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait EnviaEmailResetSenhaTrait
{
    public function enviaEmail(string $email): void
    {
        $usuario = User::whereEmail($email)->first();

        if ($usuario !== null) {
            $token = Str::uuid();

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                ['token' => $token],
            );

            $usuario->notify(new SolicitacaoResetSenhaNotification($token, $email));
        }
    }
}
