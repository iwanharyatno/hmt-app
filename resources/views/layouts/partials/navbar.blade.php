<!-- 🔹 Simplified Responsive Navbar -->
<nav id="navbar" class="fixed top-4 left-4 right-4 z-50 bg-transparent transition-all duration-300 md:rounded-full">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center px-6 py-2">

            <!-- 🔹 Logo -->
            <div class="flex items-center space-x-3">
                <span class="text-xl font-semibold text-gray-700">LSHMT Test</span>
            </div>

            <!-- 🔹 Desktop Menu -->
            <div class="hidden md:flex space-x-6 text-gray-700 font-medium items-center">
                <a href="{{ route('user.dashboard') }}" class="hover:text-cyan-700">Home</a>
                @auth
                    <a href="{{ route('user.quiz.learning-style') }}" class="hover:text-cyan-700">Learning Style</a>
                    <a href="{{ route('user.quiz.hmt') }}" class="hover:text-cyan-700">HMT Test</a>
                @endauth
                {{-- <a href="{{ route('user.contact') }}" class="hover:text-cyan-700">Contact</a> --}}
                @auth
                    <a href="{{ route('logout') }}"
                        class="inline-flex items-center gap-2 outline-1 outline-orange-600 bg-white text-orange-600 font-medium px-6 py-3 rounded-lg shadow hover:bg-orange-600 hover:text-white transition">
                        Logout
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 bg-orange-600 text-white font-medium px-6 py-3 rounded-lg shadow hover:bg-orange-700 transition">
                        Login
                    </a>
                @endauth
            </div>

            <!-- 🔹 Mobile Menu Button -->
            <button id="menu-btn" class="md:hidden text-gray-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- 🔹 Mobile Menu -->
    <div id="mobile-menu" class="hidden flex-col bg-white px-6 pb-4 space-y-2 border-t border-gray-200 md:hidden">
        <a href="{{ route('user.dashboard') }}" class="py-2 hover:bg-gray-100 rounded-md">Home</a>
        <a href="{{ route('user.quiz.learning-style') }}" class="py-2 hover:bg-gray-100 rounded-md">Learning Style</a>
        <a href="{{ route('user.quiz.hmt') }}" class="py-2 hover:bg-gray-100 rounded-md">HMT Test</a>
        {{-- <a href="{{ route('user.contact') }}" class="py-2 hover:bg-gray-100 rounded-md">Contact</a> --}}
        <a href="{{ route('login') }}" class="py-2 hover:bg-gray-100 rounded-md flex items-center space-x-2">
            <i class="fas fa-user-circle text-xl"></i>
            <span>Login</span>
        </a>
    </div>
</nav>

<script>
    // 🔹 Scroll effect (background transition)
    window.addEventListener("scroll", () => {
        const navbar = document.getElementById("navbar");
        if (window.scrollY > 20) {
            navbar.classList.add("bg-gray-100", "shadow-lg");
            navbar.classList.remove("bg-transparent");
        } else {
            navbar.classList.remove("bg-gray-100", "shadow-lg");
            navbar.classList.add("bg-transparent");
        }
    });

    // 🔹 Mobile menu toggle
    const menuBtn = document.getElementById("menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");
    menuBtn.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden");
        mobileMenu.classList.toggle("flex");
    });
</script>
