<?php

namespace App\Livewire;

use App\Models\Pengaduan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class PengaduanList extends Component
{
    use WithPagination;

    public $filter = 'semua';
    public $search = '';
    public $userType = 'masyarakat'; // 'masyarakat' or 'petugas'

    public function mount($userType = 'masyarakat')
    {
        $this->userType = $userType;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    #[On('status-updated')]
    public function handleStatusUpdate()
    {
        // Refresh the list when status is updated
        $this->render();
    }

    #[On('pengaduan-created')]
    public function handlePengaduanCreated()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Pengaduan::query();

        if ($this->userType === 'masyarakat') {
            $query->where('id_masyarakat', Auth::guard('web')->id());
        }

        if ($this->filter !== 'semua') {
            $query->where('status', $this->filter);
        }

        if ($this->search) {
            $query->where('isi_laporan', 'like', '%' . $this->search . '%');
        }

        $pengaduan = $query->with('masyarakat')
            ->latest('tanggal_pengaduan')
            ->paginate(10);

        return view('livewire.pengaduan-list', compact('pengaduan'));
    }
}
