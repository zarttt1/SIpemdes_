<?php

namespace App\Events;

use App\Models\Tanggapan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TanggapanAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tanggapan;

    /**
     * Create a new event instance.
     */
    public function __construct(Tanggapan $tanggapan)
    {
        $this->tanggapan = $tanggapan;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('pengaduan.' . $this->tanggapan->id_pengaduan),
            new PrivateChannel('masyarakat.' . $this->tanggapan->pengaduan->id_masyarakat),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'tanggapan.added';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'tanggapan_id' => $this->tanggapan->id,
            'pengaduan_id' => $this->tanggapan->id_pengaduan,
            'petugas_nama' => $this->tanggapan->petugas->nama,
            'isi_tanggapan' => $this->tanggapan->isi_tanggapan,
            'created_at' => $this->tanggapan->tanggal_tanggapan->toDateTimeString(),
        ];
    }
}
