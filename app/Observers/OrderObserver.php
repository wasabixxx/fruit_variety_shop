<?php

namespace App\Observers;

use App\Models\Order;
use App\Mail\OrderStatusUpdateMail;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Check if order_status has changed
        if ($order->isDirty('order_status')) {
            $oldStatus = $order->getOriginal('order_status');
            $newStatus = $order->order_status;

            // Send status update email
            try {
                Mail::to($order->user->email)->send(
                    new OrderStatusUpdateMail($order, $oldStatus, $newStatus)
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send order status update email: ' . $e->getMessage());
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
