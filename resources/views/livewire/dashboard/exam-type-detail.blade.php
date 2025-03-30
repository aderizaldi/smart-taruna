<?php

use Livewire\Volt\Component;
use App\Models\Type;
use App\Models\Section;

new class extends Component {
    public $type;
    public $typeId;
    public $typeName;
    public $typeDescription;
    public $typePassingScore;

    public $modal = [
        'editType' => false,
        'deleteType' => false,
        'create' => false,
        'edit' => false,
        'delete' => false
    ];

    public $id = null;
    public $name = "";
    public $description = "";
    public $passingScore = 0;
    public $scoringType = "right_or_wrong";
    public $rightAnswerPoint = null;
    public $wrongAnswerPoint = null;
    public $totalOptions = 5;

    public $search = '';
    public $perPage = 10;

    public function mount(Type $type)
    {
        $this->type = $type;
        $this->typeId = $type->id;
        $this->typeName = $type->name;
        $this->typeDescription = $type->description;
        $this->typePassingScore = $type->passing_score;
    }
    
    public function openModal($modal, $id = null) {
        if($modal == 'delete') {
            $this->id = $id;
        } else if($modal == 'edit') {
            $section = Section::find($id);
            $this->id = $section->id;
            $this->name = $section->name;
            $this->description = $section->description;
            $this->passingScore = $section->passing_score;
            $this->scoringType = $section->scoring_type;
            $this->rightAnswerPoint = $section->right_answer_point;
            $this->wrongAnswerPoint = $section->wrong_answer_point;
            $this->totalOptions = $section->total_options;
            $this->dispatch('resetEditor', $this->description);
        }
        $this->modal[$modal] = true;
    }

    public function closeModal($modal) {
        $this->modal[$modal] = false;
    }

    public function resetForm(){
        $this->reset([
            'name',
            'description',
            'passingScore',
            'scoringType',
            'rightAnswerPoint',
            'wrongAnswerPoint',
            'totalOptions',
        ]);
        $this->dispatch('resetEditor', $this->description);
    }

    public function resetFormType(){
        $this->typeName = $this->type->name;
        $this->typeDescription = $this->type->description;
        $this->typePassingScore = $this->type->passing_score;
        $this->dispatch('resetEditor', $this->typeDescription);
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

     public function store(){
        $this->validate([
            'name' => 'required',
            'description' => 'nullable',
            'passingScore' => 'required|numeric',
            'scoringType' => 'required',
            'rightAnswerPoint' => 'required_if:scoringType,right_or_wrong',
            'wrongAnswerPoint' => 'required_if:scoringType,right_or_wrong',
            'totalOptions' => 'required|numeric',
        ]);

        Section::create([
            'type_id' => $this->typeId,
            'name' => $this->name,
            'description' => $this->description,
            'passing_score' => $this->passingScore,
            'scoring_type' => $this->scoringType,
            'right_answer_point' => $this->scoringType == "right_or_wrong" ? $this->rightAnswerPoint : null,
            'wrong_answer_point' => $this->scoringType == "right_or_wrong" ? $this->wrongAnswerPoint : null,
            'total_options' => $this->totalOptions
     ]);

        $this->resetForm();
        $this->closeModal('create');
        $this->dispatch('showToast', 'success', 'Section berhasil ditambahkan.');
     }

     public function delete(){
        Section::where('id', $this->id)->delete();
        $this->closeModal('delete');
        $this->dispatch('showToast', 'success', 'Section berhasil dihapus.');
     }

     public function update(){
        $this->validate([
            'name' => 'required',
            'description' => 'nullable',
            'passingScore' => 'required|numeric', 
        ]);

        Section::where('id', $this->id)->update([
            'name' => $this->name,
            'description'=> $this->description,
            'passing_score' => $this->passingScore,
        ]);

        $this->resetForm();
        $this->closeModal('edit');
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
                <flux:text class="mt-2">{{ $type->name }}</flux:text>
            </div>
            <div>
                <flux:heading>Deskripsi</flux:heading>
                <flux:text class="mt-2">{!! strip_tags($type->description) == '' ? '-' : $type->description !!}</flux:text>
            </div>
            <div>
                <flux:heading>Nilai Kelulusan</flux:heading>
                <flux:text class="mt-2">{{ $type->passing_score }}</flux:text>
            </div>
        </div>
    </div>

    <flux:separator />

    <div class="flex justify-end items-center">
        <flux:button type="button" variant="primary" wire:click="openModal('create')">Tambah Bagian Ujian</flux:button>
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
                            <flux:button type="button" wire:click="openModal('edit', {{ $section->id }})" size="xs">Edit
                            </flux:button>
                            <flux:button type="button" wire:click="openModal('delete', {{ $section->id }})" variant="danger" size="xs">
                                Hapus</flux:button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap">
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
    <flux:modal wire:model="modal.editType" class="min-w-sm md:min-w-xl space-y-4" @close="resetFormType" @cancel="resetFormType">
        <flux:heading size="lg">Edit Jenis Ujian</flux:heading>
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
    <flux:modal wire:model="modal.create" class="min-w-sm md:min-w-xl space-y-4">
        <flux:heading size="lg">Tambah Bagian Ujian</flux:heading>
        <form wire:submit="store">
            <div class="space-y-4">
                <flux:input label="Jenis Ujian" wire:model="name" />
                <livewire:plugin.text-editor label="Deskripsi" wire:model="description" size="xs" />
                <flux:input type="number" label="Nilai Kelulusan" wire:model="passingScore" />
                <flux:select label="Cara Penilaian" wire:model.live="scoringType" placeholder="Pilih cara penilaian...">
                    <flux:select.option value="right_or_wrong">Benar/Salah</flux:select.option>
                    <flux:select.option value="point">Point</flux:select.option>
                </flux:select>
                @if($scoringType == "right_or_wrong")
                <flux:input type="number" label="Nilai Benar" wire:model="rightAnswerPoint" />
                <flux:input type="number" label="Nilai Salah" wire:model="wrongAnswerPoint" />
                @endif
                <flux:select label="Banyak Opsi" wire:model="totalOptions" placeholder="Pilih banyak opsi jawaban...">
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
    <flux:modal wire:model="modal.edit" class="min-w-sm md:min-w-xl space-y-4">
        <flux:heading size="lg">Edit Bagian Ujian</flux:heading>
        <form wire:submit="update">
            <div class="space-y-4">
                <flux:input label="Jenis Ujian" wire:model="name" />
                <livewire:plugin.text-editor label="Deskripsi" wire:model="description" size="xs" />
                <flux:input type="number" label="Nilai Kelulusan" wire:model="passingScore" />
                <flux:select label="Cara Penilaian" wire:model.live="scoringType" placeholder="Pilih cara penilaian..." disabled>
                    <flux:select.option value="right_or_wrong">Benar/Salah</flux:select.option>
                    <flux:select.option value="point">Point</flux:select.option>
                </flux:select>
                @if($scoringType == "right_or_wrong")
                <flux:input type="number" label="Nilai Benar" wire:model="rightAnswerPoint" disabled />
                <flux:input type="number" label="Nilai Salah" wire:model="wrongAnswerPoint" disabled />
                @endif
                <flux:select label="Banyak Opsi" wire:model="totalOptions" placeholder="Pilih banyak opsi jawaban..." disabled>
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
    <flux:modal wire:model="modal.delete" class="min-w-sm">
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
                <flux:button variant="danger" wire:click="delete">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>
</div>