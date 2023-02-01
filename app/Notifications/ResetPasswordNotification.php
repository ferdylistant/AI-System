<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $data = DB::table('users')->where('email',$notifiable->email)->first();
        $email = urlencode($notifiable->email);
        return (new MailMessage)
                    ->subject('Notifikasi Atur Ulang Sandi')
                    ->greeting('Halo, '.$data->nama)
                    ->line('Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda.')
                    ->action('Atur ulang sandi', url('reset-password/'.$this->token.'?email='.$email))
                    ->line('Tautan pengaturan ulang kata sandi ini akan kedaluwarsa dalam 60 menit.
                    Jika Anda tidak meminta pengaturan ulang kata sandi, tidak diperlukan tindakan lebih lanjut.')
                    ->line('Terimakasih!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
