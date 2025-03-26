<?php

use Livewire\Volt\Component;
use App\Models\Type;
use App\Models\Section;

new class extends Component {
    public $typeId;
    public $typeName;
    public $typeDescription;
    public $typePassingScore;

    public $modal = [
        'editType' => false,
        'deleteType' => false,
        'createSection' => false,
        'editSection' => false,
        'deleteSection' => false
    ];

    public $sectionId = null;
    public $sectionName = "";
    public $sectionDescription = "";
    public $sectionPassingScore = 0;
    public $sectionScoringType = "right_or_wrong";
    public $sectionRightAnswerPoint = null;
    public $sectionWrongAnswerPoint = null;
    public $sectionTotalOptions = 5;

    public $search = '';
    public $perPage = 10;

    public function mount(Type $type)
    {
        $this->typeId = $type->id;
        $this->typeName = $type->name;
        $this->typeDescription = $type->description;
        $this->typePassingScore = $type->passing_score;
    }
    
    public function openModal($modal, $id = null) {
        if($modal == 'deleteSection') {
            $this->sectionId = $id;
        } else if($modal == 'editSection') {
            $section = Section::find($id);
            $this->sectionId = $section->id;
            $this->sectionName = $section->name;
            $this->sectionDescription = $section->description;
            $this->sectionPassingScore = $section->passing_score;
            $this->sectionScoringType = $section->scoring_type;
            $this->sectionRightAnswerPoint = $section->right_answer_point;
            $this->sectionWrongAnswerPoint = $section->wrong_answer_point;
            $this->sectionTotalOptions = $section->total_options;
        }
        $this->modal[$modal] = true;
    }

    public function closeModal($modal) {
        $this->modal[$modal] = false;
    }

    public function deleteType(){
        Type::where('id', $this->typeId)->delete();
        $this->closeModal('deleteType');
        session()->flash('showToast', ['status' => 'success', 'message' => 'Jenis Ujian berhasil dihapus.']);
        $this->redirectRoute('dashboard.exam-type');
    }

    public function updateType(){
         $this->validate([
            'typeName' => 'required',
            'typeDescription' => 'nullable',
            'typePassingScore' => 'required|numeric',
         ]);

         Type::where('id', $this->typeId)->update([
            'name' => $this->typeName,
            'description'=> $this->typeDescription,
            'passing_score' => $this->typePassingScore,
        ]);

        $this->closeModal('editType');
        $this->dispatch('showToast', 'success', 'Jenis Ujian berhasil diperbarui.');
    }

    public function getSections(){
         $query = Section::query();

         if ($this->search) {
                $query = $query
                ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')->orWhere('description', 'like', '%' . $this->search . '%');
            });
         }

         $query = $query->where('type_id', $this->typeId)->latest();

         return $query->paginate($this->perPage);
    }

     public function with() : array {
        return [
            'sections' => $this->getSections(),
        ];
     }

     public function storeSection(){
        $this->validate([
            'sectionName' => 'required',
            'sectionDescription' => 'nullable',
            'sectionPassingScore' => 'required|numeric',
            'sectionScoringType' => 'required',
            'sectionRightAnswerPoint' => 'required_if:sectionScoringType,right_or_wrong',
            'sectionWrongAnswerPoint' => 'required_if:sectionScoringType,right_or_wrong',
            'sectionTotalOptions' => 'required|numeric',
        ]);

        Section::create([
            'type_id' => $this->typeId,
            'name' => $this->sectionName,
            'description' => $this->sectionDescription,
            'passing_score' => $this->sectionPassingScore,
            'scoring_type' => $this->sectionScoringType,
            'right_answer_point' => $this->sectionScoringType == "right_or_wrong" ? $this->sectionRightAnswerPoint : null,
            'wrong_answer_point' => $this->sectionScoringType == "right_or_wrong" ? $this->sectionWrongAnswerPoint : null,
            'total_options' => $this->sectionTotalOptions
     ]);

        $this->reset([
            'sectionName',
            'sectionDescription',
            'sectionPassingScore',
            'sectionScoringType',
            'sectionRightAnswerPoint',
            'sectionWrongAnswerPoint',
            'sectionTotalOptions',
        ]);
        $this->closeModal('createSection');
        $this->dispatch('showToast', 'success', 'Section berhasil ditambahkan.');
     }

     public function deleteSection(){
        Section::where('id', $this->sectionId)->delete();
        $this->closeModal('deleteSection');
        $this->dispatch('showToast', 'success', 'Section berhasil dihapus.');
     }

     public function updateSection(){
        $this->validate([
            'sectionName' => 'required',
            'sectionDescription' => 'nullable',
            'sectionPassingScore' => 'required|numeric',
            'sectionScoringType' => 'required',
            'sectionRightAnswerPoint' => 'required_if:sectionScoringType,right_or_wrong',
            'sectionWrongAnswerPoint' => 'required_if:sectionScoringType,right_or_wrong',
            'sectionTotalOptions' => 'required|numeric',
        ]);

        Section::where('id', $this->sectionId)->update([
            'name' => $this->sectionName,
            'description'=> $this->sectionDescription,
            'passing_score' => $this->sectionPassingScore,
            'scoring_type' => $this->sectionScoringType,
            'right_answer_point' => $this->sectionScoringType == "right_or_wrong" ? $this->sectionRightAnswerPoint : null,
            'wrong_answer_point' => $this->sectionScoringType == "right_or_wrong" ? $this->sectionWrongAnswerPoint : null,
            'total_options' => $this->sectionTotalOptions
        ]);

        $this->closeModal('editSection');
        $this->dispatch('showToast', 'success', 'Section berhasil diperbarui.');
     }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('dashboard.exam-type') }}">Jenis Ujian</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Detail Jenis Ujian</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Detail Jenis Ujian</h2>
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down" size="sm">Aksi</flux:button>
                <flux:menu>
                    <flux:menu.item wire:click="openModal('editType')">Edit</flux:menu.item>
                    <flux:menu.item variant="danger" wire:click="openModal('deleteType')">Hapus</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
            <div>
                <flux:heading>Jenis Ujian</flux:heading>
                <flux:text class="mt-2">{{ $typeName }}</flux:text>
            </div>
            <div>
                <flux:heading>Deskripsi</flux:heading>
                <flux:text class="mt-2">{!! strip_tags($typeDescription) == '' ? '-' : $typeDescription !!}</flux:text>
            </div>
            <div>
                <flux:heading>Nilai Kelulusan</flux:heading>
                <flux:text class="mt-2">{{ $typePassingScore }}</flux:text>
            </div>
        </div>
    </div>

    <flux:separator />

    <div class="flex justify-end items-center">
        <flux:button type="button" variant="primary" wire:click="openModal('createSection')">Tambah Bagian Ujian</flux:button>
    </div>
    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Daftar Bagian Ujian</h2>
            <div class="flex items-center">
                <flux:input type="search" wire:model.live.debounce.250ms="search" placeholder="Cari..." class="mr-2" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Bagian Ujian
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Deskripsi
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Nilai Kelulusan
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Cara Penilaian
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Banyak Opsi
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($sections as $section)
                    <tr wire:key="section-{{ $section->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $section->name }}</td>
                        <td class="px-6 py-4">{!! strip_tags($section->description) == '' ? '-' : $section->description !!}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $section->passing_score }}</td>
                        <td class="px-6 py-4">
                            @if($section->scoring_type == "right_or_wrong")
                            Benar/Salah (Benar = {{ $section->right_answer_point }}, Salah = {{ $section->wrong_answer_point }})
                            @elseif($section->scoring_type == "point")
                            Poin
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $section->total_options }}</td>
                        <td class="px-6 py-4 text-right">
                            <flux:button type="button" wire:click="openModal('editSection', {{ $section->id }})" size="xs">Edit
                            </flux:button>
                            <flux:button type="button" wire:click="openModal('deleteSection', {{ $section->id }})" variant="danger" size="xs">
                                Hapus</flux:button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap">
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data
                                tersedia</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $sections->links() }}
        </div>
    </div>

    {{-- modal hapus type --}}
    <!-- Modal Konfirmasi Delete -->
    <flux:modal wire:model="modal.deleteType" class="min-w-sm">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">Hapus Jenis Ujian?</flux:heading>
                <flux:subheading>
                    <p>Apakah Anda yakin ingin menghapus jenis ujian ini.</p>
                    <p>Semua data yang berkaitan dengan jenis ujian ini akan dihapus.</p>
                </flux:subheading>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deleteType">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- modal edit --}}
    <flux:modal wire:model="modal.editType" class="min-w-sm md:min-w-xl space-y-4">
        <flux:heading size="lg">Tambah Jenis Ujian</flux:heading>
        <form wire:submit="updateType">
            <div class="space-y-4">
                <flux:input label="Jenis Ujian" wire:model="typeName" />
                <livewire:plugin.text-editor label="Deskripsi" wire:model="typeDescription" size="xs" />
                <flux:input type="number" label="Nilai Kelulusan" wire:model="typePassingScore" />
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

    {{-- modal tambah bagian ujian --}}
    <flux:modal wire:model="modal.createSection" class="min-w-sm md:min-w-xl space-y-4">
        <flux:heading size="lg">Tambah Bagian Ujian</flux:heading>
        <form wire:submit="storeSection">
            <div class="space-y-4">
                <flux:input label="Jenis Ujian" wire:model="sectionName" />
                <livewire:plugin.text-editor label="Deskripsi" wire:model="sectionDescription" size="xs" />
                <flux:input type="number" label="Nilai Kelulusan" wire:model="sectionPassingScore" />
                <flux:select label="Cara Penilaian" wire:model.live="sectionScoringType" placeholder="Pilih cara penilaian...">
                    <flux:select.option value="right_or_wrong">Benar/Salah</flux:select.option>
                    <flux:select.option value="point">Point</flux:select.option>
                </flux:select>
                @if($sectionScoringType == "right_or_wrong")
                <flux:input type="number" label="Nilai Benar" wire:model="sectionRightAnswerPoint" />
                <flux:input type="number" label="Nilai Salah" wire:model="sectionWrongAnswerPoint" />
                @endif
                <flux:select label="Banyak Opsi" wire:model="sectionTotalOptions" placeholder="Pilih banyak opsi jawaban...">
                    <flux:select.option value="3">3</flux:select.option>
                    <flux:select.option value="4">4</flux:select.option>
                    <flux:select.option value="5">5</flux:select.option>
                    <flux:select.option value="6">6</flux:select.option>
                </flux:select>
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

    {{-- modal edit bagian ujian --}}
    <flux:modal wire:model="modal.editSection" class="min-w-sm md:min-w-xl space-y-4">
        <flux:heading size="lg">Edit Bagian Ujian</flux:heading>
        <form wire:submit="updateSection">
            <div class="space-y-4">
                <flux:input label="Jenis Ujian" wire:model="sectionName" />
                <livewire:plugin.text-editor label="Deskripsi" wire:model="sectionDescription" size="xs" />
                <flux:input type="number" label="Nilai Kelulusan" wire:model="sectionPassingScore" />
                <flux:select label="Cara Penilaian" wire:model.live="sectionScoringType" placeholder="Pilih cara penilaian...">
                    <flux:select.option value="right_or_wrong">Benar/Salah</flux:select.option>
                    <flux:select.option value="point">Point</flux:select.option>
                </flux:select>
                @if($sectionScoringType == "right_or_wrong")
                <flux:input type="number" label="Nilai Benar" wire:model="sectionRightAnswerPoint" />
                <flux:input type="number" label="Nilai Salah" wire:model="sectionWrongAnswerPoint" />
                @endif
                <flux:select label="Banyak Opsi" wire:model="sectionTotalOptions" placeholder="Pilih banyak opsi jawaban...">
                    <flux:select.option value="3">3</flux:select.option>
                    <flux:select.option value="4">4</flux:select.option>
                    <flux:select.option value="5">5</flux:select.option>
                    <flux:select.option value="6">6</flux:select.option>
                </flux:select>
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

    {{-- modal hapus bagian ujian --}}
    <flux:modal wire:model="modal.deleteSection" class="min-w-sm">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">Hapus Bagian Ujian?</flux:heading>
                <flux:subheading>
                    <p>Apakah Anda yakin ingin menghapus bagian ujian ini.</p>
                    <p>Semua data yang berkaitan dengan bagian ujian ini akan dihapus.</p>
                </flux:subheading>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deleteSection">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>
</div>