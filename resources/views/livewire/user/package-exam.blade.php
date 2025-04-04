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
        <div x-show="selectedTab == {{ $exam->type_id }}" class="p-4 border shadow-md rounded-lg w-full md:flex justify-start gap-5 items-start mb-5">
            <img src="{{ asset('storage/'. $exam->image) }}" alt="{{ $exam->name }}" class="w-full md:w-40 h-40 rounded-lg object-cover">
            <flux:separator vertical />
            <div class="flex flex-col flex-1 mt-3 md:mt-0 gap-2 md:h-40">
                <flux:heading size="xl" class="font-bold!">{{ $exam->name }}</flux:heading>
                <flux:text variant="subtle">
                    Deskripsi Soal :
                </flux:text>
                <flux:text class="overflow-hidden text-ellipsis line-clamp-3" variant="strong">{!! $exam->description !!}</flux:text>
                <div class="flex items-center mt-auto gap-2">
                    <flux:text variant="subtle">
                        Waktu Pengerjaan:
                    </flux:text>
                    <div class="flex items-center gap-2 font-bold">
                        <flux:icon.clock variant="mini"></flux:icon.clock>{{ $exam->time }} Menit
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-start md:justify-between gap-3 md:gap-0 md:w-1/4 md:h-40 mt-3 md:mt-0">
                <div class="flex items-center gap-2 mb-3">
                    <flux:text variant="subtle">
                        Status Soal:
                    </flux:text>
                    <flux:badge size="lg" class="px-5 gap-3" color="{{ $exam->is_active ? 'emerald' : 'red' }}">
                        {{ $exam->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        @if($exam->is_active)
                        <flux:icon.lock-open variant="mini"></flux:icon.lock-open>
                        @else
                        <flux:icon.lock-closed variant="mini"></flux:icon.lock-closed>
                        @endif
                    </flux:badge>
                </div>
                @if($exam->is_active)
                <flux:button icon="pencil-square" icon-trailing="arrow-right" variant="primary">Mulai Ujian</flux:button>
                @endif
            </div>
        </div>
        @endforeach
    </flux:container>
</div>
