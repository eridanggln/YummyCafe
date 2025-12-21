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

            <div x-show="userOpen" x-transition
                class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-xl border border-gray-100 py-1 z-50 overflow-hidden">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold flex items-center gap-2">
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
    <div class="p-4">
        <div class="bg-white rounded-xl p-6 w-full max-w-8xl relative
                    shadow-[0_-4px_10px_rgba(0,0,0,0.08),0_6px_15px_rgba(0,0,0,0.1)]">
        
        <a href="{{ route('payment.index') }}"
            class="absolute top-8 left-8 flex items-center gap-1 text-gray-400 hover:text-red-800 transition-colors group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="font-bold text-sm">Kembali</span>
        </a>
        
            <h1 class="text-center text-red-800 text-2xl font-bold mb-8">
                TAMBAH AKUN
            </h1>
            <form action="{{ route('user.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-semibold">Nama</label>
                    <input type="text" name="nama" required class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="block font-semibold">Role</label>
                    <select name="role" required class="w-full border rounded-lg p-2">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="kasir">Kasir</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold">Email</label>
                    <input type="email" name="email" required class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="block font-semibold">Password</label>
                    <input type="password" name="password" required minlength="6" class="w-full border rounded-lg p-2">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-[#8B1C1C] text-white px-6 py-2 rounded-lg hover:bg-red-800">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection