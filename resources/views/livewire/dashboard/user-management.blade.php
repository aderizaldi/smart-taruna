<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

new class extends Component {
    use WithPagination, withoutUrlPagination;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'user';
    public $editMode = false;
    public $userId = null;
    public $search = '';
    public $perPage = 10;
    public $confirmingUserDeletion = false;

    // Method ini sekarang mengembalikan hasil query untuk digunakan di template
    public function getUsers()
    {
        $query = User::query();

        if ($this->search) {
            $query = $query
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%');
                });
        }

        $query = $query->where('id', '!=', auth()->user()->id)->latest();

        return $query->paginate($this->perPage);
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'user';
        $this->editMode = false;
        $this->userId = null;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => ['confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole($this->role);

        $this->resetForm();
        $this->dispatch('updated');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->getRoleNames()[0] ?? 'user';
        $this->editMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'password' => $this->password ? ['confirmed', Password::defaults()] : '',
        ]);

        $user = User::findOrFail($this->userId);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        $user->removeRole($user->getRoleNames()[0]);
        $user->assignRole($this->role);

        $this->resetForm();
        $this->dispatch('updated');
    }

    public function confirmDelete($id)
    {
        $this->userId = $id;
        $this->confirmingUserDeletion = true;
    }

    public function delete()
    {
        $user = User::findOrFail($this->userId);
        $user->delete();

        $this->confirmingUserDeletion = false;
        $this->resetForm();
    }

    public function with(): array
    {
        return [
            'users' => $this->getUsers(),
        ];
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- Form untuk Create dan Edit -->
    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <h2 class="text-lg font-semibold mb-4">{{ $editMode ? 'Edit User' : 'Tambah User Baru' }}</h2>

        <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input type="text" label="Nama" wire:model="name" />
                <flux:input type="email" label="Email" wire:model="email" />
                <flux:input type="password" label="Password" wire:model="password" />
                <flux:input type="password" label="Konfirmasi Password" wire:model="password_confirmation" />
                <flux:select wire:model="role" placeholder="Pilih Role" label="Role">
                    <flux:select.option value="user">User</flux:select.option>
                    <flux:select.option value="admin">Admin</flux:select.option>
                </flux:select>
            </div>

            <div class="flex justify-end mt-4 space-x-2 items-center">

                <x-action-message class="me-3" on="updated">
                    {{ __('Saved.') }}
                </x-action-message>

                <flux:button type="button" wire:click="resetForm">{{ $editMode ? 'Batal' : 'Reset' }}</flux:button>
                <flux:button type="submit" variant="primary">{{ $editMode ? 'Perbarui' : 'Simpan' }}</flux:button>
            </div>
        </form>
    </div>

    <!-- Tabel Users -->
    <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Daftar Users</h2>
            <div class="flex items-center">
                <flux:input type="search" wire:model.live.debounce.250ms="search" placeholder="Cari..." class="mr-2" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Nama
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider">Role
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($users as $user)
                    <tr wire:key="user-{{ $user->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->getRoleNames()[0] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <flux:button type="button" wire:click="edit({{ $user->id }})" size="xs">Edit
                            </flux:button>
                            <flux:button type="button" wire:click="confirmDelete({{ $user->id }})" variant="danger"
                                size="xs">
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
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <flux:modal wire:model="confirmingUserDeletion" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus User?</flux:heading>

                <flux:subheading>
                    <p>Apakah Anda yakin ingin menghapus user ini.</p>
                    <p>Semua data yang berkaitan dengan user ini akan dihapus.</p>
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
