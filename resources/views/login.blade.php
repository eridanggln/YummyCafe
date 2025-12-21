<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Cafe Yummy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white flex items-center justify-center min-h-screen">

    <div class="bg-red-800 w-[420px] rounded-2xl shadow-lg p-10">
        <h1 class="text-center text-yellow-400 text-2xl font-bold mb-8">
            CAFE YUMMY
        </h1>

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-white mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label class="block text-white mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full bg-yellow-400 text-white font-semibold py-2 rounded-lg hover:bg-yellow-500 transition">
                Login
            </button>

            @if (session('login_error'))
                <div class="mt-4 text-center">
                    <p class="text-red-600 font-semibold text-sm bg-red-50 p-3 rounded-lg border border-red-200">
                        {{-- Gunakan tanda seru agar tag <br> berfungsi --}}
                        {!! session('login_error') !!}
                    </p>
                </div>
            @endif
        </form>

        <div class="text-center mt-4">
            <a href="#" class="text-yellow-300 underline hover:text-yellow-400">
                Masuk Sebagai Customer
            </a>
        </div>
    </div>

</body>

</html>