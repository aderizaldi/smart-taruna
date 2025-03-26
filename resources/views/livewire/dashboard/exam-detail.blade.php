<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('dashboard.exam') }}">Soal Ujian</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Detail Soal Ujian</flux:breadcrumbs.item>
    </flux:breadcrumbs>
</div>