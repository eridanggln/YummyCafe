@extends('layout.layout')

@section('title', 'Menu Makanan')

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
    <div class="p-4 space-y-6 h-[calc(100vh-120px)] overflow-y-auto">
        <div
            class="bg-white rounded-xl p-6 w-full max-w-8xl
                                                                                                                                                                            shadow-[0_-4px_10px_rgba(0,0,0,0.08),0_6px_15px_rgba(0,0,0,0.1)]">

            <h1 class="text-center text-red-800 text-2xl font-bold mb-8">
                DAFTAR MENU MAKANAN
            </h1>

            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('menu.create', 1) }}" class="bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800">
                    + Tambah Makanan
                </a>

                <input type="text" id="searchMenu" placeholder="Cari menu..."
                    class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

                @forelse ($menus as $menu)
                    <div class="menu-card
                                        rounded-xl shadow p-4 relative flex flex-col
                                        {{ $menu->status == 0 ? 'bg-gray-200 text-gray-600 opacity-80' : 'bg-white text-gray-800' }}
                                    ">

                        {{-- GAMBAR --}}
                        <div class="relative mb-3">
                            <img src="{{ asset('storage/' . $menu->gambar) }}" class="rounded-lg h-32 w-full object-cover
                                                {{ $menu->status == 0 ? 'grayscale brightness-75' : '' }}">

                            @if ($menu->status == 0)
                                <span class="absolute top-2 right-2 z-10
                                                                               bg-gray-800 text-white text-xs px-2 py-1 rounded">
                                    Nonaktif
                                </span>
                            @endif
                        </div>


                        {{-- NAMA --}}
                        <h3 class="font-semibold text-center">
                            {{ $menu->nama_menu }}
                        </h3>

                        {{-- HARGA --}}
                        <p class="text-gray-700 text-sm text-center font-medium">
                            Rp {{ number_format($menu->harga_dasar, 0, ',', '.') }}
                        </p>

                        {{-- DESKRIPSI --}}
                        <p class="text-gray-500 text-xs text-center mt-1 mb-4 line-clamp-2">
                            {{ $menu->deskripsi }}
                        </p>

                        <div class="mt-auto grid grid-cols-3 gap-2">
                            <div>
                                <button onclick="openAddonModal('{{ $menu->id_menu }}', '{{ $menu->nama_menu }}')"
                                    class="w-full bg-yellow-400 text-white text-xs py-1 rounded hover:bg-yellow-500">
                                    Add On
                                </button>
                            </div>

                            <div>
                                <button onclick='openEditModal(
                                                                                    {{ $menu->id_menu }},
                                                                                    @json($menu->nama_menu),
                                                                                    {{ $menu->harga_dasar }},
                                                                                    @json($menu->deskripsi),
                                                                                    @json(asset("storage/" . $menu->gambar))
                                                                                )'
                                    class="w-full bg-blue-500 text-white text-xs py-1 rounded hover:bg-blue-600">
                                    Edit
                                </button>
                            </div>

                            <div>
                                <form action="{{ route('menu.toggle', $menu->id_menu) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="w-full bg-red-500 text-white text-xs py-1 rounded hover:bg-red-600">
                                        {{ $menu->status ? 'Nonaktif' : 'Aktif' }}
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    <p class="col-span-4 text-center text-gray-500">
                        Belum ada menu makanan
                    </p>
                @endforelse


            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                iconColor: '#b91c1c',
                title: 'Berhasil',
                text: "{!! session('success') !!}",
                timer: 1000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}"
            });
        </script>
    @endif
@endsection


