<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex justify-center items-center bg-gray-100">

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

        <!-- Left Illustration -->
        <div class="hidden md:flex justify-center items-center bg-gray-50 p-8">
            <img src="{{ asset('asset/userlog.png') }}" alt="Ilustrasi Register" class="w-72">
        </div>

        <!-- Right Form -->
        <div class="p-10 flex flex-col justify-center">
            {{-- <!-- Logo -->
      <div class="flex items-center gap-2 mb-6">
        <div class="w-10 h-10 rounded-full bg-orange-600 flex items-center justify-center text-white font-bold">
          <i class="fas fa-bolt"></i>
        </div>
        <h1 class="text-lg font-semibold text-gray-700">Tes IQ</h1>
      </div> --}}

            <h2 class="text-2xl font-bold text-gray-800 mb-2">Buat Akun Baru</h2>
            <p class="text-gray-500 mb-6">Daftar sekarang dan mulai pengalamanmu 🚀</p>

            <!-- Form -->
            <form action="{{ route('register') }}" method="POST" id="formRegister" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
                    <input value="{{ old('name') }}" type="text" name="name" id="name" required
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 @error('name') ring-1 ring-red-600 @enderror focus:outline-none text-sm">
                    @error('name')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block mb-1">Email <span class="text-red-600">*</span></label>
                    <input value="{{ old('email') }}" type="email" name="email" required id="email"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 @error('email') ring-1 ring-red-600 @enderror focus:outline-none text-sm">
                    @error('email')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block mb-1">Nomor Telepon</label>
                    <input value="{{ old('phone') }}" type="text" name="phone" required id="phone"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 @error('phone') ring-1 ring-red-600 @enderror focus:outline-none text-sm">
                    @error('phone')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block mb-1">Jenis Kelamin</label>
                    <select name="gender" required id="gender"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 @error('gender') ring-1 ring-red-600 @enderror focus:outline-none text-sm">
                        <option>-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="birthDate" class="block mb-1">Tanggal Lahir</label>
                    <input value="{{ old('birthDate') }}" type="date" name="birthDate" required id="birthDate"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 @error('birthDate') ring-1 ring-red-600 @enderror focus:outline-none text-sm">
                    @error('birthDate')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="institution" class="block mb-1">Asal Institusi</label>
                    <input type="text" value="{{ old('institution') }}" name="institution" required id="institution"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 @error('institution') ring-1 ring-red-600 @enderror focus:outline-none text-sm">
                    @error('institution')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="relative">
                    <label for="password" class="block mb-1">Password <span class="text-red-600">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 @error('password') ring-1 ring-red-600 @enderror focus:outline-none text-sm pr-10">
                        <button type="button" onclick="togglePassword('password','eye-icon1')"
                            class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                            <i id="eye-icon1" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block mb-1">Konfirmasi Password <span
                            class="text-red-600">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none text-sm pr-10">
                        <button type="button" onclick="togglePassword('password_confirmation','eye-icon2')"
                            class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                            <i id="eye-icon2" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg shadow-md transition">
                    Register
                </button>
            </form>

            {{-- <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-grow h-px bg-gray-200"></div>
                <span class="px-4 text-gray-500 text-sm">atau</span>
                <div class="flex-grow h-px bg-gray-200"></div>
            </div>

            <!-- Social Register -->
            <div class="flex gap-3">
                <button
                    class="flex-1 py-2 border rounded-lg flex items-center justify-center gap-2 hover:bg-gray-50 transition">
                    <i class="fab fa-google text-red-500"></i> Google
                </button>
                <button
                    class="flex-1 py-2 border rounded-lg flex items-center justify-center gap-2 hover:bg-gray-50 transition">
                    <i class="fab fa-facebook text-blue-600"></i> Facebook
                </button>
            </div> --}}

            <!-- Login Link -->
            <p class="text-center text-sm text-gray-600 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-orange-600 font-medium hover:underline">Login disini</a>
            </p>
        </div>
    </div>

    <script>
        function confirmPassword() {
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');

            return password.value == passwordConfirmation.value;
        }

        window.addEventListener('DOMContentLoaded', function() {
            document.getElementById('formRegister').onsubmit = function(e) {
                e.preventDefault();
                if (!confirmPassword()) {
                    return alert('Konfirmasi password tidak sama');
                }
                e.target.submit();
            };
        })

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>
</body>

</html>
