<!-- Navbar -->
<nav id="navbar" class="fixed top-4 left-4 right-4 z-50 transition-all duration-300 bg-transparent md:rounded-full">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center px-6 py-2">

      <!-- Left: Logo -->
      <div class="flex items-center space-x-3">
        <img src="{{ asset('asset/fun run.png') }}" alt="Logo" class="h-14 w-auto">
        <span class="text-xl font-semibold text-gray-700">Test</span>
      </div>
<!-- Navbar -->
<nav id="navbar" class="fixed top-4 left-4 right-4 z-50 transition-all duration-300 bg-transparent md:rounded-full">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center px-6 py-2">

      <!-- Left: Logo -->
      <div class="flex items-center space-x-3">
        <img src="{{ asset('asset/fun run.png') }}" alt="Logo" class="h-14 w-auto">
        <span class="text-xl font-semibold text-gray-700">Test</span>
      </div>

      <!-- Right: Menu Desktop -->
      <div class="hidden md:flex space-x-6 text-gray-700 font-medium">
        <a href="{{ route ('user.dashboard') }}" class="hover:text-orange-600">Home</a>
        <a href="{{ route ('user.quiz.learning-style') }}" class="hover:text-orange-600">Learning Style</a>
        <a href="{{ route ('user.quiz.hmt') }}" class="hover:text-orange-600">HMT Test</a>
        <a href="{{ route ('user.contact') }}" class="hover:text-orange-600">Contact</a>
        <a href="{{ route('login') }}" class="hover:text-orange-600">
          <i class="fas fa-user-circle text-xl"></i>
        </a>
        <div class="relative">
          <button id="lang-btn-desktop"
                  class="flex items-center space-x-1 hover:text-orange-600 focus:outline-none">
            <span id="lang-text-desktop">EN</span>
            <i class="fas fa-chevron-down text-xs"></i>
          </button>

          <!-- Dropdown -->
          <div id="lang-dropdown-desktop"
               class="absolute right-0 mt-2 w-32 bg-white shadow-lg rounded-md py-2 hidden z-50">
            <button data-lang="en"
                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
              English
            </button>
            <button data-lang="id"
                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
              Indonesia
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu Button -->
      <div class="md:hidden">
        <button id="menu-btn" class="text-gray-700 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Badge Visitor (tampil di desktop saja) -->
  <div class="hidden md:flex absolute -bottom-3 left-10 bg-white shadow px-3 py-1 rounded-full text-orange-600 text-xs font-medium items-center space-x-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 4c4.418 0 8 3.582 8 8s-3.582 8-8 8-8-3.582-8-8 
            3.582-8 8-8z"/>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M2 12h20M12 2a10 10 0 010 20M12 2c-4.418 0-8 3.582-8 8"/>
    </svg>
    <span>1,103,959 tests completed in last 30 days</span>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden flex-col bg-white px-6 pb-4 space-y-2 border-t border-gray-200">
    <a href="{{ route ('user.dashboard') }}" class="py-2 hover:bg-gray-100 rounded-md">Home</a>
    <a href="{{ route ('user.quiz.learning-style') }}" class="py-2 hover:bg-gray-100 rounded-md">Learning Style</a>
    <a href="{{ route ('user.quiz.hmt') }}" class="py-2 hover:bg-gray-100 rounded-md">HMT Test</a>
    <a href="{{ route ('user.contact') }}" class="py-2 hover:bg-gray-100 rounded-md">Contact</a>
    <a href="{{ route('login') }}" class="py-2 hover:bg-gray-100 rounded-md flex items-center space-x-2">
      <i class="fas fa-user-circle text-xl"></i>
      <span>Login</span>
    </a>

    <!-- Lang Switcher Mobile -->
    <div class="relative">
      <button id="lang-btn-mobile"
              class="flex items-center space-x-1 py-2 hover:bg-gray-100 rounded-md w-full text-left">
        <span id="lang-text-mobile">EN</span>
        <i class="fas fa-chevron-down text-xs"></i>
      </button>
      <div id="lang-dropdown-mobile"
           class="hidden mt-1 bg-white shadow rounded-md py-2 w-full">
        <button data-lang="en"
                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
          English
        </button>
        <button data-lang="id"
                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
          Indonesia
        </button>
      </div>
    </div>
  </div>
