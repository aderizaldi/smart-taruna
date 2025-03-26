<?php 
 
use Livewire\Volt\Component;
use App\Models\LandingPageImage;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\WithFileUploads;

new class extends Component {
    use WithPagination, WithoutUrlPagination, WithFileUploads;
    
    public $image = [];
    public $confirmingImageDeletion = false;
    public $imageId = null;
    
    public function getImages()
    {
        $query = LandingPageImage::query();
        $query = $query->latest();
        return $query->paginate(10);
    }

    public function store(){
        $this->validate([
            'image.*' => 'required|image|max:1024',
        ]);

        foreach ($this->image as $img) {
            LandingPageImage::create([
                'image' => $img->store('landing-page-images'),
            ]);
        }   
        $this->dispatch('saved');
        $this->image = [];
        $this->imageId = null;
        $this->dispatch('showToast', 'success', 'Data berhasil disimpan.');
    }

    public function delete(){
        $image = LandingPageImage::findOrFail($this->imageId);
        Storage::delete($image->image);
        $image->delete();
        $this->imageId = null;
        $this->confirmingImageDeletion = false;
    }

    public function confirmDelete($id){
        $this->imageId = $id;
        $this->confirmingImageDeletion = true;
    }

    public function with(): array
    {
        return [
            'images' => $this->getImages(),
        ];
    }
}

?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Gallery</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <!-- Form untuk Create dan Edit -->
    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <h2 class="text-lg font-semibold mb-4">Tambah Gambar Baru</h2>

        <form wire:submit.prevent="store" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input type="file" wire:model="image" accept="image/*" multiple />
            </div>

            <div class="flex justify-end mt-4 space-x-2 items-center">

                <x-action-message class="me-3" on="saved">
                    {{ __('Saved.') }}
                </x-action-message>

                <flux:button type="button" wire:click="resetForm">Batal</flux:button>
                <flux:button type="submit" variant="primary">Simpan</flux:button>
            </div>
        </form>
    </div>

    <!-- Tabel Users -->
    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Daftar Gambar</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">No
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Gambar
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($images as $item)
                    <tr wire:key="image-{{ $item->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap"><img src="{{ asset('storage/'. $item->image)  }}" alt="" width="150"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <flux:button type="button" wire:click="confirmDelete({{ $item->id }})" variant="danger" size="xs">
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
            {{ $images->links() }}
        </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <flux:modal wire:model="confirmingImageDeletion" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus Gambar?</flux:heading>

                <flux:subheading>
                    <p>Apakah Anda yakin ingin menghapus gambar ini.</p>
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
