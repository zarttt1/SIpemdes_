<?php

namespace App\Livewire;

use App\Models\Pengaduan;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class PengaduanStatistics extends Component
{
    public $userType = 'masyarakat'; // 'masyarakat' or 'petugas'
    public $total = 0;
    public $baru = 0;
    public $diproses = 0;
    public $selesai = 0;

    public function mount($userType = 'masyarakat')
    {
        $this->userType = $userType;
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $query = Pengaduan::query();

        if ($this->userType === 'masyarakat') {
            $query->where('id_masyarakat', Auth::guard('web')->id());
        }

        $this->total = $query->count();
        $this->baru = (clone $query)->where('status', 'baru')->count();
        $this->diproses = (clone $query)->where('status', 'diproses')->count();
        $this->selesai = (clone $query)->where('status', 'selesai')->count();
    }

    #[On('status-updated')]
    #[On('pengaduan-created')]
    #[On('pengaduan-status-changed')]
    public function refreshStatistics()
    {
        $this->loadStatistics();
    }

    public function render()
    {
        return view('livewire.pengaduan-statistics');
    }
}
