<?php

namespace App\Livewire;

use App\Models\Pengaduan;
use App\Models\Tanggapan;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TanggapanForm extends Component
{
    public Pengaduan $pengaduan;
    public $isi_tanggapan = '';

    public function mount(Pengaduan $pengaduan)
    {
        $this->pengaduan = $pengaduan;
    }

    public function submit()
    {
        $this->validate([
            'isi_tanggapan' => 'required|string|min:10',
        ]);

        $tanggapan = Tanggapan::create([
            'id_pengaduan' => $this->pengaduan->id,
            'id_petugas' => Auth::guard('petugas')->id(),
            'tanggal_tanggapan' => now(),
            'isi_tanggapan' => $this->isi_tanggapan,
        ]);

        // Update status if needed
        if ($this->pengaduan->status === 'baru') {
            $this->pengaduan->update(['status' => 'diproses']);
            $this->dispatch('pengaduan-status-changed');
        }

        // Reset form
        $this->isi_tanggapan = '';

        // Notify components
        $this->dispatch('tanggapan-added', tanggapanId: $tanggapan->id);
        $this->dispatch('notification', message: 'Tanggapan berhasil ditambahkan!', type: 'success');
    }

    public function render()
    {
        return view('livewire.tanggapan-form');
    }
}
