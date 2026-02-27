<?php
$pageTitle = "TikTok Downloader";
$themeHex = "#7c3aed"; // Color Morado
include '../../partials/Includes/header.php';
?>

<main class="max-w-4xl mx-auto px-6 py-12 md:py-16" x-data="tiktokDownloader()">
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tight uppercase italic text-black">TikTok <span class="text-theme">Saver</span></h1>
        <p class="text-gray-500 font-bold uppercase text-[10px] md:text-xs tracking-[0.3em]">Descarga videos sin marca de agua</p>
    </div>

    <div class="tool-container bg-white rounded-[2rem] p-6 md:p-10 relative">
        <div class="space-y-8">
            <div class="space-y-3">
                <label class="text-xs font-black uppercase tracking-widest text-theme italic">Pegar Enlace de TikTok:</label>
                <input type="text" x-model="url" placeholder="https://www.tiktok.com/@usuario/video/..." class="input-style">
            </div>
            
            <button @click="checkVideo()" :disabled="loading" class="w-full bg-black text-white py-6 rounded-2xl font-black text-base uppercase tracking-widest hover-bg-theme transition-all shadow-xl flex items-center justify-center gap-4 group">
                <template x-if="!loading">
                    <div class="flex items-center gap-4">
                        <span>Analizar Enlace</span>
                        <i class="fas fa-search group-hover:scale-110 transition-transform"></i>
                    </div>
                </template>
                <template x-if="loading">
                    <div class="flex items-center gap-2">
                        <span class="loading-dots">Procesando</span>
                    </div>
                </template>
            </button>
        </div>

        <div x-show="result" x-cloak x-transition class="mt-10 pt-10 border-t-2 border-dashed border-gray-100">
            <div class="flex flex-col md:flex-row gap-8 items-center">
                <img :src="result?.cover" class="w-32 h-44 object-cover rounded-xl shadow-lg border-2 border-black">
                <div class="flex-grow space-y-4 w-full text-center md:text-left">
                    <p class="font-bold text-sm text-gray-600 line-clamp-2" x-text="result?.title"></p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <form action="process.php" method="POST">
                            <input type="hidden" name="url" :value="url">
                            <input type="hidden" name="format" value="no-wm">
                            <button type="submit" class="w-full bg-theme text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:opacity-90 transition">
                                <i class="fas fa-video mr-2"></i> Sin Marca (HD)
                            </button>
                        </form>

                        <form action="process.php" method="POST">
                            <input type="hidden" name="url" :value="url">
                            <input type="hidden" name="format" value="audio">
                            <button type="submit" class="w-full bg-black text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:opacity-90 transition">
                                <i class="fas fa-music mr-2"></i> Solo Audio
                            </button>
                        </form>

                        <template x-if="result?.images">
                            <form action="process.php" method="POST" class="sm:col-span-2">
                                <input type="hidden" name="url" :value="url">
                                <input type="hidden" name="format" value="images-zip">
                                <button type="submit" class="w-full bg-emerald-500 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition">
                                    <i class="fas fa-file-archive mr-2"></i> Descargar Todas las Fotos (.ZIP)
                                </button>
                            </form>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-24 space-y-12">
        <div class="text-center">
            <h2 class="text-3xl font-black uppercase tracking-tighter text-black italic">Características</h2>
            <div class="w-20 h-2 bg-theme mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
            <div class="bg-white p-8 rounded-3xl border-2 border-gray-100 shadow-sm">
                <div class="text-theme text-3xl font-black mb-4">HD</div>
                <h3 class="font-black uppercase text-sm mb-2 italic">Calidad Original</h3>
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider leading-relaxed">Obtén el video en la máxima resolución disponible.</p>
            </div>
            <div class="bg-white p-8 rounded-3xl border-2 border-gray-100 shadow-sm">
                <div class="text-theme text-3xl font-black mb-4"><i class="fas fa-ban"></i></div>
                <h3 class="font-black uppercase text-sm mb-2 italic">Sin Marca</h3>
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider leading-relaxed">Eliminamos el logo de TikTok automáticamente.</p>
            </div>
            <div class="bg-black text-white p-8 rounded-3xl border-2 border-black shadow-lg">
                <div class="text-theme text-3xl font-black mb-4"><i class="fas fa-music"></i></div>
                <h3 class="font-black uppercase text-sm mb-2 italic">MP3 Audio</h3>
                <p class="text-gray-500 text-[10px] font-bold uppercase tracking-wider leading-relaxed">Opción para extraer solo el audio del trend.</p>
            </div>
        </div>
    </div>
</main>

<script>
function tiktokDownloader() {
    return {
        url: '',
        loading: false,
        result: null,
        async checkVideo() {
            if (!this.url.includes('tiktok.com')) {
                alert('Por favor, ingresa un enlace válido de TikTok');
                return;
            }
            this.loading = true;
            this.result = null;
            try {
                const response = await fetch(`process.php?check=${encodeURIComponent(this.url)}`);
                const data = await response.json();
                if (data.code === 0) {
                    this.result = data.data;
                } else {
                    alert('No se pudo encontrar el video. Asegúrate de que sea público.');
                }
            } catch (e) {
                alert('Error al conectar con el servidor.');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>