</nav>

      <!-- Right: Menu Desktop -->
      <div class="hidden md:flex space-x-6 text-gray-700 font-medium">
        <a href="{{ route ('user.dashboard') }}" class="hover:text-cyan-700">Home</a>
        <a href="{{ route ('user.quiz.learning-style') }}" class="hover:text-cyan-700">Learning Style</a>
        <a href="{{ route ('user.quiz.hmt') }}" class="hover:text-cyan-700">HMT Test</a>
        <a href="{{ route ('user.contact') }}" class="hover:text-cyan-700">Contact</a>
        <a href="{{ route('login') }}" class="hover:text-cyan-700">
          <i class="fas fa-user-circle text-xl"></i>
        </a>
        <div class="relative">
          <button id="lang-btn-desktop"
                  class="flex items-center space-x-1 hover:text-cyan-700 focus:outline-none">
            <span id="lang-text-desktop">EN</span>
            <i class="fas fa-chevron-down text-xs"></i>
          </button>

          <!-- Dropdown -->
          <div id="lang-dropdown-desktop"
               class="absolute right-0 mt-2 w-32 bg-white shadow-lg rounded-md py-2 hidden z-50">
            <button data-lang="en"
                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
              English
            </button>
            <button data-lang="id"
                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
              Indonesia
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu Button -->
      <div class="md:hidden">
        <button id="menu-btn" class="text-gray-700 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden flex-col bg-white px-6 pb-4 space-y-2 border-t border-gray-200">
    <a href="{{ route ('user.dashboard') }}" class="py-2 hover:bg-gray-100 rounded-md">Home</a>
    <a href="{{ route ('user.quiz.learning-style') }}" class="py-2 hover:bg-gray-100 rounded-md">Learning Style</a>
    <a href="{{ route ('user.quiz.hmt') }}" class="py-2 hover:bg-gray-100 rounded-md">HMT Test</a>
    <a href="{{ route ('user.contact') }}" class="py-2 hover:bg-gray-100 rounded-md">Contact</a>
    <a href="{{ route('login') }}" class="py-2 hover:bg-gray-100 rounded-md flex items-center space-x-2">
      <i class="fas fa-user-circle text-xl"></i>
      <span>Login</span>
    </a>

    <!-- Lang Switcher Mobile -->
    <div class="relative">
      <button id="lang-btn-mobile"
              class="flex items-center space-x-1 py-2 hover:bg-gray-100 rounded-md w-full text-left">
        <span id="lang-text-mobile">EN</span>
        <i class="fas fa-chevron-down text-xs"></i>
      </button>
      <div id="lang-dropdown-mobile"
           class="hidden mt-1 bg-white shadow rounded-md py-2 w-full">
        <button data-lang="en"
                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
          English
        </button>
        <button data-lang="id"
                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
          Indonesia
        </button>
      </div>
    </div>
  </div>
</nav>

<script>
  // Navbar scroll effect
  window.addEventListener("scroll", function () {
    const navbar = document.getElementById("navbar");
    if (window.scrollY > 20) {
      navbar.classList.add("bg-gray-100", "shadow-lg");
      navbar.classList.remove("bg-transparent");
    } else {
      navbar.classList.remove("bg-gray-100", "shadow-lg");
      navbar.classList.add("bg-transparent");
    }
  });

  // Hamburger menu toggle
  const menuBtn = document.getElementById("menu-btn");
  const mobileMenu = document.getElementById("mobile-menu");
  menuBtn.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
    mobileMenu.classList.toggle("flex");
  });

  // Dropdown desktop
  const langBtnDesktop = document.getElementById("lang-btn-desktop");
  const langDropdownDesktop = document.getElementById("lang-dropdown-desktop");
  langBtnDesktop?.addEventListener("click", () => {
    langDropdownDesktop.classList.toggle("hidden");
  });

  // Dropdown mobile
  const langBtnMobile = document.getElementById("lang-btn-mobile");
  const langDropdownMobile = document.getElementById("lang-dropdown-mobile");
  langBtnMobile?.addEventListener("click", () => {
    langDropdownMobile.classList.toggle("hidden");
  });
</script>
