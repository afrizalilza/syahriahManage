<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegisteredNotification extends Notification
{
    use Queueable;

    protected $newUser;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\User  $newUser The user that has just registered.
     * @return void
     */
    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // We want to store this notification in the database AND send an email.
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('user.pending');

        return (new MailMessage)
                    ->subject('Persetujuan Pengguna Baru - SyahriahManage')
                    ->greeting('Halo Admin,')
                    ->line("Pengguna baru dengan nama '{$this->newUser->name}' (Email: {$this->newUser->email}) telah mendaftar dan menunggu persetujuan Anda.")
                    ->action('Tinjau Pengguna', $url)
                    ->line('Silakan login ke dashboard untuk melakukan persetujuan.');
    }

    /**
     * Get the array representation of the notification.
     *
     * This data will be stored in the `data` column of the `notifications` table as JSON.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->newUser->id,
            'user_name' => $this->newUser->name,
            'message' => "Pengguna baru '{$this->newUser->name}' telah mendaftar dan menunggu persetujuan.",
            'url' => route('user.pending'), // URL to the approval page
        ];
    }
}