{{-- MODAL ADD ON --}}
<div id="addonModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl p-6 w-full max-w-4xl mx-auto relative
        shadow-[0_-4px_10px_rgba(0,0,0,0.08),0_6px_15px_rgba(0,0,0,0.1)]">

        {{-- CLOSE --}}
        <button onclick="closeAddonModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
            ✕
        </button>

        <h1 class="text-center text-red-800 text-2xl font-bold mb-6">
            ATUR OPSI MENU
        </h1>

        <form action="{{ route('menu.opsi.store') }}" method="POST">
            @csrf

            {{-- NAMA MENU --}}
            <div class="mb-4">
                <label class="font-semibold">Nama Menu</label>
                <input type="text" id="addonMenuNama" readonly class="w-full border rounded-lg p-2 bg-gray-100">
            </div>

            <input type="hidden" name="id_menu" id="addonMenuId">

            {{-- NAMA OPSI --}}
            <div class="mb-4">
                <label class="font-semibold text-gray-700">Nama Add On <span class="text-red-500">*</span></label>
                <input type="text" name="nama_opsi" placeholder="Contoh: Jenis / Tambahan"
                    required value="{{ old('nama_opsi') }}"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-yellow-400 outline-none @error('nama_opsi') border-red-500 @enderror">
                @error('nama_opsi')
                    <p class="text-red-600 text-xs font-bold mt-1 italic">{{ $message }}</p>
                @enderror
            </div>

            {{-- TIPE OPSI --}}
            <div class="mb-6">
                <label class="font-semibold block mb-2 text-gray-700">Tipe Add On <span class="text-red-500">*</span></label>

                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="tipe_opsi" value="radio" class="w-4 h-4 text-red-800 focus:ring-red-800" {{ old('tipe_opsi', 'radio') == 'radio' ? 'checked' : '' }}>
                        <span class="text-sm font-medium group-hover:text-red-800">Pilih satu (Radio)</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="tipe_opsi" value="checkbox" class="w-4 h-4 text-red-800 focus:ring-red-800" {{ old('tipe_opsi') == 'checkbox' ? 'checked' : '' }}>
                        <span class="text-sm font-medium group-hover:text-red-800">Bisa lebih dari satu (Checkbox)</span>
                    </label>
                </div>
                @error('tipe_opsi')
                    <p class="text-red-600 text-xs font-bold mt-1 italic">{{ $message }}</p>
                @enderror
            </div>

            {{-- PILIHAN --}}
            <h2 class="font-semibold mb-2">Pilihan</h2>

            <div id="pilihanWrapper" class="space-y-3">
                <div class="flex gap-3">
                    <input type="text" name="pilihan[0][nama]" placeholder="Nama pilihan"
                        class="flex-1 border rounded-lg p-2">

                    <input type="number" name="pilihan[0][harga]" placeholder="Harga (+)"
                        class="w-32 border rounded-lg p-2">
                </div>
            </div>

            <button type="button" onclick="tambahPilihan()" class="mt-3 text-sm text-blue-600 hover:underline">
                + Tambah Pilihan
            </button>

            <button class="w-full mt-6 bg-yellow-400 text-white py-2 rounded-lg font-semibold hover:bg-yellow-500">
                Simpan Add On
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const searchInput = document.getElementById('searchMenu');
        if (!searchInput) return;

        searchInput.addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase();
            const cards = document.querySelectorAll('.menu-card');

            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                card.style.display = text.includes(keyword) ? '' : 'none';
            });
        });

    });

    let index = 1;

    function openAddonModal(id, nama) {
        document.getElementById('addonModal').classList.remove('hidden');
        document.getElementById('addonModal').classList.add('flex');

        document.getElementById('addonMenuId').value = id;
        document.getElementById('addonMenuNama').value = nama;

        // reset pilihan
        document.getElementById('pilihanWrapper').innerHTML = `
            <div class="flex gap-3">
                <input type="text" name="pilihan[0][nama]"
                    placeholder="Nama pilihan"
                    class="flex-1 border rounded-lg p-2">

                <input type="number" name="pilihan[0][harga]"
                    placeholder="Harga (+)"
                    class="w-32 border rounded-lg p-2">
            </div>
        `;
        index = 1;
    }

    function closeAddonModal() {
        document.getElementById('addonModal').classList.add('hidden');
        document.getElementById('addonModal').classList.remove('flex');
    }

    function tambahPilihan() {
        document.getElementById('pilihanWrapper')
            .insertAdjacentHTML('beforeend', `
                <div class="flex gap-3">
                    <input type="text" name="pilihan[${index}][nama]"
                        placeholder="Nama pilihan"
                        class="flex-1 border rounded-lg p-2">

                    <input type="number" name="pilihan[${index}][harga]"
                        placeholder="Harga (+)"
                        class="w-32 border rounded-lg p-2">
                </div>
            `);
        index++;
    }
</script>


