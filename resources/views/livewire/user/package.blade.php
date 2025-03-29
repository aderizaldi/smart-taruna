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

<div class="flex h-screen w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Paket Pembelajaran</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:container class="bg-gray-100 w-full h-1/3 rounded-lg grid md:grid-cols-2">
        <flux:container class="h-full flex flex-col justify-center items-center order-last md:order-first">
            <p class="font-bold md:text-2xl">Paket Pembelajaran</p>
            <p class="font-bold md:text-2xl">SMART TARUNA</p>
            <p>Silahkan pilih paket pembelajaran anda.</p>
        </flux:container>
        <flux:container class="flex justify-center items-center h-full order-first md:order-second">
            <img src="{{ asset('assets/smart_taruna_logo.png') }}" alt="" class="object-fill h-30 w-25 md:h-40  md:w-35">
        </flux:container>
    </flux:container>

    <flux:container class="mt-10 w-full relative flex justify-center items-center py-3">
        @foreach ($packages as $package)
        <div class="w-4/5 h-[45vh] bg-white rounded-xl border shadow-md p-5 flex flex-col items-center justify-between ms-10">
            <p class="font-bold text-lg text-center">{{ $package->name }}</p>
            <p class="text-sm text-center">{{ $package->description }}</p>
            <flux:button class="w-full bg-[#20327A]! rounded-lg! text-white!">Mulai</flux:button>
        </div>
        @endforeach
    </flux:container>

</div>
