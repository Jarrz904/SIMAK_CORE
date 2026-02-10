<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // PERBAIKAN: Tambahkan \Contracts\
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LaporanUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($message = "refresh")
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Pastikan nama channel ini sama dengan yang ada di JavaScript/Livewire nanti
        return [
            new Channel('admin-dashboard'),
        ];
    }

    /**
     * Nama event yang akan diterima oleh Laravel Echo
     * Jika tidak ditentukan, biasanya menggunakan nama Class-nya
     */
    public function broadcastAs() { return 'LaporanUpdated'; }
}