{{-- MODAL EDIT MENU --}}
<div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl w-full max-w-3xl max-h-[90vh]
                flex flex-col relative">

        {{-- HEADER --}}
        <div class="p-6 border-b">
            <h2 class="text-center text-red-800 text-xl font-bold">
                EDIT MENU MAKANAN
            </h2>

            <button onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                ✕
            </button>
        </div>

        {{-- BODY --}}
        <div class="p-6 overflow-y-auto">

            <form method="POST" action="{{ route('menu.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="id_menu" id="editMenuId">

                {{-- NAMA --}}
                <div class="mb-4">
                    <label class="font-semibold">Nama Makanan</label>
                    <input type="text" id="editNama" name="nama_menu" class="w-full border rounded-lg p-2">
                </div>

                {{-- HARGA --}}
                <div class="mb-4">
                    <label class="font-semibold">Harga</label>
                    <input type="number" id="editHarga" name="harga_dasar" class="w-full border rounded-lg p-2">
                </div>

                {{-- DESKRIPSI --}}
                <div class="mb-4">
                    <label class="font-semibold">Deskripsi</label>
                    <textarea id="editDeskripsi" name="deskripsi" rows="3"
                        class="w-full border rounded-lg p-2"></textarea>
                </div>

                {{-- GAMBAR --}}
                <div class="mb-6">
                    <label class="font-semibold block mb-1">Gambar</label>

                    <!-- INPUT FILE -->
                    <input type="file" name="gambar" id="editGambarInput" onchange="previewEditImage(event)"
                        class="w-full border rounded-lg p-2">

                    <!-- PREVIEW -->
                    <div id="editPreviewWrapper" class="relative inline-block mt-3">
                        <img id="editPreview" class="h-32 w-32 rounded object-cover border">

                        <button type="button" onclick="hapusGambar()"
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-2 py-1 hover:bg-red-600">
                            ✕
                        </button>
                    </div>

                    <!-- FLAG HAPUS GAMBAR -->
                    <input type="hidden" name="hapus_gambar" id="hapusGambar" value="0">
                </div>

                {{-- GARIS --}}
                <hr class="my-6">

                {{-- ADD ON --}}
                <h3 class="font-semibold mb-3">ADD ON</h3>

                <div id="editAddonWrapper" class="text-sm text-gray-600">
                    Add on belum ditambahkan
                </div>

                {{-- SUBMIT --}}
                <button class="w-full mt-6 bg-yellow-400 text-white py-2 rounded-lg font-semibold hover:bg-yellow-500">
                    Simpan Perubahan
                </button>

            </form>
        </div>
    </div>
</div>


