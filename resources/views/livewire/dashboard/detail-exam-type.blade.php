<?php

use Livewire\Volt\Component;
use App\Models\Type;

new class extends Component {
    public $typeId;
    public $typeName;
    public $typeDescription;
    public $typePassingScore;

    public $isModalEditTypeOpen = false;
    public $isModalDeleteTypeOpen = false;

    public function mount(Type $type)
    {
        $this->typeId = $type->id;
        $this->typeName = $type->name;
        $this->typeDescription = $type->description;
        $this->typePassingScore = $type->passing_score;
    }

    public function openModalEditType() {
        $this->isModalEditTypeOpen = true;
    }

    public function openModalDeleteType() {
        $this->isModalDeleteTypeOpen = true;
    }

    public function closeModalEditType() {
        $this->isModalEditTypeOpen = false;
    }

    public function closeModalDeleteType() {
        $this->isModalDeleteTypeOpen = false;
    }

    public function deleteType(){
        Type::where('id', $this->typeId)->delete();
        $this->closeModalDeleteType();
        $this->redirectRoute('dashboard.exam-type');
        $this->dispatch('showToast', 'success', 'Jenis Soal berhasil dihapus.');
    }

    public function updateType(){
         $this->validate([
            'typeName' => 'required',
            'typeDescription' => 'nullable',
            'typePassingScore' => 'required|numeric',
         ]);

         Type::where('id', $this->typeId)->update([
            'name' => $this->typeName,
            'description'=> $this->typeDescription,
            'passing_score' => $this->typePassingScore,
        ]);

        $this->closeModalEditType();
        $this->dispatch('showToast', 'success', 'Jenis Soal berhasil diperbarui.');
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('dashboard.exam-type') }}">Jenis Soal</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Detail Jenis Soal</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
            <h2 class="text-lg font-semibold">Detail Jenis Soal</h2>
            <flux:dropdown class="flex justify-start md:justify-end items-center">
                <flux:button icon-trailing="chevron-down" size="sm">Aksi</flux:button>
                <flux:menu>
                    <flux:menu.item wire:click="openModalEditType">Edit</flux:menu.item>
                    <flux:menu.item variant="danger" wire:click="openModalDeleteType">Hapus</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
            <div>
                <flux:heading>Jenis Soal</flux:heading>
                <flux:text class="mt-2">{{ $typeName }}</flux:text>
            </div>
            <div>
                <flux:heading>Deskripsi</flux:heading>
                <flux:text class="mt-2">{!! $typeDescription ?: '-' !!}</flux:text>
            </div>
            <div>
                <flux:heading>Nilai Kelulusan</flux:heading>
                <flux:text class="mt-2">{{ $typePassingScore }}</flux:text>
            </div>
        </div>
    </div>
    <flux:separator />

    {{-- modal hapus type --}}
    <!-- Modal Konfirmasi Delete -->
    <flux:modal wire:model="isModalDeleteTypeOpen" class="min-w-sm">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">Hapus Jenis Soal?</flux:heading>
                <flux:subheading>
                    <p>Apakah Anda yakin ingin menghapus jenis soal ini.</p>
                    <p>Semua data yang berkaitan dengan jenis soal ini akan dihapus.</p>
                </flux:subheading>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deleteType">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- modal edit --}}
    <flux:modal title="Tambah Jenis Soal" wire:model="isModalEditTypeOpen" class="min-w-xl space-y-4">
        <flux:heading size="lg">Tambah Jenis Soal</flux:heading>
        <form wire:submit="updateType">
            <div class="space-y-4">
                <flux:input label="Jenis Soal" wire:model="typeName" />
                <livewire:plugin.text-editor label="Deskripsi" wire:model="typeDescription" size="xs" />
                <flux:input type="number" label="Nilai Kelulusan" wire:model="typePassingScore" />
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