<?php 

namespace App\Services;

use App\Notifications\OrderClient;
use App\Notifications\OrderManager;
use Illuminate\Support\Facades\Notification;

class NotificationService 
{
    public function newOrder($order) {
        $this->notifyClient($order);
        $this->notifyManager($order);
    }

    public function notifyClient($order) 
    {
        Notification::route('mail', $order->client_email)->notify(new OrderClient($order->price));
    }

    public function notifyManager($order) 
    {
        Notification::route('mail', config('notifications.email.managers'))->notify(new OrderManager($order->price));
    }
}