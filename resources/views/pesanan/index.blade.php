@extends('layout.layout')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Pesanan')

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

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@section('content')
    <div>
        <div class="bg-white rounded-xl p-6 max-w-7xl mx-auto shadow h-[80vh] flex flex-col">

            <h1 class="text-center text-red-800 text-2xl font-bold mb-8">
                PESANAN
            </h1>

            <div class="flex gap-6 flex-1 overflow-hidden">

                <section class="flex-1 flex flex-col h-full overflow-hidden">

                <div class="bg-white-50 pb-4 pr-2">
                    <div class="flex items-center justify-between mb-2 gap-4">

                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('pesanan.index') }}"
                                class="px-4 py-1 rounded-full text-sm transition {{ empty($kategoriAktif) ? 'bg-red-700 text-white' : 'bg-yellow-300 hover:bg-yellow-400' }}">
                                All
                            </a>

                            <a href="{{ route('pesanan.index', ['kategori' => 1]) }}"
                                class="px-4 py-1 rounded-full text-sm transition {{ $kategoriAktif == 1 ? 'bg-red-700 text-white' : 'bg-yellow-300 hover:bg-yellow-400' }}">
                                Makanan
                            </a>

                            <a href="{{ route('pesanan.index', ['kategori' => 2]) }}"
                                class="px-4 py-1 rounded-full text-sm transition {{ $kategoriAktif == 2 ? 'bg-red-700 text-white' : 'bg-yellow-300 hover:bg-yellow-400' }}">
                                Minuman
                            </a>

                            <a href="{{ route('pesanan.index', ['kategori' => 3]) }}"
                                class="px-4 py-1 rounded-full text-sm transition {{ $kategoriAktif == 3 ? 'bg-red-700 text-white' : 'bg-yellow-300 hover:bg-yellow-400' }}">
                                Snack
                            </a>

                            <a href="{{ route('pesanan.index', ['kategori' => 4]) }}"
                                class="px-4 py-1 rounded-full text-sm transition {{ $kategoriAktif == 4 ? 'bg-red-700 text-white' : 'bg-yellow-300 hover:bg-yellow-400' }}">
                                Dessert
                            </a>
                        </div>

                        <div class="relative w-64">
                            <input type="text" id="searchMenu" placeholder="Cari menu..."
                                class="w-full pl-10 pr-4 py-2 border rounded-full text-sm focus:ring-2 focus:ring-red-500 focus:outline-none">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        </div>

                    </div>
                </div>

                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    <div class="grid grid-cols-4 gap-6 pb-10">
                        @foreach ($menus as $menu)
                            <div onclick="openAddon({{ $menu->id_menu }}, '{{ $menu->nama_menu }}', {{ $menu->harga_dasar }}, '{{ asset('storage/' . $menu->gambar) }}', '{{ $menu->deskripsi ?? '' }}')"
                                class="bg-white rounded-xl shadow-sm cursor-pointer hover:shadow-lg hover:ring-2 ring-yellow-400 transition overflow-hidden">
                                
                                {{-- GAMBAR --}}
                                <img src="{{ asset('storage/' . $menu->gambar) }}" class="h-32 w-full object-cover">

                                {{-- BODY --}}
                                <div class="p-3">
                                    <h3 class="font-semibold text-sm">{{ $menu->nama_menu }}</h3>
                                    <p class="text-xs text-gray-500 line-clamp-2">
                                        {{ $menu->deskripsi ?? 'Tanpa deskripsi' }}
                                    </p>
                                    <p class="mt-2 font-bold text-red-700 text-sm">
                                        Rp {{ number_format($menu->harga_dasar, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

</section>

                <!-- ================= CART ================= -->
                <aside class="w-80 bg-white border rounded-xl flex flex-col h-full">

                    <!-- HEADER -->
                    <div class="p-4 border-b">
                        <h3 class="font-bold">Pesanan</h3>
                    </div>

                    <!-- LIST PESANAN (SCROLLABLE) -->
                    <div id="cartList" class="flex-1 p-4 space-y-3 overflow-y-auto">
                    </div>

                    <!-- FOOTER (TETAP DI BAWAH) -->
                    <div class="border-t p-4 bg-white sticky bottom-0 space-y-2">

                        <div class="flex justify-between text-sm">
                            <span>Sub Total</span>
                            <span id="subtotal">Rp 0</span>
                        </div>

                        <div class="flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span id="total">Rp 0</span>
                        </div>

                        <button onclick="openCheckoutModal()"
                            class="w-full mt-3 bg-red-700 text-white py-2 rounded-lg hover:bg-red-800">
                            Pesan
                        </button>
                    </div>
                </aside>

            </div>
        </div>
    </div>

    {{-- ================= MODAL ADD ON ================= --}}
    <div id="addonModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl w-full max-w-md p-6 relative">

            <!-- CLOSE -->
            <button type="button" onclick="closeAddon()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-xl font-bold">
                ✕
            </button>

            <!-- JUDUL -->
            <h3 id="addonTitle" class="font-bold text-lg mb-2"></h3>

            <!-- GAMBAR -->
            <img id="addonImage" class="w-full h-40 object-cover rounded-lg mb-3">

            <!-- DESKRIPSI -->
            <p id="addonDesc" class="text-sm text-gray-600 mb-4"></p>

            <hr class="my-4">

            <!-- ADD ON (BUKAN FORM) -->
            <div id="addonForm" class="space-y-4"></div>

            <div class="flex justify-between items-center mt-6">

                <!-- QTY MENU -->
                <div class="flex items-center gap-2">
                    <button type="button" onclick="changeMenuQty(-1)" class="px-3 py-1 bg-gray-200 rounded text-lg">
                        −
                    </button>

                    <span id="menuQty" class="font-semibold text-lg">1</span>

                    <button type="button" onclick="changeMenuQty(1)" class="px-3 py-1 bg-gray-200 rounded text-lg">
                        +
                    </button>
                </div>

                <!-- TOTAL & TAMBAH -->
                <div class="text-right">
                    <p class="font-bold text-sm">
                        Total: <span id="addonTotal">Rp 0</span>
                    </p>

                    <button type="button" onclick="confirmAddToCart()"
                        class="mt-2 bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800">
                        Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        /* =======================
           STATE GLOBAL
        ======================= */
        let cart = [];

        let selectedMenu = null;
        let basePrice = 0;
        let menuQty = 1;
        let editIndex = null;

        let radioAddon = null;        // { id, harga }
        let checkboxAddons = {};     // { id: { harga, qty } }

        /* =======================
           CART STORAGE
        ======================= */
        function saveCart() {
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        function loadCart() {
            const saved = localStorage.getItem('cart');
            if (saved) {
                cart = JSON.parse(saved);
                renderCart();
            }
        }

        /* =======================
           HELPER
        ======================= */
        function findCartIndexByMenuId(menuId) {
            return cart.findIndex(item => item.menu_id == menuId);
        }

        /* =======================
           OPEN MODAL
        ======================= */
        function openAddon(id, nama, harga, gambar, deskripsi = '') {

            // 🔍 cek menu sudah ada di cart
            const existingIndex = findCartIndexByMenuId(id);
            if (existingIndex !== -1) {
                editCartItem(existingIndex);
                return;
            }

            // mode tambah baru
            selectedMenu = { id, nama };
            basePrice = harga;
            menuQty = 1;
            editIndex = null;

            radioAddon = null;
            checkboxAddons = {};

            document.getElementById('menuQty').innerText = menuQty;
            document.getElementById('addonTitle').innerText = nama;
            document.getElementById('addonImage').src = gambar;
            document.getElementById('addonDesc').innerText = deskripsi;
            document.getElementById('addonForm').innerHTML = 'Memuat...';

            updateTotal();

            const modal = document.getElementById('addonModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            fetch(`/menu/${id}/opsi`)
                .then(res => res.json())
                .then(data => renderAddon(data));
        }

        /* =======================
           RENDER ADDON
        ======================= */
        function renderAddon(data) {
            const form = document.getElementById('addonForm');
            form.innerHTML = '';

            if (!data || data.length === 0) {
                form.innerHTML = '<p class="text-gray-500">Tidak ada add on</p>';
                return;
            }

            data.forEach(o => {

                // ===== RADIO (WAJIB PILIH) =====
                if (o.tipe_opsi === 'radio') {
                    let html = `
                                <div class="radio-group mb-4" data-radio-group="${o.id_opsi}">
                                    <p class="font-semibold mb-2">
                                        ${o.nama_opsi}
                                        <span class="text-red-600 text-xs">(wajib)</span>
                                    </p>
                            `;

                    o.pilihan.forEach(p => {
                        html += `
                                    <label class="flex justify-between items-center text-sm mb-1">
                                        <span>
                                            <input type="radio"
                                                name="radio_${o.id_opsi}"
                                                data-id="${p.id_opsi_detail}"
                                                data-harga="${p.harga_tambah}"
                                                data-nama="${p.nama_pilihan}"
                                                data-required="true"
                                                onchange="selectRadioAddon(this)">
                                            ${p.nama_pilihan}
                                        </span>
                                        <span class="text-gray-500">
                                            Rp ${p.harga_tambah.toLocaleString('id-ID')}
                                        </span>
                                    </label>
                                `;
                    });

                    html += `
                                <p id="radioError_${o.id_opsi}"
                                   class="text-red-600 text-xs mt-1 hidden">
                                   Silakan pilih salah satu opsi terlebih dahulu.
                                </p>
                            </div>
                            `;

                    form.innerHTML += html;
                }

                // ===== CHECKBOX =====
                else {
                    let html = `
                                <div class="mb-4">
                                    <p class="font-semibold mb-2">${o.nama_opsi}</p>
                            `;

                    o.pilihan.forEach(p => {
                        html += `
                                    <div class="flex justify-between items-center text-sm mb-2">
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox"
                                                data-id="${p.id_opsi_detail}"
                                                data-harga="${p.harga_tambah}"
                                                data-nama="${p.nama_pilihan}"
                                                onchange="toggleCheckboxAddon(this)">
                                            <span>
                                                ${p.nama_pilihan}
                                                <span class="text-gray-500 text-xs">
                                                    (Rp ${p.harga_tambah.toLocaleString('id-ID')})
                                                </span>
                                            </span>
                                        </label>

                                        <div class="flex items-center gap-1">
                                            <button type="button"
                                                onclick="changeAddonQty(${p.id_opsi_detail}, -1)"
                                                class="px-2 py-1 bg-gray-200 rounded">−</button>

                                            <span id="addonQty_${p.id_opsi_detail}" class="w-5 text-center">1</span>

                                            <button type="button"
                                                onclick="changeAddonQty(${p.id_opsi_detail}, 1)"
                                                class="px-2 py-1 bg-gray-200 rounded">+</button>
                                        </div>
                                    </div>
                                `;
                    });

                    html += `</div>`;
                    form.innerHTML += html;
                }
            });
        }

        /* =======================
           ADDON HANDLER
        ======================= */
        function selectRadioAddon(el) {
            radioAddon = {
                id: el.dataset.id,
                nama: el.dataset.nama ?? 'Addon',
                harga: Number(el.dataset.harga)
            };
            updateTotal();
        }

        function toggleCheckboxAddon(el) {
            const id = el.dataset.id;

            if (el.checked) {
                checkboxAddons[id] = {
                    nama: el.dataset.nama ?? 'Addon',
                    harga: Number(el.dataset.harga),
                    qty: 1
                };
                document.getElementById(`addonQty_${id}`).innerText = 1;
            } else {
                delete checkboxAddons[id];
                document.getElementById(`addonQty_${id}`).innerText = 1;
            }

            updateTotal();
        }

        function changeAddonQty(id, delta) {
            if (!checkboxAddons[id]) return;
            checkboxAddons[id].qty += delta;
            if (checkboxAddons[id].qty < 1) checkboxAddons[id].qty = 1;
            document.getElementById(`addonQty_${id}`).innerText = checkboxAddons[id].qty;
            updateTotal();
        }

        /* =======================
           MENU QTY & TOTAL
        ======================= */
        function changeMenuQty(delta) {
            menuQty += delta;
            if (menuQty < 1) menuQty = 1;
            document.getElementById('menuQty').innerText = menuQty;
            updateTotal();
        }

        function hitungTotalItem() {
            let addonTotal = 0;
            if (radioAddon) addonTotal += radioAddon.harga;
            Object.values(checkboxAddons).forEach(a => addonTotal += a.harga * a.qty);
            return (basePrice * menuQty) + addonTotal;
        }

        function updateTotal() {
            document.getElementById('addonTotal').innerText =
                'Rp ' + hitungTotalItem().toLocaleString('id-ID');
        }

        /* =======================
           ADD TO CART
        ======================= */
        function confirmAddToCart() {

            let isValid = true;

            // cari semua grup radio wajib
            const radioGroups = document.querySelectorAll(
                '#addonForm .radio-group'
            );

            radioGroups.forEach(group => {
                const radios = group.querySelectorAll('input[type="radio"][data-required="true"]');
                const checked = group.querySelector('input[type="radio"]:checked');
                const errorEl = group.querySelector('p[id^="radioError_"]');

                if (radios.length > 0 && !checked) {
                    isValid = false;
                    if (errorEl) errorEl.classList.remove('hidden');
                }
            });

            if (!isValid) return;

            // ✅ LANJUT TAMBAH KE CART
            const item = {
                menu_id: selectedMenu.id,
                nama: selectedMenu.nama,
                harga_menu: basePrice,
                qty: menuQty,
                radioAddon,
                checkboxAddons: JSON.parse(JSON.stringify(checkboxAddons)),
                total: hitungTotalItem()
            };

            if (editIndex !== null) {
                cart[editIndex] = item;
                editIndex = null;
            } else {
                cart.push(item);
            }

            saveCart();
            closeAddon();
            renderCart();
        }

        /* =======================
           RENDER CART
        ======================= */
        function renderCart() {
            const el = document.getElementById('cartList');
            el.innerHTML = '';

            if (!cart.length) {
                el.innerHTML = '<p class="text-gray-400">Pesanan kosong</p>';
                document.getElementById('subtotal').innerText = 'Rp 0';
                document.getElementById('total').innerText = 'Rp 0';
                return;
            }

            let subtotal = 0;

            cart.forEach((item, index) => {
                subtotal += item.total;

                let addonHtml = '';

                // RADIO ADDON
                if (item.radioAddon) {
                    addonHtml += `
                                                                                                <div class="text-xs text-gray-500">
                                                                                                    • ${item.radioAddon.nama || 'Addon'} 
                                                                                                    (Rp ${item.radioAddon.harga.toLocaleString('id-ID')})
                                                                                                </div>`;
                }

                // CHECKBOX ADDON
                Object.values(item.checkboxAddons || {}).forEach(a => {
                    const hargaSatuan = a.harga || 0;
                    const totalAddon = hargaSatuan * a.qty;

                    addonHtml += `
                                                                            <div class="text-xs text-gray-500">
                                                                                • ${a.nama || 'Addon'}
                                                                                (${a.qty} × Rp ${hargaSatuan.toLocaleString('id-ID')} )
                                                                            </div>`;
                });

                el.innerHTML += `
                                                                                            <div class="border rounded-lg p-3 relative">
                                                                                                <div class="absolute top-2 right-2 flex gap-3">
                                                                                                    <button onclick="editCartItem(${index})"
                                                                                                        class="text-blue-600 hover:text-blue-800">
                                                                                                        <i class="fa-solid fa-pen"></i>
                                                                                                    </button>
                                                                                                    <button onclick="deleteCartItem(${index})"
                                                                                                        class="text-red-600 hover:text-red-800">
                                                                                                        <i class="fa-solid fa-trash"></i>
                                                                                                    </button>
                                                                                                </div>

                                                                                                <div class="font-semibold">${item.nama}</div>
                                                                                                <div class="text-sm text-gray-600">
                                                                                                    ${item.qty} × Rp ${item.harga_menu.toLocaleString('id-ID')}
                                                                                                </div>

                                                                                                ${addonHtml}

                                                                                                <div class="font-bold mt-1">
                                                                                                    Rp ${item.total.toLocaleString('id-ID')}
                                                                                                </div>
                                                                                            </div>`;
            });

            document.getElementById('subtotal').innerText =
                'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('total').innerText =
                'Rp ' + subtotal.toLocaleString('id-ID');
        }

        /* =======================
           EDIT & DELETE
        ======================= */
        function deleteCartItem(index) {
            cart.splice(index, 1);
            saveCart();
            renderCart();
        }

        function editCartItem(index) {
            const item = cart[index];
            editIndex = index;

            selectedMenu = { id: item.menu_id, nama: item.nama };
            basePrice = item.harga_menu;
            menuQty = item.qty;
            radioAddon = item.radioAddon;
            checkboxAddons = JSON.parse(JSON.stringify(item.checkboxAddons));

            document.getElementById('menuQty').innerText = menuQty;
            document.getElementById('addonTitle').innerText = item.nama;
            document.getElementById('addonForm').innerHTML = 'Memuat...';

            updateTotal();

            const modal = document.getElementById('addonModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            fetch(`/menu/${item.menu_id}/opsi`)
                .then(res => res.json())
                .then(data => {
                    renderAddon(data);
                    restoreAddonSelection();
                });
        }

        function restoreAddonSelection() {
            if (radioAddon) {
                const radio = document.querySelector(
                    `input[type="radio"][data-id="${radioAddon.id}"]`
                );
                if (radio) radio.checked = true;
            }

            Object.keys(checkboxAddons).forEach(id => {
                const checkbox = document.querySelector(
                    `input[type="checkbox"][data-id="${id}"]`
                );
                if (checkbox) {
                    checkbox.checked = true;
                    document.getElementById(`addonQty_${id}`).innerText =
                        checkboxAddons[id].qty;
                }
            });
        }

        /* =======================
           MODAL
        ======================= */
        function closeAddon() {
            const modal = document.getElementById('addonModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        /* =======================
           INIT
        ======================= */
        document.addEventListener('DOMContentLoaded', () => {
            loadCart();
        });
    </script>

@endsection


<!-- ================= MODAL CHECKOUT ================= -->
<div id="checkoutModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl w-full max-w-lg relative max-h-[90vh] flex flex-col shadow-2xl">

        <button type="button" onclick="closeCheckoutModal()"
            class="absolute top-3 right-3 text-gray-400 hover:text-red-600 text-xl font-bold z-10 transition-colors">
            ✕
        </button>

        <div class="p-6 border-b">
            <h3 class="font-bold text-lg text-red-800">Konfirmasi Pesanan</h3>
            <p class="text-xs text-gray-500">Pastikan data pelanggan sudah benar sebelum konfirmasi.</p>
        </div>

        <div class="p-6 overflow-y-auto flex-1 space-y-4">

            <form id="orderForm" action="/orders" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="text-sm font-semibold text-gray-700">Nomor Pesanan</label>
                    <input type="text" name="nomor_pesanan" id="orderNumber"
                        class="w-full mt-1 px-3 py-2 border rounded-lg text-sm bg-gray-100 font-mono outline-none" readonly>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Nama Customer <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_customer" id="customerName" 
                        required value="{{ old('nama_customer') }}"
                        class="w-full mt-1 px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-red-200 outline-none @error('nama_customer') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap">
                    @error('nama_customer')
                        <p class="text-red-600 text-[10px] mt-1 font-bold italic">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Tipe Pesanan <span class="text-red-500">*</span></label>
                    <select name="tipe_pesanan" id="orderType" required
                        class="w-full mt-1 px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-red-200 outline-none @error('tipe_pesanan') border-red-500 @enderror"
                        onchange="handleOrderType()">
                        <option value="">Pilih tipe</option>
                        <option value="dine_in" {{ old('tipe_pesanan') == 'dine_in' ? 'selected' : '' }}>Dine In</option>
                        <option value="take_away" {{ old('tipe_pesanan') == 'take_away' ? 'selected' : '' }}>Take Away</option>
                    </select>
                    @error('tipe_pesanan')
                        <p class="text-red-600 text-[10px] mt-1 font-bold italic">{{ $message }}</p>
                    @enderror
                </div>

                <div id="tableField" class="{{ old('tipe_pesanan') == 'dine_in' ? '' : 'hidden' }}">
                    <label class="text-sm font-semibold text-gray-700">Nomor Meja <span class="text-red-500">*</span></label>
                    <input type="number" name="nomor_meja" id="tableNumber" 
                        value="{{ old('nomor_meja') }}"
                        class="w-full mt-1 px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-red-200 outline-none @error('nomor_meja') border-red-500 @enderror"
                        placeholder="Contoh: 05">
                    @error('nomor_meja')
                        <p class="text-red-600 text-[10px] mt-1 font-bold italic">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Metode Pembayaran <span class="text-red-500">*</span></label>
                    <select id="paymentMethod" name="id_payment" required
                        class="w-full mt-1 px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-red-200 outline-none @error('id_payment') border-red-500 @enderror">
                        <option value="">Pilih metode pembayaran</option>
                        @foreach ($payments as $payment)
                            <option value="{{ $payment->id_payment }}" 
                                data-jenis="{{ $payment->jenis }}"
                                {{ old('id_payment') == $payment->id_payment ? 'selected' : '' }}>
                                {{ $payment->jenis }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_payment')
                        <p class="text-red-600 text-[10px] mt-1 font-bold italic">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="my-4">
                
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-gray-800">Ringkasan Pesanan</h3>
                    <span class="text-[10px] bg-gray-100 px-2 py-1 rounded text-gray-500 uppercase tracking-widest font-bold">Items</span>
                </div>

                <div class="space-y-2 text-sm bg-gray-50 p-3 rounded-lg border border-dashed border-gray-300 max-h-40 overflow-y-auto" id="checkoutItems">
                    </div>

                <div class="pt-2">
                    <div class="flex justify-between items-center font-bold text-xl text-red-800">
                        <span>Total Bayar</span>
                        <span id="checkoutTotal">Rp 0</span>
                    </div>
                </div>

                <button type="button" onclick="submitOrder()" 
                    class="w-full bg-red-700 text-white py-2 rounded-lg hover:bg-red-800">
                    Konfirmasi Pesanan
                </button>
            </form>

        </div>
    </div>
</div>


<!-- EMPTY CART NOTICE -->
<div id="emptyCartNotice" class="fixed inset-0 flex items-center justify-center z-[999] hidden">

    <div class="bg-white rounded-xl shadow-xl px-8 py-6 text-center
               transform scale-90 opacity-0 transition-all duration-300">

        <!-- ICON -->
        <div class="mx-auto mb-3 w-14 h-14 rounded-full bg-red-100
                    flex items-center justify-center animate-pulse">
            <span class="text-red-600 text-3xl font-bold">✕</span>
        </div>

        <p class="font-semibold text-gray-800 text-lg">
            Pesanan masih kosong
        </p>

        <p class="text-sm text-gray-500 mt-1">
            Silakan pilih menu terlebih dahulu
        </p>
    </div>
</div>

@if (session('success'))
            Swal.fire({
                icon: 'success',
                iconColor: '#b91c1c',
                title: 'Berhasil',
                text: "{!! session('success') !!}",
                timer: 1500,
                showConfirmButton: false,
                timerProgressBar: true
            });
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('error') }}",
        });
    </script>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function generateOrderNumber() {
        const today = new Date();
        const dateKey =
            today.getFullYear().toString() +
            String(today.getMonth() + 1).padStart(2, '0') +
            String(today.getDate()).padStart(2, '0');

        const storageKey = 'order_counter_' + dateKey;

        let counter = localStorage.getItem(storageKey);

        if (!counter) {
            counter = 1;
        } else {
            counter = parseInt(counter) + 1;
        }

        localStorage.setItem(storageKey, counter);

        return `ORD-${dateKey}-${String(counter).padStart(4, '0')}`;
    }

    function handleOrderType() {
        const type = document.getElementById('orderType').value;
        const tableField = document.getElementById('tableField');
        const tableInput = document.getElementById('tableNumber');

        if (type === 'dine_in') {
            tableField.classList.remove('hidden');
        } else {
            tableField.classList.add('hidden');
            tableInput.value = '';
        }
    }

    function showEmptyCartNotice() {
        const wrapper = document.getElementById('emptyCartNotice');
        const box = wrapper.firstElementChild;

        wrapper.classList.remove('hidden');

        // animasi masuk
        setTimeout(() => {
            box.classList.remove('scale-90', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
        }, 10);

        // animasi keluar setelah 2 detik
        setTimeout(() => {
            box.classList.add('scale-90', 'opacity-0');
            setTimeout(() => {
                wrapper.classList.add('hidden');
            }, 300);
        }, 1000);
    }

    function openCheckoutModal() {
        if (cart.length === 0) {
            showEmptyCartNotice();
            return;
        }

        document.getElementById('orderNumber').value = getNextOrderNumber();

        const modal = document.getElementById('checkoutModal');
        const itemsEl = document.getElementById('checkoutItems');
        const totalEl = document.getElementById('checkoutTotal');

        itemsEl.innerHTML = '';
        let grandTotal = 0;

        cart.forEach(item => {
            const hargaDasar = parseFloat(item.harga_dasar || item.harga_menu || item.harga || 0);
            const qtyMenu = parseInt(item.qty || 1);
            const subtotalDasar = hargaDasar * qtyMenu;
            
            grandTotal += subtotalDasar;

            itemsEl.innerHTML += `
                <div class="flex justify-between font-semibold mt-3">
                    <span>${qtyMenu}× ${item.nama}</span>
                    <span>Rp ${subtotalDasar.toLocaleString('id-ID')}</span>
                </div>
            `;

            // 2. Radio Addon
            if (item.radioAddon) {
                const hRadio = parseFloat(item.radioAddon.harga || item.radioAddon.price || 0);
                // CUKUP hRadio saja jika radioAddon hanya 1 per porsi, 
                // atau gunakan qtyMenu jika add-on ini otomatis mengikuti jumlah porsi.
                const subRadio = hRadio * qtyMenu; 
                
                grandTotal += subRadio;
                itemsEl.innerHTML += `
                    <div class="ml-4 text-[11px] text-gray-500 flex justify-between italic border-l-2 border-red-200 pl-2">
                        <span>• ${item.radioAddon.nama}</span>
                        <span>+ Rp ${subRadio.toLocaleString('id-ID')}</span>
                    </div>
                `;
            }

            // 3. Checkbox Addons (BAGIAN YANG DIPERBAIKI)
            if (item.checkboxAddons) {
                Object.values(item.checkboxAddons).forEach(addon => {
                    const hCheck = parseFloat(addon.harga || addon.price || 0);
                    const qCheck = parseInt(addon.qty || 1);
                    
                    // PERBAIKAN: Jangan dikali qtyMenu lagi jika qCheck sudah total keseluruhan
                    const subCheck = hCheck * qCheck; 
                    
                    grandTotal += subCheck;
                    itemsEl.innerHTML += `
                        <div class="ml-4 text-[11px] text-gray-500 flex justify-between italic border-l-2 border-red-200 pl-2">
                            <span>• ${addon.nama} (×${qCheck})</span>
                            <span>+ Rp ${subCheck.toLocaleString('id-ID')}</span>
                        </div>
                    `;
                });
            }
        });

        totalEl.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function getNextOrderNumber() {
        const today = new Date();
        const dateKey = today.getFullYear().toString() +
            String(today.getMonth() + 1).padStart(2, '0') +
            String(today.getDate()).padStart(2, '0');

        const storageKey = 'order_counter_' + dateKey;
        let counter = localStorage.getItem(storageKey);

        let nextCounter = counter ? parseInt(counter) + 1 : 1;

        return `ORD-${dateKey}-${String(nextCounter).padStart(4, '0')}`;
    }

    function commitOrderNumber() {
        const today = new Date();
        const dateKey =
            today.getFullYear().toString() +
            String(today.getMonth() + 1).padStart(2, '0') +
            String(today.getDate()).padStart(2, '0');

        const storageKey = 'order_counter_' + dateKey;
        let counter = localStorage.getItem(storageKey);

        if (!counter) {
            counter = 1;
        } else {
            counter = parseInt(counter) + 1;
        }

        localStorage.setItem(storageKey, counter);
    }

    function submitOrder() {
        console.log('submitOrder dipanggil');

        // 1. Ambil data dari elemen DOM
        const customerName = document.getElementById('customerName').value;
        const orderType = document.getElementById('orderType').value;
        const paymentMethod = document.getElementById('paymentMethod').value;
        const tableNumber = document.getElementById('tableNumber').value;

        // 2. Validasi Sebelum Kirim (Klien-side)
        if (cart.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Cart Kosong',
                text: 'Silakan pilih menu terlebih dahulu sebelum melakukan pesanan.',
                confirmButtonColor: '#b91c1c'
            });
            return;
        }

        if (!customerName || !orderType || !paymentMethod) {
            Swal.fire({
                icon: 'info',
                title: 'Data Belum Lengkap',
                text: 'Mohon isi Nama, Tipe Pesanan, dan Metode Pembayaran.',
                confirmButtonColor: '#b91c1c'
            });
            return;
        }

        // Validasi khusus Meja jika Dine In
        if (orderType === 'dine_in' && !tableNumber) {
            Swal.fire({
                icon: 'info',
                title: 'Nomor Meja Kosong',
                text: 'Mohon isi nomor meja untuk pesanan Dine In.',
                confirmButtonColor: '#b91c1c'
            });
            return;
        }

        // 3. Persiapkan Data
        const orderData = {
            order_number: document.getElementById('orderNumber').value,
            customer_name: customerName,
            order_type: orderType,
            table_number: tableNumber || null,
            payment_method: paymentMethod,
            cart: cart
        };

        // Tampilkan Loading (Opsional tapi baik untuk UX)
        Swal.showLoading();

        // 4. Proses Fetch
        fetch('/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(orderData)
        })
        .then(res => {
            if (!res.ok) {
                // Jika server mengembalikan status 422, 500, dll
                return res.json().then(err => { throw err });
            }
            return res.json();
        })
        .then(res => {
            if (res.success) {
                if (typeof commitOrderNumber === "function") commitOrderNumber();
                
                Swal.fire({
                    icon: 'success',
                    iconColor: '#b91c1c',
                    title: 'Berhasil',
                    text: `Pesanan ${res.order_number} berhasil ditambahkan`,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Refresh halaman atau reset state setelah alert hilang
                    cart = [];
                    if (typeof saveCart === "function") saveCart();
                    if (typeof renderCart === "function") renderCart();
                    closeCheckoutModal();
                    window.location.reload(); // Refresh agar nomor pesanan baru ter-generate
                });
                const printUrl = "{{ route('order.print', ':order_number') }}".replace(':order_number', res.order_number);
                window.open(printUrl, '_blank');

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    confirmButtonColor: '#b91c1c',
                    text: res.message || 'Gagal menyimpan pesanan'
                });
            }
        })
        .catch(err => {
            console.error('Fetch Error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                confirmButtonColor: '#b91c1c',
                text: err.message || 'Terjadi kesalahan pada server atau koneksi'
            });
        });
    }

    function closeCheckoutModal() {
        const modal = document.getElementById('checkoutModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

</script>