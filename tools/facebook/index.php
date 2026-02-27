<?php
$pageTitle = "FB Video Downloader";
$themeHex = "#1877F2"; // Azul oficial de Facebook
include '../../partials/Includes/header.php';
?>

<main class="max-w-4xl mx-auto px-6 py-12" x-data="fbLoader()">
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-4 uppercase italic">
            FB <span style="color: <?= $themeHex ?>;">Downloader</span>
        </h1>
        <p class="text-gray-500 font-bold uppercase text-[10px] tracking-[0.3em]">
            Descarga videos de Facebook en HD (Watch & Reels)
        </p>
    </div>

    <div class="tool-container bg-white rounded-[2rem] p-6 md:p-10 shadow-2xl border-4 border-black relative overflow-hidden">
        
        <div x-show="loading" x-cloak 
             class="absolute inset-0 bg-white/90 z-50 flex flex-col items-center justify-center rounded-[2rem]">
            <div class="w-12 h-12 border-4 border-t-[#1877F2] border-gray-200 rounded-full animate-spin mb-4"></div>
            <p class="font-black text-xs uppercase tracking-widest text-slate-800 animate-pulse">
                Conectando con servidores de Facebook...
            </p>
        </div>

        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <input type="text" x-model="url" 
                       placeholder="Pega el link de Facebook (Reel, Video o Watch)..." 
                       class="w-full bg-gray-100 border-4 border-gray-200 rounded-2xl py-4 px-6 font-bold outline-none focus:border-[#1877F2] transition-all">
                <button x-show="url" @click="url = ''" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
            <button @click="fetchMedia" 
                    class="bg-black text-white px-8 py-4 rounded-2xl font-black uppercase hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3 shadow-lg">
                <span>Obtener Video</span>
                <i class="fab fa-facebook-f"></i>
            </button>
        </div>

        <template x-if="mediaData">
            <div class="mt-10 animate-fade-in">
                <div class="flex flex-col md:flex-row gap-8 items-center bg-gray-50 p-6 rounded-3xl border-2 border-dashed border-gray-300">
                    
                    <div class="relative group shrink-0">
                        <img :src="mediaData.thumbnail" 
                             class="w-40 h-52 object-cover rounded-2xl shadow-lg border-2 border-black bg-gray-200">
                        <div class="absolute inset-0 flex items-center justify-center bg-black/20 rounded-2xl">
                             <i class="fas fa-check-circle text-white text-3xl"></i>
                        </div>
                    </div>
                    
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="font-black text-xl mb-4 uppercase italic tracking-tighter text-slate-800">
                            ¡Video procesado con éxito!
                        </h3>
                        <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                            <template x-for="link in mediaData.links">
                                <a :href="'download.php?url=' + encodeURIComponent(link.url) + '&name=' + encodeURIComponent(link.filename)"
                                   class="bg-[#1877F2] text-white px-6 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest hover:brightness-110 shadow-[4px_4px_0px_rgba(0,0,0,0.2)] flex items-center gap-2 transition-all hover:-translate-y-1">
                                    <span x-text="link.quality"></span> 
                                    <i class="fas fa-download"></i>
                                </a>
                            </template>
                        </div>
                        <p class="mt-6 text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                            <i class="fas fa-info-circle mr-1"></i> La descarga comenzará automáticamente al hacer clic.
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border-2 border-black/5 shadow-sm text-center">
            <div class="text-[#1877F2] mb-3"><i class="fas fa-shield-alt text-2xl"></i></div>
            <h4 class="font-black text-[10px] uppercase mb-2">Privacidad Total</h4>
            <p class="text-gray-500 text-[10px] leading-relaxed font-bold">No almacenamos tus videos. El proceso es directo de Facebook a tu dispositivo.</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border-2 border-black/5 shadow-sm text-center">
            <div class="text-[#1877F2] mb-3"><i class="fas fa-bolt text-2xl"></i></div>
            <h4 class="font-black text-[10px] uppercase mb-2">Alta Velocidad</h4>
            <p class="text-gray-500 text-[10px] leading-relaxed font-bold">Optimizamos la ruta de descarga para obtener la máxima velocidad de Hostinger.</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border-2 border-black/5 shadow-sm text-center">
            <div class="text-[#1877F2] mb-3"><i class="fas fa-mobile-alt text-2xl"></i></div>
            <h4 class="font-black text-[10px] uppercase mb-2">Mobile Ready</h4>
            <p class="text-gray-500 text-[10px] leading-relaxed font-bold">Diseñado para funcionar perfectamente en Android, iOS y computadoras.</p>
        </div>
    </div>
</main>

<?php include '../../partials/Includes/footer.php'; ?>

<script>
function fbLoader() {
    return {
        url: '',
        loading: false,
        mediaData: null,
        async fetchMedia() {
            const cleanUrl = this.url.trim();
            
            // Validación básica de URL
            if (!cleanUrl.match(/(facebook\.com|fb\.watch|fb\.com)/)) {
                return alert('Por favor, pega un enlace válido de Facebook.');
            }
            
            this.loading = true;
            this.mediaData = null;

            try {
                // Petición a tu archivo de procesamiento PHP
                const res = await fetch('process_fb.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'url=' + encodeURIComponent(cleanUrl)
                });
                
                const data = await res.json();
                
                if (data.success) {
                    this.mediaData = data;
                } else {
                    alert(data.error || 'No pudimos encontrar el video. Intenta con otro link.');
                }
            } catch (e) {
                console.error(e);
                alert('Error de conexión con Nexosyne Engine. Verifica tu internet.');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style>
    [x-cloak] { display: none !important; }
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>