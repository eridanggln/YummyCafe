@extends('layout.layout')

@section('title', 'Kelola Akun')

@section('header')
    <div class="flex justify-between items-center w-full">
        <span>Cafe Yummy</span>

        <div x-data="{ userOpen: false }" class="relative inline-block text-left">
            <button @click="userOpen = !userOpen" @click.away="userOpen = false"
                class="flex items-center focus:outline-none group">
                <div class="text-right mr-3 hidden md:block">
                    <p class="text-xs font-bold text-red-800 uppercase leading-none">{{ Auth::user()->nama }}</p>
                    <p class="text-[10px] text-red-700 font-medium leading-none">{{ Auth::user()->role }}</p>
                </div>

                <div
                    class="bg-red-800 p-2 rounded-full shadow-md group-hover:bg-red-900 transition flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </button>

            <div x-show="userOpen" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100" @click.away="userOpen = false"
                class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-xl border border-gray-200 p-0 z-50 overflow-hidden">

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-3 px-5 py-3 text-sm text-red-600 hover:bg-red-50 font-bold transition-colors w-full whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div>
        <div class="bg-white rounded-xl p-6 max-w-7xl mx-auto shadow h-[80vh] flex flex-col">
            <h1 class="text-center text-red-800 text-2xl font-bold mb-8">
                DAFTAR AKUN
            </h1>

            <div class="flex justify-between items-center mb-6">
                <!-- KIRI: TOMBOL -->
                <a href="{{ route('user.create') }}" class="bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800">
                    + Tambah Akun
                </a>

                <!-- KANAN: SEARCH -->
                <input type="text" id="searchInput" placeholder="Cari akun..."
                    class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>

            <!-- TABLE -->
            <div class="bg-white rounded-xl shadow overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-center">No</th>
                            <th class="border px-4 py-2">Role</th>
                            <th class="border px-4 py-2">Nama</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2 text-center">Status</th>
                            <th class="border px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="userTableBody">
                        @forelse ($users as $index => $user)
                            <tr>
                                <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                                <td class="border px-4 py-2">{{ ucfirst($user->role) }}</td>
                                <td class="border px-4 py-2">{{ $user->nama }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>

                                <td class="border px-4 py-2 text-center">
                                    @if($user->is_active)
                                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700">Aktif</span>
                                    @else
                                        <span class="px-3 py-1 text-sm rounded-full bg-gray-200 text-gray-600">Nonaktif</span>
                                    @endif
                                </td>

                                <td class="border px-4 py-2 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        {{-- TOMBOL EDIT --}}
                                        <button
                                            onclick="openEditModal({{ $user->id_user }}, '{{ $user->nama }}', '{{ $user->email }}', '{{ $user->role }}')"
                                            class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 transition-colors">
                                            Edit
                                        </button>

                                        <span class="text-gray-400">|</span>

                                        {{-- TOMBOL TOGGLE STATUS --}}
                                        <form action="{{ route('user.toggle', $user->id_user) }}" method="POST"
                                            class="flex items-center m-0"
                                            onsubmit="return confirmToggle(event, '{{ $user->is_active ? 'nonaktifkan' : 'aktifkan' }}', '{{ $user->nama }}')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="w-[120px] {{ $user->is_active ? 'bg-red-600' : 'bg-green-600' }} text-white px-3 py-1 rounded transition-colors">
                                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl w-full max-w-xl p-6 relative">
            <h2 class="text-xl font-semibold mb-4">Edit Akun</h2>

            <form method="POST" action="{{ route('user.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <input type="hidden" name="id_user" id="edit_id" required>

                <div>
                    <label class="block font-semibold">Nama</label>
                    <input type="text" name="nama" id="edit_nama" required class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="block font-semibold">Email</label>
                    <input type="email" name="email" id="edit_email" required class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="block font-semibold">Role</label>
                    <select name="role" id="edit_role" required class="w-full border rounded-lg p-2">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="kasir">Kasir</option>
                    </select>
                </div>

                <div class="bg-gray-50 p-3 rounded-lg border border-dashed border-gray-300">
                    <label class="block font-semibold text-gray-700">Password Baru</label>
                    <input type="password" name="password" class="w-full border rounded-lg p-2 bg-white"
                        placeholder="Isi hanya jika ingin ganti password">
                    <p class="text-xs text-gray-500 mt-1">*Kosongkan jika tidak ingin mengubah password.</p>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded-lg">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-[#8B1C1C] text-white px-6 py-2 rounded-lg hover:bg-red-800 transition shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
            <button onclick="closeEditModal()" class="absolute top-3 right-4 text-xl hover:text-red-600">✕</button>
        </div>
    </div>


@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#userTableBody tr');

            rows.forEach(row => {
                let role = row.cells[1].textContent.toLowerCase();
                let nama = row.cells[2].textContent.toLowerCase();
                let email = row.cells[3].textContent.toLowerCase();

                if (nama.includes(filter) || email.includes(filter) || role.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                iconColor: '#b91c1c',
                title: 'Berhasil',
                text: "{!! session('success') !!}",
                timer: 1000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        @endif

        // 3. Notifikasi SweetAlert untuk Session Error/Gagal
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{!! session('error') !!}"
            });
        @endif

            function openEditModal(id, nama, email, role) {
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_role').value = role;
                const modal = document.getElementById('editModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

        function confirmToggle(event, action, name) {
            event.preventDefault();
            const form = event.target;

            Swal.fire({
                title: 'Konfirmasi Status',
                text: `Apakah Anda yakin ingin ${action} akun ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: action === 'nonaktifkan' ? '#d33' : '#059669',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.querySelector('input[name="password"]').value = "";
        }
    </script>
@endpush