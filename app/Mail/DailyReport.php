<?php

namespace App\Mail;

use App\Item;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Order;
use Illuminate\Database\Eloquent\Collection;

class DailyReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->date = Carbon::now()->format('d.m.Y');
        $orders = Order::whereBetween('created_at', [now()->modify('-1 day'), now()])->get();
        $this->itemsSold = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if (isset($this->itemsSold[$item->id])) {
                    $this->itemsSold[$item->id]['income'] += $item->pivot->item_price * $item->pivot->quantity;
                    $this->itemsSold[$item->id]['quantity'] += $item->pivot->quantity;
                } else {
                    $this->itemsSold[$item->id] = [
                        'title' => $item->title,
                        'income' => $item->pivot->item_price,
                        'quantity' => $item->pivot->quantity,
                        'stock' => $item->stock
                    ];
                }
            }
        }
        $this->outOfStock = Item::select(['title', 'stock'])->where('stock', '<', 5)->get();
        $this->income = $orders->sum('price');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.daily_report', [
            'date' => $this->date, 
            'itemsSold' => $this->itemsSold,
            'outOfStock' => $this->outOfStock,
            'income' => $this->income
        ]);
    }
}
