<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\LandingPage;


new class extends Component {
    use WithFileUploads;

    public $quote = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $facebook = '';
    public $instagram = '';
    public $twitter = '';
    public $youtube = '';
    public $editMode = false;

    public function mount()
    {
        $landingPage = LandingPage::first();
        if ($landingPage) {
            $this->quote = $landingPage->quote;
            $this->email = $landingPage->email;
            $this->phone = $landingPage->phone;
            $this->address = $landingPage->address;
            $this->facebook = $landingPage->facebook;
            $this->instagram = $landingPage->instagram;
            $this->twitter = $landingPage->twitter;
            $this->youtube = $landingPage->youtube;
        }
    }
    
    public function edit()
    {
        $this->editMode = true;
        $this->dispatch("toggleDisableEditor", true);
    }
    
    public function cancel()
    {
        $this->editMode = false;
        $landingPage = LandingPage::first();
        if ($landingPage) {
            $this->quote = $landingPage->quote;
            $this->email = $landingPage->email;
            $this->phone = $landingPage->phone;
            $this->address = $landingPage->address;
            $this->facebook = $landingPage->facebook;
            $this->instagram = $landingPage->instagram;
            $this->twitter = $landingPage->twitter;
            $this->youtube = $landingPage->youtube;
        }
        
        $this->dispatch("resetEditor", $this->quote);
        $this->dispatch("toggleDisableEditor", false);
    }
    
    public function store()
    {
        $validated = $this->validate([
            'quote' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'instagram' => 'required',
            'facebook' => 'nullable',
            'twitter' => 'nullable',
            'youtube' => 'nullable',
        ]);

        LandingPage::first()->update($validated);        
        $this->editMode = false;
        $this->dispatch("toggleDisableEditor", false);
        $this->dispatch('showToast', 'success', 'Data berhasil disimpan.');
    }

    public function with() : array {
        return [
            'editMode' => $this->editMode
    ];
    }
};
?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Landing Page</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <h2 class="text-lg font-semibold mb-4">Landing Page</h2>

        <form wire:submit.prevent="store">

            <div class="mb-4" wire:ignore>
                <livewire:plugin.text-editor label="Jargon" wire:model="quote" :disabled="!$editMode" />
            </div>
            <flux:separator text="Kontak & Media Sosial" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <flux:input type="email" label="Email" wire:model="email" :disabled="!$editMode" />
                <flux:input type="text" label="No HP" wire:model="phone" :disabled="!$editMode" />
                <flux:input type="text" label="Alamat" wire:model="address" :disabled="!$editMode" />
                <flux:input type="text" label="Facebook" wire:model="facebook" :disabled="!$editMode" />
                <flux:input type="text" label="Instagram" wire:model="instagram" :disabled="!$editMode" />
                <flux:input type="text" label="Twitter" wire:model="twitter" :disabled="!$editMode" />
                <flux:input type="text" label="Youtube" wire:model="youtube" :disabled="!$editMode" />
            </div>
            <div class="flex justify-end mt-4 space-x-2 items-center">
                @if($editMode)
                <flux:button type="button" wire:click="cancel">Batal</flux:button>
                <flux:button type="submit" icon="arrow-up-tray" variant="primary">Simpan</flux:button>
                @else
                <flux:button type="button" icon="pencil-square" wire:click="edit">Edit</flux:button>
                @endif
            </div>
        </form>
    </div>
</div>
