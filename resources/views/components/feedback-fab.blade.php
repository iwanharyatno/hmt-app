@if (!empty($url))
    <div x-data="{ open: false, no_auto: localStorage.getItem('feedback_fab_no_auto') === '1' }" x-init="if (!no_auto) { setTimeout(() => open = true, 1000) }" x-cloak>
        <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-3">
            <!-- Card kecil (popup) -->
            <div x-show="open" x-transition @click.away="open = false"
                class="w-80 bg-white border border-gray-200 rounded-lg shadow-lg p-4 text-left"
                style="will-change: transform, opacity;">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Beri tahu Kami</h3>
                        <p class="text-xs text-gray-600 mt-1">
                            Punya masukan, saran, atau menemukan bug? Klik tombol di bawah untuk isi form feedback.
                        </p>
                    </div>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 ml-2" aria-label="Close">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 8.586L15.657 2.93a1 1 0 011.414 1.414L11.414 10l5.657 5.657a1 1 0 01-1.414 1.414L10 11.414l-5.657 5.657a1 1 0 01-1.414-1.414L8.586 10 2.93 4.343A1 1 0 014.343 2.93L10 8.586z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="mt-3 flex gap-2">
                    <a href="{{ $url }}" target="_blank" rel="noopener"
                        class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">
                        Beri tahu Kami
                    </a>
                    <template x-if="!no_auto">
                        <button @click="no_auto = true; open = false; localStorage.setItem('feedback_fab_no_auto', '1')"
                            title="Sembunyikan"
                            class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                            Sembunyikan
                        </button>
                    </template>
                </div>
            </div>

            <!-- FAB -->
            <button @click="open = !open" :aria-expanded="open"
                class="h-14 w-14 rounded-full bg-orange-500 text-white flex items-center justify-center shadow-lg hover:bg-orange-600 focus:outline-none"
                title="Beri tahu Kami">
                <!-- icon chat/feedback -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                </svg>
            </button>
        </div>
    </div>
@endif
