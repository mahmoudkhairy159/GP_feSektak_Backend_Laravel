<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestCanceled extends Notification
{
    use Queueable;
    public $request;
    public $userName;




    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($request,$user)
    {
        $this->request=$request;
        if($user->id !=  $this->request->user->id){
            $this->userName=$user->name; //store driverName
        }else{
            $this->userName= $this->request->user->name;
        }
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
            'user' =>  $this->userName,
            'requestId'=>$this->request->id,
            'rideId'=>$this->request->ride->id
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
        return [
            //
        ];
    }
}
