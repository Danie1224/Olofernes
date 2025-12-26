<?php

namespace App\Notifications;

use App\Models\ReturnRequest;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ReturnRequestSubmitted extends Notification
{
    use Queueable;

    public ReturnRequest $returnRequest;
    public Customer $customer;
    public float $totalRefund;

    /**
     * Create a new notification instance.
     */
    public function __construct(ReturnRequest $returnRequest, Customer $customer, float $totalRefund)
    {
        $this->returnRequest = $returnRequest;
        $this->customer = $customer;
        $this->totalRefund = $totalRefund;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => '🔄 New Return Request',
            'message' => "Customer {$this->customer->name} ({$this->customer->email}) submitted a return request for Order #{$this->returnRequest->order_id}",
            'request_id' => $this->returnRequest->request_id,
            'customer_id' => $this->customer->customer_id,
            'order_id' => $this->returnRequest->order_id,
            'total_refund' => $this->totalRefund,
            'items_count' => $this->returnRequest->items->count(),
            'customer_name' => $this->customer->name,
            'customer_email' => $this->customer->email,
            'action_url' => route('admin.returns.show', $this->returnRequest->request_id),
        ];
    }
}