<script>
    /* ===============================
       OPEN & CLOSE EDIT MODAL
    ================================ */
    function openEditModal(id, nama, harga, deskripsi, gambar) {
        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('editMenuId').value = id;
        document.getElementById('editNama').value = nama;
        document.getElementById('editHarga').value = harga;
        document.getElementById('editDeskripsi').value = deskripsi ?? '';

        const img = document.getElementById('editPreview');
        const wrapper = document.getElementById('editPreviewWrapper');

        img.src = gambar;
        img.dataset.originalSrc = gambar;

        wrapper.style.display = 'inline-block';

        document.getElementById('hapusGambar').value = 0;

        img.dataset.originalSrc = gambar;

        // reset state
        document.getElementById('hapusGambar').value = 0;
        document.getElementById('editGambarInput').value = '';

        loadAddon(id);
    }


    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        const img = document.getElementById('editPreview');
        const wrapper = document.getElementById('editPreviewWrapper');

        if (img.dataset.originalSrc) {
            img.src = img.dataset.originalSrc;
            wrapper.style.display = 'inline-block';
        }

        document.getElementById('hapusGambar').value = 0;
    }



    /* ===============================
       LOAD ADD ON (EDITABLE)
    ================================ */
    function loadAddon(menuId) {
        const wrapper = document.getElementById('editAddonWrapper');
        wrapper.innerHTML = '<span class="text-gray-500">Memuat add on...</span>';

        fetch(`/menu/${menuId}/opsi`)
            .then(res => res.json())
            .then(data => {

                if (!data || data.length === 0) {
                    wrapper.innerHTML =
                        '<span class="text-gray-500">Add on belum ditambahkan</span>';
                    return;
                }

                let html = '';

                data.forEach(opsi => {
                    html += `
                <div class="border rounded-lg p-4 mb-6">

                    <!-- NAMA OPSI -->
                    <div class="mb-3">
                        <label class="font-semibold">Nama Opsi</label>
                        <input type="text"
                            name="opsi[${opsi.id_opsi}][nama_opsi]"
                            value="${opsi.nama_opsi ?? ''}"
                            class="w-full border rounded-lg p-2">
                    </div>

                    <!-- TIPE OPSI -->
                    <div class="mb-4">
                        <label class="font-semibold block mb-2">Tipe Opsi</label>

                        <label class="flex items-center gap-2">
                            <input type="radio"
                                name="opsi[${opsi.id_opsi}][tipe_opsi]"
                                value="radio"
                                ${opsi.tipe_opsi === 'radio' ? 'checked' : ''}>
                            Pilih satu
                        </label>

                        <label class="flex items-center gap-2 mt-1">
                            <input type="radio"
                                name="opsi[${opsi.id_opsi}][tipe_opsi]"
                                value="checkbox"
                                ${opsi.tipe_opsi === 'checkbox' ? 'checked' : ''}>
                            Bisa lebih dari satu
                        </label>
                    </div>

                    <!-- PILIHAN -->
                    <h4 class="font-semibold mb-2">Pilihan</h4>
                    <div id="pilihanEdit_${opsi.id_opsi}" class="space-y-3">
                `;

                    opsi.pilihan.forEach((p, i) => {
                        const isActive = Number(p.status) === 1;

                        html += `
                    <div class="grid grid-cols-12 gap-3 items-center ${!isActive ? 'opacity-50' : ''}"
                         data-row
                         data-status="${p.status}">

                        <input type="hidden"
                            name="opsi[${opsi.id_opsi}][pilihan][${i}][id_opsi_detail]"
                            value="${p.id_opsi_detail}">

                        <input type="text"
                            name="opsi[${opsi.id_opsi}][pilihan][${i}][nama]"
                            value="${p.nama_pilihan}"
                            class="col-span-7 border rounded-lg p-2">

                        <input type="number"
                            name="opsi[${opsi.id_opsi}][pilihan][${i}][harga]"
                            value="${p.harga_tambah}"
                            class="col-span-3 border rounded-lg p-2">

                        <button type="button"
                            onclick="togglePilihanUI(this)"
                            data-status="${p.status}"
                            class="col-span-2 text-xs px-2 py-2 rounded
                            ${isActive ? 'bg-blue-500 text-white' : 'bg-gray-400 text-white'}">
                            ${isActive ? 'Aktif' : 'Nonaktif'}
                        </button>

                        <input type="hidden"
                            name="opsi[${opsi.id_opsi}][pilihan][${i}][status]"
                            value="${p.status}">
                    </div>
                    `;
                    });

                    html += `
                    </div>

                    <!-- TAMBAH PILIHAN -->
                    <button type="button"
                        onclick="tambahPilihanEdit(${opsi.id_opsi})"
                        class="mt-3 text-sm text-blue-600 hover:underline">
                        + Tambah Pilihan
                    </button>

                </div>`;
                });

                wrapper.innerHTML = html;
            })
            .catch(() => {
                wrapper.innerHTML =
                    '<span class="text-red-500">Gagal memuat add on</span>';
            });
    }

    function previewEditImage(event) {
        const img = document.getElementById('editPreview');

        img.src = URL.createObjectURL(event.target.files[0]);
        img.style.display = 'block';

        // batal hapus
        document.getElementById('hapusGambar').value = 0;
    }



    function hapusGambar() {
        // SEMBUNYIKAN SELURUH PREVIEW (PASTI HILANG)
        document.getElementById('editPreviewWrapper').style.display = 'none';

        // tandai untuk hapus saat simpan
        document.getElementById('hapusGambar').value = 1;

        // reset file input
        document.getElementById('editGambarInput').value = '';
    }




    /* ===============================
       TAMBAH PILIHAN BARU
    ================================ */
    function tambahPilihanEdit(id_opsi) {
        const wrapper = document.getElementById(`pilihanEdit_${id_opsi}`);
        const index = wrapper.children.length;

        wrapper.insertAdjacentHTML('beforeend', `
    <div class="grid grid-cols-12 gap-3 items-center">

        <input type="hidden"
            name="opsi[${id_opsi}][pilihan][${index}][id_opsi_detail]"
            value="">

        <input type="text"
            name="opsi[${id_opsi}][pilihan][${index}][nama]"
            placeholder="Nama pilihan"
            class="col-span-6 border rounded-lg p-2">

        <input type="number"
            name="opsi[${id_opsi}][pilihan][${index}][harga]"
            placeholder="Harga (+)"
            class="col-span-4 border rounded-lg p-2">

        <span class="col-span-2 text-xs text-gray-400 text-center">
            Baru
        </span>

    </div>
    `);
    }

    /* ===============================
       TOGGLE AKTIF / NONAKTIF
    ================================ */
    function togglePilihanUI(btn) {
        // status sekarang
        let status = btn.dataset.status === '1' ? 1 : 0;

        // toggle
        status = status === 1 ? 0 : 1;
        btn.dataset.status = status;

        // ambil hidden input status
        const hiddenInput = btn.parentElement.querySelector(
            'input[type="hidden"][name$="[status]"]'
        );
        hiddenInput.value = status;

        // animasi & tampilan
        if (status === 1) {
            btn.innerText = 'Aktif';
            btn.className =
                'col-span-2 text-xs px-2 py-2 rounded bg-blue-500 text-white';
            btn.closest('[data-row]').classList.remove('opacity-50');
        } else {
            btn.innerText = 'Nonaktif';
            btn.className =
                'col-span-2 text-xs px-2 py-2 rounded bg-gray-400 text-white';
            btn.closest('[data-row]').classList.add('opacity-50');
        }
    }

</script>