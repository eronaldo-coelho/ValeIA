<div class="md:hidden flex items-center justify-between bg-white border-b border-slate-200 h-16 px-4 fixed w-full z-50 shadow-sm">
    <!-- Wrapper da Logo com posicionamento absoluto para "vazar" da barra -->
    <div class="relative h-full flex items-center w-32">
        <img src="{{ asset('imagens/logo.png') }}" class="absolute top-1 left-0 h-24 w-auto object-contain max-w-none drop-shadow-sm transition-transform hover:scale-105" alt="Logo">
    </div>
    
    <!-- Botão do Menu -->
    <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 focus:outline-none p-2 hover:bg-slate-100 rounded-lg transition">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
    </button>
</div>