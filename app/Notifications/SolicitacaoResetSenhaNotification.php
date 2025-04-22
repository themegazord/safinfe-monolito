<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SolicitacaoResetSenhaNotification extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   */
  public function __construct(
    private readonly string $token,
    private readonly string $email
  ) {
  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   */
  public function toMail(object $notifiable): MailMessage
  {

    $url = url("/resetsenha/reset/{$this->token}/{$this->email}");


    return (new MailMessage)
      ->subject('Alteração de Senha - SAFI NFE Online')
      ->greeting("Olá $notifiable->name")
      ->line('Recebemos uma solicitação para alterar a senha da sua conta no SAFI NFE Online.')
      ->action('Alterar Minha Senha', $url)
      ->line('Se você não solicitou esta alteração, ignore este email. Sua senha atual permanecerá a mesma.')
      ->salutation("SAFI NFE Online - Facilitando a problematíca dos XML's");
  }

  /**
   * Get the array representation of the notification.
   *
   * @return array<string, mixed>
   */
  public function toArray(object $notifiable): array
  {
    return [
      //
    ];
  }
}
