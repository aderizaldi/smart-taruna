<?php 
use Livewire\Volt\Component;
use App\Models\Exam;
use App\Models\Type;

new class extends Component {
    public $selectedTab;
    public $packageId;

    public function mount($packageId)
    {
        $this->packageId = $packageId;
        $this->selectedTab = Exam::where('package_id', $this->packageId)->first()?->type_id ?? null;
    }

    public function getExams()
    {
        return Exam::where('package_id', $this->packageId)->get();
    }

    public function getTypes()
    {
        $exam = $this->getExams();
        $uniqueTypeIds = $exam->pluck('type_id')->unique();
        $uniqueTypeIdsArray = $uniqueTypeIds->values()->toArray();
        
        return Type::whereIn('id', $uniqueTypeIdsArray)->get();
    }

    public function with(): array {
        return [
            'exams' => $this->getExams(),
            'types' => $this->getTypes(),
            'selectedTab' => $this->selectedTab,
        ];
    }
};
?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" x-data="{ selectedTab: @entangle('selectedTab') }">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('user.package') }}">Paket Pembelajaran</flux:breadcrumbs.item>
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
        @foreach ($exams as $exam)
        <div x-show="selectedTab == {{ $exam->type_id }}" class="p-4 border rounded-lg bg-white">
            <h2 class="text-lg font-bold text-gray-700">{{ $exam->name }}</h2>
        </div>
        @endforeach
    </flux:container>
</div>
