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
        $this->dispatch("editModeChange", $this->editMode); 
    }

    public function cancel()
    {
        $this->editMode = false;
        $this->dispatch("editModeChange", $this->editMode);
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
        $this->dispatch("editModeChange", $this->editMode); 
    }

    public function with(): array {
        return [
            'edit_mode' => $this->editMode,
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
                <div class="mb-2">Jargon</div>
                <div class="summernote mt-2" id="summer-note">{!! $quote !!}</div>
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
            <div class="flex justify-end mt-4 space-x-2">
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

@script
<script>
    $(function() {
        $('#summer-note').summernote({
            placeholder: 'Masukkan Jargon Anda'
            , focus: false
            , tabsize: 2
            , height: 150
            , styleWithCSS: true
            , disableDragAndDrop: true
            , callbacks: {
                onChange: function(contents, $editable) {
                    // Terapkan class Tailwind secara manual
                    $('#summer-note').next().find('h1').addClass('text-5xl! font-light!');
                    $('#summer-note').next().find('h2').addClass('text-4xl! font-light!');
                    $('#summer-note').next().find('h3').addClass('text-2xl! font-light!');
                    $('#summer-note').next().find('p').addClass('text-base! font-light!');
                    // Quote (blockquote)
                    $('#summer-note').next().find('blockquote').addClass('border-l-4 border-gray-500 pl-4 italic text-gray-600');
                    // Code
                    $('#summer-note').next().find('code').addClass('bg-gray-200 text-red-600 px-1 rounded-md font-mono');
                    // Unordered List (ul)
                    $('#summer-note').next().find('ul').addClass('list-disc list-inside text-gray-800');
                    // Ordered List (ol)
                    $('#summer-note').next().find('ol').addClass('list-decimal list-inside text-gray-800');
                }
                , onBlur: function(contents, $editable) {
                    @this.set('quote', $('#summer-note').summernote('code'));
                }
            }
        });
        $('#summer-note').summernote('disable');
        $wire.on('editModeChange', (event) => {
            if (event[0]) {
                $('#summer-note').summernote('enable');
            } else {
                $('#summer-note').summernote('disable');
            }
        });
    });

</script>
@endscript
