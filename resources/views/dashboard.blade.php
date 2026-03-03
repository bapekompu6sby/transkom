<x-app-layout>
    <div class=" mx-auto space-y-6 bg-gray-100">
        {{-- HERO SECTION --}}
        {{-- Perubahan: h-[70vh] untuk HP, dan md:h-[calc(100vh-4rem)] untuk full desktop (dikurangi tinggi navbar) --}}
        <div
            class="relative w-full h-[70vh] md:h-[calc(100vh-4rem)] min-h-[400px] flex items-center justify-center bg-gray-900 overflow-hidden">
            {{-- Background Image --}}
            <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=1920&auto=format&fit=crop"
                alt="Background" class="absolute inset-0 w-full h-full object-cover opacity-40">

            {{-- Konten Teks & Tombol --}}
            <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
                <h1
                    class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight mb-4 drop-shadow-md">
                    Layanan Kendaraan Operasional
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-gray-200 mb-8 font-light drop-shadow px-2 sm:px-0">
                    Selamat datang di TRANSKOM BAPEKOM PU Wilayah VI Surabaya. Cek ketersediaan jadwal dan ajukan
                    peminjaman kendaraan dengan mudah.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    {{-- Tombol "Lihat Jadwal" Transparan bergaya modern --}}
                    <a href="#jadwal"
                        class="w-full sm:w-auto px-6 py-3 bg-transparent text-white font-semibold rounded-lg hover:bg-white hover:text-gray-900 transition border border-white shadow-lg flex items-center justify-center gap-2 backdrop-blur-sm">
                        Lihat Jadwal
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- KALENDER SECTION --}}
        <div id="jadwal" class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Kalender</h2>
                        <p id="calendarSubTitle" class="text-sm text-gray-500">—</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button id="btnToday"
                            class="px-3 py-2 text-sm rounded-md border border-gray-300 hover:bg-gray-50">
                            today
                        </button>

                        <div class="flex overflow-hidden rounded-md border border-gray-300">
                            <button id="btnPrev" class="px-3 py-2 hover:bg-gray-50" title="Prev">‹</button>
                            <button id="btnNext" class="px-3 py-2 hover:bg-gray-50" title="Next">›</button>
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-4">
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="p-3 overflow-auto" style="max-height: 70vh;">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- CARD LIST MOBIL (Carousel) --}}
        <div class="relative p-6 bg-white">
            <div class="text-center py-8">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                    Kendaraan Aktif
                </h2>

                <p class="text-base text-gray-500 mt-2">
                    {{ $cars->count() }} kendaraan terdaftar
                </p>


            </div>

            {{-- tombol kiri --}}
            <button type="button" id="carsPrev"
                class="absolute left-2 top-1/2 -translate-y-1/2 z-10
               h-12 w-12 rounded-full bg-white shadow-md
               border border-gray-200 hover:bg-gray-50
               flex items-center justify-center">
                ‹
            </button>

            {{-- tombol kanan --}}
            <button type="button" id="carsNext"
                class="absolute right-2 top-1/2 -translate-y-1/2 z-10
               h-12 w-12 rounded-full bg-white shadow-md
               border border-gray-200 hover:bg-gray-50
               flex items-center justify-center">
                ›
            </button>

            {{-- ✅ VIEWPORT: Responsif (Full di HP, Center di Desktop) --}}
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 overflow-hidden">
                <div id="carsTrack"
                    class="flex gap-4 sm:gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-4
                   [scrollbar-width:none] [-ms-overflow-style:none]">
                    <style>
                        #carsTrack::-webkit-scrollbar {
                            display: none;
                        }
                    </style>

                    @foreach ($cars ?? [] as $car)
                        {{-- Perubahan: Lebar card 260px di HP, 320px di Desktop. Snap-center di HP --}}
                        <div
                            class="snap-center sm:snap-start shrink-0 w-[260px] sm:w-[320px] bg-white border border-gray-200
                            rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden">

                            {{-- gambar --}}
                            <div class="h-40 sm:h-44 bg-gray-50 flex items-center justify-center">
                                @if (!empty($car->image_url))
                                    <img src="{{ asset('storage/' . $car->image_url) }}" alt="{{ $car->name }}"
                                        class="h-full w-full object-cover">
                                @else
                                    <div
                                        class="h-16 w-16 sm:h-20 sm:w-20 rounded-full border-4 border-gray-900/80 grid place-items-center">
                                        <span class="text-xl sm:text-2xl font-bold text-gray-900">🚗</span>
                                    </div>
                                @endif
                            </div>

                            {{-- konten --}}
                            <div class="p-4 sm:p-5 text-center">
                                <div class="text-base sm:text-lg font-semibold text-gray-900">{{ $car->name }}</div>
                                <div class="text-xs sm:text-sm text-gray-500 mt-1">
                                    {{ $car->type ?? 'Kendaraan Operasional' }}
                                </div>
                                <div class="text-lg sm:text-xl font-bold text-gray-900 mt-3 sm:mt-4">
                                    {{ $car->plate_number ?? '-' }}</div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        {{-- SECTION BUKU PANDUAN --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
            <div
                class="bg-gray-900 rounded-2xl shadow-lg border border-gray-700 flex flex-col md:flex-row items-center justify-between p-6 md:p-8 md:px-10 gap-6">

                {{-- Teks Info --}}
                <div class="text-center md:text-left">
                    <h3
                        class="text-xl md:text-2xl font-bold text-white flex items-center justify-center md:justify-start gap-2">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        Butuh Bantuan Penggunaan Sistem?
                    </h3>
                    <p class="text-gray-300 mt-2 text-sm md:text-base">
                        Baca buku panduan lengkap langkah demi langkah pengajuan peminjaman kendaraan operasional.
                    </p>
                </div>

                {{-- Tombol Lihat Buku (target="_blank" agar buka tab baru, tidak auto download) --}}
                {{-- Sesuaikan 'buku-panduan.pdf' dengan nama file asli di dalam folder public kamu --}}
                {{-- {{ asset('buku-panduan.pdf') }} --}}
                <a href="#" target="_blank"
                    class="shrink-0 px-6 py-3 bg-white text-gray-900 font-bold rounded-lg hover:bg-gray-100 hover:scale-105 transition-all shadow-md flex items-center gap-2">
                    Lihat Buku Panduan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            </div>
        </div>

        {{-- BAGIAN FOOTER --}}
        <footer class="bg-gray-900 text-white mt-12 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
                <div class="flex flex-col md:flex-row justify-between items-center md:items-start gap-8">

                    {{-- Info Sistem --}}
                    <div class="text-center md:text-left">
                        <h3 class="text-xl font-bold tracking-wider text-white">TRANSKOM</h3>
                        <p class="text-sm text-gray-400 mt-2 max-w-sm">
                            Sistem Peminjaman Kendaraan <br>
                            Balai Pengembangan Kompetensi PUPR Wilayah VI Surabaya
                        </p>
                    </div>

                    {{-- Kontak & Developer --}}
                    <div class="flex flex-col gap-4 text-sm text-center md:text-left">

                        {{-- WA Admin (Ganti angka 628... dengan nomor aslinya) --}}
                        <a href="https://wa.me/6285859422388" target="_blank"
                            class="flex items-center justify-center md:justify-start gap-3 text-gray-300 hover:text-green-400 transition-colors group">
                            <span class="p-2 bg-gray-800 rounded-lg group-hover:bg-green-400/10 transition-colors">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                            </span>
                            <div>
                                <span class="block text-xs text-gray-500">Hubungi Admin Peminjaman</span>
                                <span class="font-medium text-white tracking-wide">+62 858-5942-2388</span>
                            </div>
                        </a>

                        {{-- Info Developer --}}
                        <a href="https://uhycore.github.io" target="_blank"
                            class="flex items-center justify-center md:justify-start gap-3 text-gray-300 hover:text-green-400 transition-colors group">

                            {{-- Perubahan: Tambahkan group-hover dan ganti warna ikon saat di hover --}}
                            <span class="p-2 bg-gray-800 rounded-lg group-hover:bg-green-400/10 transition-colors">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-green-500 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            </span>

                            <div>
                                <span class="block text-xs text-gray-500">Dikembangkan oleh</span>
                                <span
                                    class="font-medium text-gray-300 group-hover:text-white transition-colors">Uhycore</span>
                            </div>
                        </a>
                    </div>

                </div>
            </div>

            {{-- Copyright Bawah --}}
            <div class="mt-8 p-2 border-t border-gray-800 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} TRANSKOM. All rights reserved.
            </div>
    </div>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const track = document.getElementById("carsTrack");
            if (!track) return;

            const prev = document.getElementById("carsPrev");
            const next = document.getElementById("carsNext");

            const step = 320 + 24; // width card + gap

            prev?.addEventListener("click", () => {
                track.scrollBy({
                    left: -step,
                    behavior: "smooth"
                });
            });

            next?.addEventListener("click", () => {
                track.scrollBy({
                    left: step,
                    behavior: "smooth"
                });
            });
        });
    </script>

    </div>
</x-app-layout>
