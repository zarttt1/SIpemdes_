<?php

namespace App\Livewire;

use App\Models\Pengaduan;
use App\Models\Tanggapan;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class TanggapanList extends Component
{
    public Pengaduan $pengaduan;
    public $tanggapan;

    public function mount(Pengaduan $pengaduan)
    {
        $this->pengaduan = $pengaduan;
        $this->loadTanggapan();
    }

    public function loadTanggapan()
    {
        $this->tanggapan = $this->pengaduan->tanggapan()
            ->with('petugas')
            ->latest('tanggal_tanggapan')
            ->get();
    }

    #[On('tanggapan-added')]
    public function handleTanggapanAdded()
    {
        $this->loadTanggapan();
    }

    #[On('tanggapan-deleted')]
    public function handleTanggapanDeleted()
    {
        $this->loadTanggapan();
        $this->dispatch('pengaduan-status-changed');
    }

    public function deleteTanggapan($tanggapanId)
    {
        $tanggapan = Tanggapan::find($tanggapanId);
        
        if (!$tanggapan) {
            $this->dispatch('notification', message: 'Tanggapan tidak ditemukan!', type: 'error');
            return;
        }

        $petugas = Auth::guard('petugas')->user();
        
        if ($petugas->id !== $tanggapan->id_petugas && $petugas->level !== 'admin') {
            $this->dispatch('notification', message: 'Anda tidak memiliki akses untuk menghapus tanggapan ini!', type: 'error');
            return;
        }

        $tanggapan->delete();

        // Check if need to revert status
        if ($this->pengaduan->tanggapan()->count() === 0 && $this->pengaduan->status === 'diproses') {
            $this->pengaduan->update(['status' => 'baru']);
        }

        $this->dispatch('tanggapan-deleted');
        $this->dispatch('notification', message: 'Tanggapan berhasil dihapus!', type: 'success');
    }

    public function render()
    {
        return view('livewire.tanggapan-list');
    }
}
