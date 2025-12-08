<?php

namespace App\Livewire;

use App\Models\Pengaduan;
use Livewire\Component;
use Livewire\Attributes\On;

class PengaduanStatus extends Component
{
    public Pengaduan $pengaduan;
    public $status;

    public function mount(Pengaduan $pengaduan)
    {
        $this->pengaduan = $pengaduan;
        $this->status = $pengaduan->status;
    }

    public function updateStatus($newStatus)
    {
        $this->validate([
            'status' => 'required|in:baru,diproses,selesai',
        ]);

        $this->pengaduan->update(['status' => $newStatus]);
        $this->status = $newStatus;

        $this->dispatch('status-updated', status: $newStatus);
        $this->dispatch('notification', message: 'Status pengaduan berhasil diperbarui!', type: 'success');
    }

    #[On('pengaduan-status-changed')]
    public function refreshStatus()
    {
        $this->pengaduan->refresh();
        $this->status = $this->pengaduan->status;
    }

    public function render()
    {
        return view('livewire.pengaduan-status');
    }
}
