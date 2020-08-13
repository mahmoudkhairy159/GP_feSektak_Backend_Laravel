<?php

namespace App\Notifications;

use App\Request;
use Illuminate\Http\Request as WebRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestSent extends Notification
{
    use Queueable;
    public $requestt;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Request $requestt)
    {
        $this->requestt = $requestt;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [
            'user' => $this->requestt->user->name,
            'rideId'=> $this->requestt->ride_id,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {

    }
}
