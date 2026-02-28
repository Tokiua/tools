<?php
$pageTitle = "TikTok Multimedia Saver";
$themeHex = "#7c3aed"; 
include '../../partials/Includes/header.php';
?>

<main class="max-w-4xl mx-auto px-6 py-12 md:py-16" x-data="tiktokDownloader()">
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tight uppercase italic text-black">
            TikTok <span class="text-theme">Master</span>
        </h1>
        <p class="text-gray-500 font-bold uppercase text-[10px] md:text-xs tracking-[0.3em]">
            Analizador de Perfil, Video y Fotos
        </p>
    </div>

    <div class="card-unified p-6 md:p-10 relative shadow-2xl">
        <div class="space-y-8">
            <div class="space-y-3">
                <label class="text-xs font-black uppercase tracking-widest text-theme italic">Enlace del contenido:</label>
                <input type="text" x-model="url" 
                    placeholder="https://www.tiktok.com/..." 
                    class="w-full p-5 rounded-2xl border-2 border-gray-100 focus:border-purple-600 outline-none transition-all font-bold text-slate-700 bg-gray-50/50">
            </div>
            
            <button @click="checkContent()" :disabled="loading" 
                class="w-full bg-black text-white py-6 rounded-2xl font-black text-base uppercase tracking-widest hover:bg-purple-700 transition-all shadow-xl flex items-center justify-center gap-4 group disabled:opacity-50">
                <span x-show="!loading">Escanear TikTok</span>
                <span x-show="loading" class="flex items-center gap-3">
                    <i class="fas fa-circle-notch animate-spin"></i> Obteniendo datos...
                </span>
            </button>
        </div>

        <div x-show="result" x-cloak x-transition class="mt-10 pt-10 border-t-2 border-dashed border-gray-100">
            
            <div class="flex items-center gap-4 mb-8 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                <img :src="result?.author_avatar" class="w-12 h-12 rounded-full border-2 border-theme shadow-sm">
                <div>
                    <p class="text-[10px] font-black text-theme uppercase tracking-widest">Creador</p>
                    <p class="font-extrabold text-black" x-text="'@' + result?.author_name"></p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-8 items-start">
                <div class="relative mx-auto md:mx-0">
                    <img :src="result?.cover" class="w-40 h-56 object-cover rounded-2xl shadow-2xl border-2 border-black">
                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-black text-white text-[9px] font-black px-4 py-1 rounded-full whitespace-nowrap border border-gray-800">
                        <span x-text="result?.is_video ? 'VIDEO HD' : 'CARRUSEL'"></span>
                    </div>
                </div>

                <div class="flex-grow space-y-6 w-full">
                    <p class="font-bold text-slate-600 leading-snug italic" x-text="result?.title"></p>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <template x-if="result?.is_video">
                            <form action="process.php" method="POST">
                                <input type="hidden" name="action" value="download_stream">
                                <input type="hidden" name="url" :value="result?.video">
                                <input type="hidden" name="name" :value="'nexosyne_' + result?.id + '.mp4'">
                                <button class="w-full bg-purple-600 text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-purple-700 transition shadow-lg flex items-center justify-center gap-3">
                                    <i class="fas fa-download"></i> Descargar Video sin Marca
                                </button>
                            </form>
                        </template>

                        <template x-if="!result?.is_video">
                            <form action="process.php" method="POST">
                                <input type="hidden" name="action" value="download_images_zip">
                                <input type="hidden" name="images" :value="JSON.stringify(result?.images)">
                                <input type="hidden" name="id" :value="result?.id">
                                <button class="w-full bg-emerald-500 text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 transition shadow-lg flex items-center justify-center gap-3">
                                    <i class="fas fa-file-zipper"></i> Descargar Todas las Fotos (.ZIP)
                                </button>
                            </form>
                        </template>

                        <form action="process.php" method="POST">
                            <input type="hidden" name="action" value="download_stream">
                            <input type="hidden" name="url" :value="result?.music">
                            <input type="hidden" name="name" :value="'nexosyne_audio_' + result?.id + '.mp3'">
                            <button class="w-full bg-black text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-900 transition shadow-lg flex items-center justify-center gap-3 border border-gray-800">
                                <i class="fas fa-music"></i> Extraer Audio Original
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-24 space-y-20">
        
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="text-3xl font-black uppercase tracking-tighter text-black italic mb-6">¿Qué es Nexosyne TikTok Master?</h2>
            <p class="text-gray-500 font-bold text-sm leading-relaxed">
                Es una herramienta de extracción multimedia de alto rendimiento diseñada para obtener contenido de TikTok (Videos HD, Carruseles de Fotos y Audio MP3) con la máxima fidelidad original y sin marcas de agua.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-[2.5rem] border-2 border-gray-50 shadow-sm text-center">
                <div class="w-12 h-12 bg-purple-100 text-theme rounded-full flex items-center justify-center mx-auto mb-6 font-black text-xl">1</div>
                <h3 class="font-black uppercase text-xs mb-3 tracking-widest">Copia el Link</h3>
                <p class="text-gray-400 text-[10px] font-bold uppercase leading-relaxed">Desde TikTok, haz clic en "Compartir" y luego en "Copiar enlace".</p>
            </div>
            <div class="bg-white p-8 rounded-[2.5rem] border-2 border-gray-50 shadow-sm text-center">
                <div class="w-12 h-12 bg-purple-100 text-theme rounded-full flex items-center justify-center mx-auto mb-6 font-black text-xl">2</div>
                <h3 class="font-black uppercase text-xs mb-3 tracking-widest">Analiza el Contenido</h3>
                <p class="text-gray-400 text-[10px] font-bold uppercase leading-relaxed">Pega el link arriba y nuestro núcleo identificará si es un video o un álbum.</p>
            </div>
            <div class="bg-black p-8 rounded-[2.5rem] text-center shadow-2xl">
                <div class="w-12 h-12 bg-theme text-white rounded-full flex items-center justify-center mx-auto mb-6 font-black text-xl">3</div>
                <h3 class="font-black uppercase text-xs mb-3 tracking-widest text-white">Descarga Instantánea</h3>
                <p class="text-gray-500 text-[10px] font-bold uppercase leading-relaxed">Elige el formato y obtén tu archivo al momento sin esperas.</p>
            </div>
        </div>

        <div class="card-unified p-8 md:p-12 bg-gray-50/50 border-dashed">
            <div class="flex flex-col md:flex-row gap-10 items-center">
                <div class="md:w-1/3">
                    <div class="text-5xl mb-4 text-theme"><i class="fas fa-shield-halved"></i></div>
                    <h2 class="text-2xl font-black uppercase tracking-tighter italic">Tecnología <span class="text-theme">Volátil</span></h2>
                </div>
                <div class="md:w-2/3 space-y-4">
                    <p class="text-gray-600 font-bold text-xs leading-relaxed uppercase tracking-wide">
                        A diferencia de otros descargadores, <span class="text-black">Nexosyne no almacena tus archivos</span>. 
                    </p>
                    <p class="text-gray-500 text-[11px] font-semibold leading-relaxed">
                        Nuestra infraestructura utiliza un sistema de <span class="text-theme italic">Streaming Bridge</span>: el servidor actúa únicamente como un túnel que toma los datos de origen y los entrega directamente a tu navegador en tiempo real. Al finalizar la transferencia, la memoria RAM se libera automáticamente, garantizando que no existan copias de tu contenido en nuestro servidor.
                    </p>
                </div>
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
        async checkContent() {
            if (!this.url.includes('tiktok.com')) return alert('Enlace inválido');
            this.loading = true;
            this.result = null;
            try {
                const res = await fetch(`process.php?check=${encodeURIComponent(this.url)}`);
                const data = await res.json();
                if (data.id) {
                    this.result = data;
                } else {
                    alert('No se pudo encontrar el contenido. Verifica que sea público.');
                }
            } catch (e) {
                alert('Error en Nexosyne Core.');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>