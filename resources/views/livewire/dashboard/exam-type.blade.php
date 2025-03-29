<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\Type;

new class extends Component {
    use WithPagination, withoutUrlPagination;

    public $name = '';
    public $description = '';
    public $passingScore = 0;

    public $search = '';
    public $perPage = 10;

    public $isModalOpen = false;

    public function getTypes()
    {
         $query = Type::query();

         if ($this->search) {
            $query = $query
                ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')->orWhere('description', 'like', '%' . $this->search . '%');
            });
         }

         $query = $query->latest();

         return $query->paginate($this->perPage);
    }

    public function openModal() {
        $this->isModalOpen = true;
    }

    public function closeModal() {
        $this->isModalOpen = false;
    }

    public function store() {
        $this->validate([
            'name' => 'required',
            'description' => 'nullable',
            'passingScore' => 'required|numeric',
        ]);

        Type::create([
            'name' => $this->name,
            'description' => $this->description,
            'passing_score' => $this->passingScore,
        ]); 

        $this->closeModal();
        $this->reset(['name', 'description', 'passingScore']);
        $this->dispatch('showToast', 'success', 'Jenis Ujian berhasil ditambahkan.');
    }

    public function with() : array {
        return [
            'types' => $this->getTypes(),
        ];
    }

}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Jenis Ujian</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Button tambah jenis soal --}}
    <div class="flex justify-end items-center">
        <flux:button type="button" variant="primary" wire:click="openModal">Tambah Jenis Ujian</flux:button>
    </div>

    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Daftar Jenis Ujian</h2>
            <div class="flex items-center">
                <flux:input type="search" wire:model.live.debounce.250ms="search" placeholder="Cari..." class="mr-2" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Jenis Ujian
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Nilai Kelulusan
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Bagian Ujian
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($types as $type)
                    <tr wire:key="type-{{ $type->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $type->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $type->passing_score }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ implode(', ', $type->sections()->pluck('name')->all()) }}</td>
                        <td class="px-6 py-4 text-right">
                            <flux:button type="button" href="{{ route('dashboard.exam-type-detail', $type->id) }}" size="xs">Detail
                            </flux:button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap">
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data
                                tersedia</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $types->links() }}
        </div>
    </div>

    {{-- modal tambah jenis soal --}}
    <flux:modal wire:model="isModalOpen" class="min-w-sm md:min-w-xl space-y-4">
        <flux:heading size="lg">Tambah Jenis Ujian</flux:heading>
        <form wire:submit="store">
            <div class="space-y-4">
                <flux:input label="Jenis Ujian" wire:model="name" />
                <livewire:plugin.text-editor label="Deskripsi" wire:model="description" size="xs" />
                <flux:input type="number" label="Nilai Kelulusan" wire:model="passingScore" />
            </div>
            <div class="flex gap-2 mt-4">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Simpan</flux:button>
            </div>
        </form>
    </flux:modal>
</div>