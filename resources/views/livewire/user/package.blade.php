<?php 
use Livewire\Volt\Component;
use App\Models\Package;

new class extends Component {

    public function getPackages()
    {
        return Package::all();
    }


    public function with(): array {
        return [
            'packages' => $this->getPackages(),
        ];
    }

};
?>

<div class="flex w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Paket Pembelajaran</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:container class="bg-gray-100 w-full h-[35vh] md:h-[30vh] rounded-lg grid md:grid-cols-2">
        <flux:container class="h-full flex flex-col justify-center items-center order-last md:order-first text-center">
            <p class="font-bold md:text-2xl">Paket Pembelajaran</p>
            <p class="font-bold md:text-2xl">SMART TARUNA</p>
            <p>Silahkan pilih paket pembelajaran anda.</p>
        </flux:container>
        <flux:container class="flex justify-center items-center h-full order-first md:order-second">
            <img src="{{ asset('assets/smart_taruna_logo.png') }}" alt="" class="object-fill h-30 w-25 md:h-40  md:w-35">
        </flux:container>
    </flux:container>

    <flux:container class="grid md:grid-cols-4 gap-3 justify-items-center">
        @foreach ($packages as $package)
        <div class="w-4/5 md:w-4/5 h-[45vh] bg-white rounded-xl border shadow-md flex flex-col hover:bg-gray-100 transition-all duration-300 ease-in-out">
            <img src="{{ $package->image ? asset('storage/'. $package->image) : asset('assets/default.png') }}" alt="{{ $package->name }}" class="object-cover w-full h-1/2 rounded-t-xl">
            <div class="px-3 py-2 text-center flex flex-col gap-3 h-full justify-between">
                <p class="font-bold text-lg text-center">{{ $package->name }}</p>
                <div class="overflow-hidden text-ellipsis line-clamp-2">
                    {!! $package->description !!}
                </div>
                <flux:button class="w-full bg-[#20327A]! rounded-lg! text-white! hover:bg-[#4054A5]! transition-all duration-300 ease-in-out" :href="route('user.package-exam', ['packageId' => $package->id])" wire:navigate>Mulai</flux:button>
            </div>
        </div>
        @endforeach
    </flux:container>

</div>
