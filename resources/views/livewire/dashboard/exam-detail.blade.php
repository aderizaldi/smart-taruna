<?php

use Livewire\Volt\Component;
use App\Models\Exam;
use App\Models\Package;
use App\Models\Type;
use App\Models\Section;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $exam;
    public $examId;
    public $examType;
    public $examTypeId;
    public $examPackage;
    public $examPackageId;
    public $examName;
    public $examDescription;
    public $examImage;
    public $examIsActive;
    public $examTime;

    public $packages;
    public $types;

    public $sections;
    public $selectedSection;

    public $modal =[
        'editExam' => false,
        'deleteExam' => false
    ];

    public function mount(Exam $exam)
    {
        $this->exam = $exam;
        $this->examId = $exam->id;
        $this->examType = $exam->type->name;
        $this->examTypeId = $exam->type->id;
        $this->examPackage = $exam->package->name;
        $this->examPackageId = $exam->package->id;
        $this->examName = $exam->name;
        $this->examDescription = $exam->description;
        $this->examImage = $exam->image;
        $this->examIsActive = $exam->is_active;
        $this->examTime = $exam->time;

        $this->packages = Package::latest()->get();
        $this->types = Type::latest()->get();

        $this->sections = $exam->type->sections;
        $this->selectedSection = $exam->type->sections->first();
    }

    public function updateStatus() {
        Exam::find($this->examId)->update([
            "is_active" => $this->examIsActive
        ]);

        $this->dispatch('showToast', 'success', 'Status ujian berhasil' . ($this->examIsActive ? ' diaktifkan' : ' dinonaktifkan') . '.');
    }

    public function openModal($modal)
    {
        $this->modal[$modal] = true;
    }

    public function closeModal($modal)
    {
        $this->modal[$modal] = false;
    }

    public function resetFormExam() {
        $this->examPackageId = $this->exam->package_id;
        $this->examName = $this->exam->name;
        $this->examDescription = $this->exam->description;
        $this->examImage = $this->exam->image;
        $this->examTime = $this->exam->time;
        $this->dispatch('resetEditor', $this->examDescription);
    }

    public function updateExam() {
        $this->validate([
            'examPackageId' => 'required|exists:packages,id',
            'examName' => 'required',
            'examDescription' => 'nullable',
            'examTime' => 'required',
            'examImage' => $this->examImage instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile ? 'image|max:2048' : '',
        ]);

        $exam = Exam::find($this->examId);
        $examImage = $this->examImage;

        if($this->examImage instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile){
            if($exam->image){
                Storage::delete($exam->image);
            }
            $examImage = save_as_webp($this->examImage, 'image/exam/');
        }

        $exam->update([
            "package_id" => $this->examPackageId,
            "name" => $this->examName,
            "description" => $this->examDescription,
            "image" => $examImage,
            "time" => $this->examTime
        ]);

        $this->examPackage = Package::find($this->examPackageId)->name;

        $this->closeModal('editExam');
        $this->dispatch('showToast', 'success', 'Soal Ujian berhasil diperbarui.');
    }

    public function deleteExam()
    {
        $exam = Exam::find($this->examId);
        if($exam->image){
            Storage::delete($exam->image);
        }
        $exam->delete();
         $this->closeModal('deleteExam');
         session()->flash('showToast', ['status' => 'success', 'message' => 'Soal Ujian berhasil dihapus.']);
         $this->redirectRoute('dashboard.exam');
    }

    public function selectSection($sectionId)
    {
        $this->selectedSection = Section::find($sectionId);
    }

    public function removeImageExam() {
        $this->examImage = null;
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('dashboard.exam') }}">Soal Ujian</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Detail Soal Ujian</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Detail Soal Ujian</h2>
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down" size="sm">Aksi</flux:button>
                <flux:menu>
                    <flux:menu.item wire:click="openModal('editExam')">Edit</flux:menu.item>
                    <flux:menu.item variant="danger" wire:click="openModal('deleteExam')">Hapus</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
            <div>
                <flux:heading>Jenis Ujian</flux:heading>
                <flux:text class="mt-2">{{ $exam->type->name }}</flux:text>
            </div>
            <div>
                <flux:heading>Paket</flux:heading>
                <flux:text class="mt-2">{{ $exam->package->name }}</flux:text>
            </div>
            <div>
                <flux:heading>Nama Ujian</flux:heading>
                <flux:text class="mt-2">{{ $exam->name }}</flux:text>
            </div>
            <div>
                <flux:heading>Deskripsi</flux:heading>
                <flux:text class="mt-2">{!! strip_tags($exam->description) == '' ? '-' : $exam->description !!}</flux:text>
            </div>
            <div>
                <flux:heading>Waktu Ujian</flux:heading>
                <flux:text class="mt-2">{{ $exam->time }} menit</flux:text>
            </div>
            <div>
                <flux:heading>Gambar</flux:heading>
                <flux:text class="mt-2">
                    @if($exam->image)
                    <a href="{{ asset('storage/' . $exam->image) }}" target="_blank" class="block size-fit">
                        <img src="{{ asset('storage/' . $exam->image) }}" alt="image" class="h-24 w-24 object-cover">
                    </a>
                    @else
                    -
                    @endif
                </flux:text>
            </div>
            <div>
                <flux:heading>Status Ujian</flux:heading>
                <flux:select wire:model.live="examIsActive" wire:change="updateStatus" placeholder="Pilih status ujian..." size="sm" class="mt-2">
                    <flux:select.option value="1">Aktif</flux:select.option>
                    <flux:select.option value="0">Tidak Aktif</flux:select.option>
                </flux:select>
            </div>
        </div>
    </div>

    @if($sections->count() > 0)
    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <div>
            <ul class="flex items-center gap-2 text-sm font-medium">
                @foreach($sections as $section)
                <li class="flex-1">
                    <a href="#" class="flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-gray-700 hover:text-gray-700 {{ $section->id == $selectedSection->id ? 'bg-gray-200' : 'hover:bg-gray-100' }}" wire:click="selectSection({{ $section->id }})">
                        {{ $section->name }}
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-500"> {{ $section->questions->count() }} </span></a>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="flex flex-col justify-between items-center mb-4 mt-4 gap-2">
            <h2 class="text-lg font-semibold">Soal {{ $selectedSection->name }}</h2>
            <flux:button type="button" variant="primary" wire:click="openModal('addQuestion')">Tambah Soal</flux:button>
        </div>
    </div>
    @else
    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <flux:text class="mt-2 text-center text-red-600 font-bold">Tidak bisa menambahkan soal.</flux:text>
        <flux:text class="mt-2 text-center">Harap menambahkan bagian ujian pada jenis ujian ini terlebih dahulu <flux:link href="{{ route('dashboard.exam-type-detail', $examTypeId) }}">disini</flux:link>.</flux:text>
    </div>
    @endif

    {{-- modal edit soal ujian --}}
    <flux:modal wire:model="modal.editExam" class="min-w-sm md:min-w-xl space-y-4" @close="resetFormExam" @cancel="resetFormExam">
        <flux:heading size="lg">Tambah Soal Ujian</flux:heading>
        <form wire:submit="updateExam">
            <div class="space-y-4">
                <flux:select label="Jenis Soal" wire:model="examTypeId" placeholder="Pilih jenis soal..." disabled>
                    @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </flux:select>
                <flux:select label="Paket Ujian" wire:model="examPackageId" placeholder="Pilih paket ujian...">
                    @foreach($packages as $package)
                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                    @endforeach
                </flux:select>
                <flux:input label="Nama Soal Ujian" wire:model="examName" />
                <livewire:plugin.text-editor label="Deskripsi" wire:model="examDescription" size="xs" />
                <flux:field>
                    <flux:label>Waktu</flux:label>
                    <flux:input.group>
                        <flux:input wire:model="examTime" />
                        <flux:input.group.suffix>Menit</flux:input.group.suffix>
                    </flux:input.group>
                    <flux:error name="examTime" />
                </flux:field>
                <flux:field>
                    <flux:label>Gambar</flux:label>
                    @if($examImage)
                    <div class="flex gap-2 items-center">
                        <img src="{{ is_string($examImage) ? asset('storage/' . $examImage) : $examImage->temporaryUrl() }}" alt="{{ $examName }}" class="w-16 h-16 object-cover rounded-lg">
                        <flux:button type="button" variant="danger" wire:click="removeImageExam" size="xs">Hapus Gambar</flux:button>
                    </div>
                    @endif
                    <flux:input type="file" wire:model="examImage" class="overflow-hidden" accept="image/*" description:trailing="Gambar maksimal 2MB" />
                    <flux:error name="image" />
                </flux:field>
            </div>
            <div class="flex gap-2 mt-4">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Simpan</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- modal delete soal ujian --}}
    <flux:modal wire:model="modal.deleteExam" class="min-w-sm">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">Hapus Soal Ujian?</flux:heading>
                <flux:subheading>
                    <p>Apakah Anda yakin ingin menghapus soal ujian ini.</p>
                    <p>Semua data yang berkaitan dengan soal ujian ini akan dihapus.</p>
                </flux:subheading>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deleteExam">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>
</div>