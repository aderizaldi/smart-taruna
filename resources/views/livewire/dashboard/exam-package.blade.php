<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\WithFileUploads;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithPagination, withoutUrlPagination, withFileUploads;

    public $id = null;
    public $name = '';
    public $description = '';
    public $image = null;

    public $search = '';
    public $perPage = 10;

    public $modal = [
        'create' => false,
        'edit' => false,
        'delete' => false,
    ];

     public function openModal($modal, $id = null) {
        if($modal == 'delete' && $id) {
            $this->id = $id;
        } else if($modal == 'edit' && $id) {
            $package = Package::find($id);
            $this->id = $package->id;
            $this->name = $package->name;
            $this->description = $package->description;
            $this->image = $package->image;
            $this->dispatch('resetEditor', $this->description);
        }
        $this->modal[$modal] = true;
     }

     public function closeModal($modal) {
        $this->modal[$modal] = false;
     }

     public function resetForm(){
         $this->reset(['name', 'description', 'image']);
         $this->dispatch('resetEditor', $this->description);
     }

    public function getPackages()
    {
        $query = Package::query();

        if ($this->search) {
            $query = $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $query = $query->latest();

        return $query->paginate($this->perPage);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ]);

        Package::create([
            "name" => $this->name,
            "description" => $this->description,
            "image" => $this->image ? save_as_webp($this->image, 'image/package/') : null
        ]);

        $this->resetForm();
        $this->closeModal('create');
        $this->dispatch('showToast', 'success', 'Paket Ujian berhasil ditambahkan.');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'description' => 'nullable',
            'image' => $this->image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile ? 'image|max:2048' : '',
        ]);

        $package = Package::find($this->id);
        $image = $this->image;
        
        if($this->image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile){
            if($package->image){
                Storage::delete($package->image);
            }
            $image = save_as_webp($this->image, 'image/package/');
        }

        $package->update([
            "name" => $this->name,
            "description" => $this->description,
            "image" => $image
        ]);

        $this->resetForm();
        $this->closeModal('edit');
        $this->dispatch('showToast', 'success', 'Paket Ujian berhasil diperbarui.');
    }

    public function delete(){
        $package = Package::find($this->id);
        if($package->image){
            Storage::delete($package->image);
        }
        $package->delete();
        $this->closeModal('delete');
        $this->dispatch('showToast', 'success', 'Paket Ujian berhasil dihapus.');
    }

    public function removeImage(){
        $this->image = null;
    }

    public function with() : array {
        return [
            'packages' => $this->getPackages(),
        ];
    }

}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Paket Ujian</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Button tambah jenis soal --}}
    <div class="flex justify-end items-center">
        <flux:button type="button" variant="primary" wire:click="openModal('create')">Tambah Paket Ujian</flux:button>
    </div>

    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Daftar Paket Ujian</h2>
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Deskripsi
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Gambar
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($packages as $package)
                    <tr wire:key="package-{{ $package->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $package->name }}</td>
                        <td class="px-6 py-4 ">{!! strip_tags($package->description) == '' ? '-' : $package->description !!}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($package->image)
                            <a href="{{ asset('storage/' . $package->image) }}" target="_blank" class="block size-fit">
                                <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-24 h-24 object-cover rounded-lg">
                            </a>
                            @else
                            -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <flux:button type="button" wire:click="openModal('edit', {{ $package->id }})" size="xs">Edit
                            </flux:button>
                            <flux:button type="button" wire:click="openModal('delete', {{ $package->id }})" variant="danger" size="xs">
                                Hapus</flux:button>
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
            {{ $packages->links() }}
        </div>
    </div>

    {{-- modal tambah paket ujian --}}
    <flux:modal wire:model="modal.create" class="min-w-sm md:min-w-xl space-y-4">
        <flux:heading size="lg">Tambah Paket Ujian</flux:heading>
        <form wire:submit="store">
            <div class="space-y-4">
                <flux:input label="Nama Paket" wire:model="name" />
                <livewire:plugin.text-editor label="Deskripsi" wire:model="description" size="xs" />
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

    {{-- modal edit paket ujian --}}
    <flux:modal wire:model="modal.edit" class="min-w-sm md:min-w-xl space-y-4">
        <flux:heading size="lg">Edit Paket Ujian</flux:heading>
        <form wire:submit="update">
            <div class="space-y-4">
                <flux:input label="Nama Paket" wire:model="name" />
                <livewire:plugin.text-editor label="Deskripsi" wire:model="description" size="xs" />
                <flux:field>
                    <flux:label>Gambar</flux:label>
                    @if($image)
                    <div class="flex gap-2 items-center">
                        <img src="{{ is_string($image) ? asset('storage/' . $image) : $image->temporaryUrl() }}" alt="{{ $name }}" class="w-16 h-16 object-cover rounded-lg">
                        <flux:button type="button" variant="danger" wire:click="removeImage" size="xs">Hapus Gambar</flux:button>
                    </div>
                    @endif
                    <flux:input type="file" wire:model="image" class="overflow-hidden" accept="image/*" description:trailing="Gambar maksimal 2MB" />
                    <flux:error name="image" />
                </flux:field>
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

    {{-- modal hapus paket ujian --}}
    <flux:modal wire:model="modal.delete" class="min-w-sm">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">Hapus Paket Ujian?</flux:heading>
                <flux:subheading>
                    <p>Apakah Anda yakin ingin menghapus paket ujian ini.</p>
                    <p>Semua data yang berkaitan dengan paket ujian ini akan dihapus.</p>
                </flux:subheading>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="delete">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>
</div>