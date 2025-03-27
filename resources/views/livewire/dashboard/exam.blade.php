<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\WithFileUploads;
use App\Models\Exam;
use App\Models\Package;
use App\Models\Type;

new class extends Component {
    use WithPagination, withoutUrlPagination, withFileUploads;

    public $packageId = null;
    public $typeId = null;
    public $name = '';
    public $description = '';
    public $image = null;
    public $time = 60;

    public $search = '';
    public $perPage = 10;

    public $isModalOpen = false;

    public $packages;
    public $types;

    public function mount() {
        $this->packages = Package::latest()->get();
        $this->types = Type::latest()->get();
        $this->packageId = $this->packages->first()->id;
        $this->typeId = $this->types->first()->id;
    }

    public function getExams()
    {
         $query = Exam::query();

         if ($this->search) {
            $query = $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')->orWhere('description', 'like', '%' . $this->search . '%');
            })->orWhereRelation('package', 'name', 'like', '%' . $this->search . '%');
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
            'packageId' => 'required|exists:packages,id',
            'typeId' => 'required|exists:types,id',
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'time' => 'required|numeric',
        ]);

        Exam::create([
            "user_id" => auth()->user()->id,
            "package_id" => $this->packageId,
            "type_id" => $this->typeId,
            "name" => $this->name,
            "description" => $this->description,
            "image" => $this->image ? save_as_webp($this->image, 'image/exam/') : null,
            "time" => $this->time
        ]); 

        $this->closeModal();
        $this->reset(['typeId', 'name', 'description', 'image', 'time']);
        $this->dispatch('showToast', 'success', 'Soal Ujian berhasil ditambahkan.');
    }

    public function with() : array {
        return [
            'exams' => $this->getExams(),
        ];
    }

}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Soal Ujian</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Button tambah jenis soal --}}
    <div class="flex justify-end items-center">
        <flux:button type="button" variant="primary" wire:click="openModal">Tambah Soal Ujian</flux:button>
    </div>

    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Daftar Soal Ujian</h2>
            <div class="flex items-center">
                <flux:input type="search" wire:model.live.debounce.250ms="search" placeholder="Cari..." class="mr-2" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Paket
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Nama Soal Ujian
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Jenis Soal
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Waktu Ujian
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($exams as $exam)
                    <tr wire:key="exam-{{ $exam->id }}">
                        <td class="px-6 py-4">{{ $exam->package->name }}</td>
                        <td class="px-6 py-4">{{ $exam->name }}</td>
                        <td class="px-6 py-4">{{ $exam->type->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $exam->time }} Menit</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($exam->is_active)
                            <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                Aktif
                            </span>
                            @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-900 dark:text-gray-300">
                                Tidak Aktif
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <flux:button type="button" href="{{ route('dashboard.exam-detail', $exam->id) }}" size="xs">Detail
                            </flux:button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap">
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data
                                tersedia</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $exams->links() }}
        </div>
    </div>

    {{-- modal tambah soal ujian --}}
    <flux:modal wire:model="isModalOpen" class="min-w-sm md:min-w-xl space-y-4">
        <flux:heading size="lg">Tambah Soal Ujian</flux:heading>
        <form wire:submit="store">
            <div class="space-y-4">
                <flux:select label="Jenis Soal" wire:model="typeId" placeholder="Pilih jenis soal...">
                    @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </flux:select>
                <flux:select label="Paket Ujian" wire:model="packageId" placeholder="Pilih paket ujian...">
                    @foreach($packages as $package)
                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                    @endforeach
                </flux:select>
                <flux:input label="Nama Soal Ujian" wire:model="name" />
                <livewire:plugin.text-editor label="Deskripsi" wire:model="description" size="xs" />
                <flux:field>
                    <flux:label>Waktu</flux:label>
                    <flux:input.group>
                        <flux:input wire:model="time" />
                        <flux:input.group.suffix>Menit</flux:input.group.suffix>
                    </flux:input.group>
                    <flux:error name="time" />
                </flux:field>
                <flux:input type="file" label="Gambar" wire:model="image" class="overflow-hidden" accept="image/*" description:trailing="Gambar maksimal 2MB" />
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