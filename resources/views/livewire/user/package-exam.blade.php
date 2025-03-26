<?php 
use Livewire\Volt\Component;
use App\Models\Type;

new class extends Component {
    public $selectedTab; 

    public function mount()
    {
        $this->selectedTab = Type::first()?->id ?? null;
    }

    public function getTypes()
    {
        return Type::all();
    }

    public function with(): array {
        return [
            'types' => $this->getTypes(),
            'selectedTab' => $this->selectedTab,
        ];
    }
};
?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" x-data="{ selectedTab: @entangle('selectedTab') }">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Paket Soal</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Tab Navigation --}}
    <flux:container class="mt-5!">
        <flux:radio.group x-model="selectedTab" label="Pilih Ujian Anda" variant="segmented">
            @foreach ($types as $type)
            <flux:radio value="{{ $type->id }}" label="{{ $type->name }}" />
            @endforeach
        </flux:radio.group>
    </flux:container>

    {{-- Tab Panel --}}
    <flux:container>
        @foreach ($types as $type)
        <div x-show="selectedTab == {{ $type->id }}" class="p-4 border rounded-lg bg-white">
            <h2 class="text-lg font-bold text-gray-700">{{ $type->name }}</h2>
        </div>
        @endforeach
    </flux:container>
</div>
