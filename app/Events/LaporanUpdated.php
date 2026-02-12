<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
// Gunakan ShouldBroadcastNow agar data terkirim INSTAN tanpa menunggu antrean (queue)
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LaporanUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public function __construct($data = [])
    {
        // Default array kosong jika tidak ada data spesifik
        $this->data = $data;
    }

    public function broadcastOn(): array
    {
        // Disamakan dengan script Frontend
        return [
            new Channel('laporan-channel'),
        ];
    }

    public function broadcastAs()
    {
        // Disamakan dengan script Frontend (menggunakan dot notation biasanya lebih standar)
        return 'laporan.updated';
    }

    public function broadcastWith()
    {
        return [
            'payload' => $this->data,
            'timestamp' => now()->toDateTimeString()
        ];
    }
}