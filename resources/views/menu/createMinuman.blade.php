@extends('layout.layout')

@section('title', 'Tambah Minuman')

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

                <div class="bg-red-800 p-2 rounded-full shadow-md group-hover:bg-red-900 transition flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>

            <div x-show="userOpen" x-transition
                class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-xl border border-gray-100 py-1 z-50 overflow-hidden">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold flex items-center gap-2">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="p-4">
        <div class="bg-white rounded-xl p-6 w-full max-w-8xl relative shadow-[0_-4px_10px_rgba(0,0,0,0.08),0_6px_15px_rgba(0,0,0,0.1)]">
            
            <a href="{{ url()->previous() }}" class="absolute top-7 left-6 flex items-center gap-1 text-gray-500 hover:text-red-800 transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="font-semibold">Kembali</span>
            </a>
            
            <h1 class="text-center text-red-800 text-2xl font-bold mb-8 uppercase tracking-widest">
                TAMBAH MINUMAN
            </h1>

            <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_kategori" value="2">

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Nama Minuman</label>
                    <input type="text" name="nama_menu" value="{{ old('nama_menu') }}" 
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none @error('nama_menu') border-red-500 @enderror">
                    @error('nama_menu')
                        <span class="text-red-600 text-xs font-bold mt-1 inline-block italic underline">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Harga</label>
                    <input type="number" name="harga" value="{{ old('harga') }}"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none @error('harga') border-red-500 @enderror">
                    @error('harga')
                        <span class="text-red-600 text-xs font-bold mt-1 inline-block italic underline">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Gambar</label>
                    <input type="file" name="gambar" accept="image/*" 
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none @error('gambar') border-red-500 @enderror">
                    <p class="text-gray-400 text-[10px] mt-1 italic font-medium">* Format yang didukung: JPG, PNG, JPEG, WEBP (Maks. 2MB)</p>
                    @error('gambar')
                        <span class="text-red-600 text-xs font-bold mt-1 inline-block italic underline">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block font-semibold mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" 
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <span class="text-red-600 text-xs font-bold mt-1 inline-block italic underline">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-yellow-400 text-white font-bold py-3 rounded-lg hover:bg-yellow-500 transition-all shadow-md active:scale-[0.98]">
                    Simpan Menu Minuman
                </button>
            </form>
        </div>
    </div>
@endsection