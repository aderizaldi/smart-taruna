<?php 

use Livewire\Volt\Component;
use App\Models\LandingPageAchievement;

new class extends Component {
    public $description = '';
    public $amount = '';
    public $icon = '';
    public $search = '';
    public $achievementId = null;
    public $confirmingAchievementDeletion = false;
    public $editMode = false;

    public function getAchievements(){
        $query = LandingPageAchievement::query();
        if($this->search){
            $query = $query->where('description', 'like', '%' . $search . '%')->orWhere('amount', 'like', '%' . $search . '%');
        }
        $query = $query->latest();
        return $query->paginate(10);
    }

    public function resetForm(){
        $this->description = '';
        $this->amount = '';
        $this->icon = '';
        $this->editMode = false;
        $this->achievementId = null;
        $this->resetValidation();
    }

    public function store(){
        $this->validate([
            'description' => 'required|string',
            'amount' => 'required|string',
            'icon' => 'required|string',
        ]);

        LandingPageAchievement::create([
            'description' => $this->description,
            'amount' => $this->amount,
            'icon' => $this->icon,
        ]);
        $this->dispatch('updated');
        $this->resetForm();
        $this->dispatch('showToast', 'success', 'Data berhasil disimpan.');
    }

    public function edit($id){
        $achievement = LandingPageAchievement::findOrFail($id);
        $this->description = $achievement->description;
        $this->amount = $achievement->amount;
        $this->icon = $achievement->icon;
        $this->editMode = true;
        $this->achievementId = $id;
    }

    public function update(){
        $this->validate([
            'description' => 'required|string',
            'amount' => 'required|string',
            'icon' => 'required|string',
        ]);

        $achievement = LandingPageAchievement::findOrFail($this->achievementId);
        $achievement->update([
            'description' => $this->description,
            'amount' => $this->amount,
            'icon' => $this->icon,
        ]);
        $this->dispatch('updated');
        $this->resetForm();
    }

    public function confirmDelete($id){
        $this->achievementId = $id;
        $this->confirmingAchievementDeletion = true;
    }

    public function delete(){
        $achievement = LandingPageAchievement::findOrFail($this->achievementId);
        $achievement->delete();
        $this->confirmingAchievementDeletion = false;
        $this->resetForm();
    }

    public function with(): array
    {
        return [
            'achievements' => $this->getAchievements(),
        ];
    }
};

?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Achievement</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Form untuk Create dan Edit -->
    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <h2 class="text-lg font-semibold mb-4">{{ $editMode ? 'Edit Achievement' : 'Tambah Achievement Baru' }}</h2>

        <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}">
            <flux:textarea label="Deskripsi" wire:model="description" placeholder="Masukan deskripsi.." resize="none"></flux:textarea>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                <flux:input type="text" label="Jumlah" wire:model="amount" placeholder="Masukan jumlah.."></flux:input>
                <flux:field>
                    <flux:label>Ikon</flux:label>
                    <flux:input.group>
                        <flux:input type="text" wire:model="icon" placeholder="Masukkan .svg ikon"></flux:input>
                        <flux:tooltip content="Cari SVG Ikon">
                            <flux:button icon="magnifying-glass" href="https://heroicons.com/" target="_blank"></flux:button>
                        </flux:tooltip>
                    </flux:input.group>
                    <flux:error name="icon" />
                </flux:field>
            </div>

            <div class="flex justify-end mt-4 space-x-2 items-center">

                <x-action-message class="me-3" on="updated">
                    {{ __('Saved.') }}
                </x-action-message>

                <flux:button type="button" wire:click="resetForm">{{ $editMode ? 'Batal' : 'Reset' }}</flux:button>
                <flux:button type="submit" variant="primary">{{ $editMode ? 'Update' : 'Simpan' }}</flux:button>
            </div>
        </form>
    </div>

    <!-- Tabel Users -->
    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Daftar Achievement</h2>
            <div class="flex items-center">
                <flux:input type="search" wire:model.live.debounce.250ms="search" placeholder="Cari..." class="mr-2" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Deskripsi
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Jumlah
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Ikon
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($achievements as $item)
                    <tr wire:key="user-{{ $item->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->amount }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{!! $item->icon !!}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <flux:button type="button" wire:click="edit({{ $item->id }})" size="xs">Edit
                            </flux:button>
                            <flux:button type="button" wire:click="confirmDelete({{ $item->id }})" variant="danger" size="xs">
                                Hapus</flux:button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap">
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data
                                tersedia</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $achievements->links() }}
        </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <flux:modal wire:model="confirmingAchievementDeletion" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus Achievement?</flux:heading>

                <flux:subheading>
                    <p>Apakah Anda yakin ingin menghapus achievement ini.</p>
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