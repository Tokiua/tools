<?php
$pageTitle = "Lumina Stream - Professional PDF Reader";
$themeHex = "#ef4444"; 
include '../../partials/Includes/header.php';
?>

<style>
    [x-cloak] { display: none !important; }
    
    #lumina-viewport {
        background-color: #ffffff !important;
        width: 100%;
        height: 80vh;
        border: 2px solid #000;
        border-radius: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    #lumina-viewport:fullscreen {
        width: 100vw !important;
        height: 100vh !important;
        border-radius: 0;
        border: none;
    }

    iframe {
        width: 100%;
        height: 100%;
        border: none;
        background: #ffffff;
    }

    .btn-lumina {
        background-color: #000;
        color: #fff;
        border: 2px solid #ef4444;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-lumina:hover {
        background-color: #ef4444;
        transform: translateY(-2px);
    }

    .exit-fs {
        position: fixed; top: 25px; right: 25px; z-index: 9999;
        background: #ef4444; color: white; width: 50px; height: 50px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        border: 2px solid white; box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }

    /* Animación de escaneo estilo TikTok Master */
    .scan-line {
        width: 100%;
        height: 4px;
        background: #ef4444;
        position: absolute;
        top: 0;
        left: 0;
        box-shadow: 0 0 15px #ef4444;
        animation: scan 2s linear infinite;
    }
    @keyframes scan {
        0% { top: 0%; }
        100% { top: 100%; }
    }
</style>

<main class="max-w-5xl mx-auto px-6 py-12 md:py-16" x-data="luminaReader()">
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tight uppercase italic text-black">
            Lumina <span class="text-theme">Stream</span>
        </h1>
        <h3 class="text-xl md:text-2xl font-black uppercase italic tracking-tighter text-black mb-4">
            Visualizador <span class="text-theme">PDF de Alta Fidelidad</span> HD
        </h3>
        <p class="text-gray-500 font-bold uppercase text-[10px] md:text-xs tracking-[0.3em]">
            Lectura Segura • Motor Volátil de un solo uso
        </p>
    </div>

    <div class="card-unified p-6 md:p-10 relative shadow-2xl">
        
        <div x-show="!isLoaded" class="space-y-8">
            <div @click="$refs.fileInput.click()" 
                 class="w-full border-4 border-dashed border-gray-100 rounded-[3rem] py-24 flex flex-col items-center justify-center cursor-pointer hover:border-theme hover:bg-red-50/30 transition-all group">
                <div class="w-20 h-20 bg-gray-50 text-gray-200 rounded-3xl flex items-center justify-center mb-6 group-hover:bg-theme group-hover:text-white group-hover:rotate-12 transition-all shadow-sm">
                    <i class="fas fa-file-pdf text-4xl"></i>
                </div>
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Cargar Archivo PDF</h3>
                <p class="text-[9px] font-bold text-gray-300 mt-2 uppercase tracking-widest italic">Solo se admiten documentos .pdf</p>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak x-transition class="space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-900 p-5 rounded-[2rem] shadow-xl">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-theme text-white rounded-2xl flex items-center justify-center shadow-lg -rotate-3">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-theme uppercase tracking-widest">Vista Protegida</p>
                        <p class="font-extrabold text-white text-sm truncate max-w-[250px]" x-text="fileName"></p>
                    </div>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button @click="toggleFullscreen" class="flex-1 md:flex-none bg-white/10 text-white px-6 py-4 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-white/20 transition-all">
                        <i class="fas fa-expand-alt mr-2"></i> Pantalla Completa
                    </button>
                    <button @click="confirmChange" class="flex-1 md:flex-none btn-lumina px-6 py-4 rounded-xl font-black text-[10px] uppercase tracking-widest">
                        <i class="fas fa-sync-alt mr-2"></i> Otro PDF
                    </button>
                </div>
            </div>

            <div id="lumina-viewport">
                <template x-if="fileURL">
                    <iframe :src="fileURL"></iframe>
                </template>

                <div x-show="loading" class="absolute inset-0 bg-white flex flex-col items-center justify-center z-50">
                    <div class="scan-line"></div>
                    <div class="w-14 h-14 border-4 border-theme border-t-transparent rounded-full animate-spin mb-4"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-black">Analizando PDF en RAM...</p>
                </div>

                <button x-show="isFullscreen" @click="toggleFullscreen" class="exit-fs">
                    <i class="fas fa-compress text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="mt-24 space-y-20">
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="text-3xl font-black uppercase tracking-tighter text-black italic mb-6">¿Qué es Lumina Stream?</h2>
            <p class="text-gray-500 font-bold text-sm leading-relaxed">
                Es un visor de PDF volátil diseñado para la lectura de documentos sin dejar rastro. Nuestra tecnología de túnel permite visualizar el contenido sin necesidad de descargarlo localmente, ideal para documentos confidenciales.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-[2.5rem] border-2 border-gray-50 shadow-sm text-center hover:border-theme/20 transition-all">
                <div class="w-12 h-12 bg-red-100 text-theme rounded-full flex items-center justify-center mx-auto mb-6 font-black text-xl">1</div>
                <h3 class="font-black uppercase text-xs mb-3 tracking-widest text-black">Sube el PDF</h3>
                <p class="text-gray-400 text-[10px] font-bold uppercase leading-relaxed">Solo arrastra el archivo. El núcleo Lumina lo detectará al instante.</p>
            </div>
            <div class="bg-white p-8 rounded-[2.5rem] border-2 border-gray-50 shadow-sm text-center hover:border-theme/20 transition-all">
                <div class="w-12 h-12 bg-red-100 text-theme rounded-full flex items-center justify-center mx-auto mb-6 font-black text-xl">2</div>
                <h3 class="font-black uppercase text-xs mb-3 tracking-widest text-black">Apertura Segura</h3>
                <p class="text-gray-400 text-[10px] font-bold uppercase leading-relaxed">El documento se abre en un entorno aislado y sin scripts externos.</p>
            </div>
            <div class="bg-black p-8 rounded-[3rem] text-center shadow-2xl border-2 border-theme/30">
                <div class="w-12 h-12 bg-theme text-white rounded-full flex items-center justify-center mx-auto mb-6 font-black text-xl shadow-[0_0_15px_#ef4444]">3</div>
                <h3 class="font-black uppercase text-xs mb-3 tracking-widest text-white">Privacidad Total</h3>
                <p class="text-gray-500 text-[10px] font-bold uppercase leading-relaxed">Al cerrar o cambiar de PDF, la memoria RAM se purga automáticamente.</p>
            </div>
        </div>

        <div class="card-unified p-8 md:p-12 bg-gray-50/50 border-dashed border-2 border-gray-200">
            <div class="flex flex-col md:flex-row gap-10 items-center">
                <div class="md:w-1/3">
                    <div class="text-5xl mb-4 text-theme"><i class="fas fa-microchip"></i></div>
                    <h2 class="text-2xl font-black uppercase tracking-tighter italic">Visor <span class="text-theme">Cifrado</span></h2>
                </div>
                <div class="md:w-2/3 space-y-4">
                    <p class="text-gray-600 font-bold text-xs leading-relaxed uppercase tracking-wide">
                        <span class="text-black">Lumina Stream</span> no almacena tus documentos. 
                    </p>
                    <p class="text-gray-500 text-[11px] font-semibold leading-relaxed">
                        Nuestro sistema de <span class="text-theme italic">Streaming Bridge</span> utiliza buffers binarios efímeros. Esto significa que el servidor actúa como un puente directo que entrega los datos a tu pantalla. Una vez visualizado, el servidor no conserva copias físicas, cumpliendo estrictamente con el protocolo de residuo cero.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept=".pdf">
</main>

<script>
function luminaReader() {
    return {
        isLoaded: false, loading: false, isFullscreen: false, fileName: '', fileURL: '',
        
        async handleFile(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validación extra por si acaso
            if (file.type !== 'application/pdf') {
                return alert('Por favor, selecciona solo archivos PDF originales.');
            }

            this.loading = true;
            this.isLoaded = true;
            this.fileName = file.name;

            const formData = new FormData();
            formData.append('documento', file);

            try {
                const res = await fetch('process.php', { method: 'POST', body: formData });
                const blob = await res.blob();
                
                if (this.fileURL) URL.revokeObjectURL(this.fileURL);
                this.fileURL = URL.createObjectURL(blob) + '#toolbar=1&navpanes=0';
            } catch (err) {
                alert('Error al sincronizar con el motor PDF.');
                this.isLoaded = false;
            } finally {
                setTimeout(() => { this.loading = false; }, 1000);
            }
        },

        toggleFullscreen() {
            const el = document.getElementById('lumina-viewport');
            if (!document.fullscreenElement) {
                el.requestFullscreen();
                this.isFullscreen = true;
            } else {
                document.exitFullscreen();
                this.isFullscreen = false;
            }
        },

        confirmChange() {
            if (confirm("¿Quieres cambiar el documento? El PDF actual se borrará de la sesión.")) {
                this.isLoaded = false;
                this.fileURL = '';
                this.$refs.fileInput.click();
            }
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>