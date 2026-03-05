<?php
$pageTitle = "Lumina Stream - Professional PDF Reader";
$themeHex = "#ef4444"; 
include '../../partials/Includes/header.php';
?>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    :root { --theme: #ef4444; }
    
    .lumina-card {
        background: #111;
        border-radius: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        transition: all 0.3s ease;
    }

    /* CONTENEDOR DEL VISOR */
    .viewer-wrapper {
        width: 100%;
        height: 70vh; /* Altura estándar en la página */
        background: #222;
        position: relative;
    }

    /* MODAL DE PANTALLA COMPLETA */
    .fullscreen-mode {
        position: fixed !important;
        top: 0; left: 0;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 99999;
        background: #000;
        border-radius: 0 !important;
    }

    .fullscreen-mode .viewer-wrapper {
        height: 100vh !important;
    }

    /* BOTÓN X PARA CERRAR FULLSCREEN */
    .btn-exit-fs {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 100000;
        background: var(--theme);
        color: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        border: none;
        font-size: 20px;
        transition: transform 0.2s;
    }
    .btn-exit-fs:hover { transform: scale(1.1); }

    iframe { width: 100%; height: 100%; border: none; }

    .dropzone {
        border: 3px dashed rgba(255,255,255,0.1);
        transition: 0.3s;
    }
    .dropzone:hover { border-color: var(--theme); background: rgba(239, 68, 68, 0.03); }

    [x-cloak] { display: none !important; }
</style>

<main class="max-w-7xl mx-auto px-4 py-8" x-data="luminaPro()">
    
    <div class="text-center mb-8" x-show="!isFullScreen">
        <h1 class="text-4xl md:text-5xl font-black uppercase italic text-black">
            Lumina <span class="text-theme">Stream</span>
        </h1>
        <p class="text-[10px] tracking-[0.4em] font-bold text-gray-500 uppercase mt-2">Tecnología de Memoria Volátil • Nexosyne 2026</p>
    </div>

    <div class="lumina-card" :class="isFullScreen ? 'fullscreen-mode' : ''">
        
        <button x-show="isFullScreen" @click="isFullScreen = false" class="btn-exit-fs" x-cloak>
            <i class="fas fa-times"></i>
        </button>

        <div x-show="!isLoaded" class="p-8 md:p-20 flex flex-col items-center justify-center text-center">
            <div @click="$refs.fileInput.click()" 
                 @dragover.prevent @drop.prevent="handleDrop"
                 class="dropzone w-full max-w-xl py-16 md:py-24 rounded-[2.5rem] cursor-pointer group">
                <div class="w-20 h-20 bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:bg-theme transition-all">
                    <i class="fas fa-file-upload text-3xl text-gray-500 group-hover:text-white"></i>
                </div>
                <h3 class="text-white font-black uppercase tracking-widest text-sm">Cargar Nuevo Documento</h3>
                <p class="text-gray-500 text-[9px] mt-4 uppercase font-bold">Sin almacenamiento en servidor</p>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak class="flex flex-col">
            <div x-show="!isFullScreen" class="bg-black p-4 flex justify-between items-center border-b border-white/10">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-pdf text-theme"></i>
                    <span class="text-white text-[11px] font-bold uppercase truncate max-w-[200px]" x-text="fileName"></span>
                </div>

                <div class="flex items-center gap-2">
                    <button @click="isFullScreen = true" class="bg-white/5 hover:bg-theme text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all">
                        <i class="fas fa-expand mr-1"></i> Pantalla Completa
                    </button>
                    <button @click="reset()" class="bg-white/5 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all">
                        <i class="fas fa-trash-alt mr-1"></i> Limpiar
                    </button>
                </div>
            </div>

            <div class="viewer-wrapper">
                <iframe x-ref="pdfIframe" src=""></iframe>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4" x-show="!isFullScreen">
        <div class="p-6 rounded-2xl bg-black border border-white/5">
            <h4 class="text-theme font-black uppercase text-[10px] tracking-widest mb-2">Seguridad de Datos</h4>
            <p class="text-gray-500 text-xs italic">Nexosyne utiliza Blob URLs para que tus archivos se procesen únicamente en la memoria volátil de tu navegador.</p>
        </div>
        <div class="p-6 rounded-2xl bg-black border border-white/5">
            <h4 class="text-theme font-black uppercase text-[10px] tracking-widest mb-2">Instrucciones</h4>
            <p class="text-gray-500 text-xs italic">Usa el botón de pantalla completa para una lectura inmersiva sin distracciones.</p>
        </div>
    </div>

    <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept=".pdf">
</main>

<script>
function luminaPro() {
    return {
        isLoaded: false,
        isFullScreen: false,
        fileName: '',
        currentBlobUrl: null,
        viewerUrl: '/herramienta/assets/pdfjs/web/viewer.html', 

        handleFile(e) {
            const file = e.target.files[0];
            if (file) this.injectPDF(file);
        },

        handleDrop(e) {
            const file = e.dataTransfer.files[0];
            if (file && file.type === 'application/pdf') this.injectPDF(file);
        },

        injectPDF(file) {
            if (this.currentBlobUrl) {
                URL.revokeObjectURL(this.currentBlobUrl);
            }
            this.fileName = file.name;
            this.isLoaded = true;
            this.currentBlobUrl = URL.createObjectURL(file);
            
            this.$nextTick(() => {
                this.$refs.pdfIframe.src = `${this.viewerUrl}?file=${encodeURIComponent(this.currentBlobUrl)}`;
            });
        },

        reset() {
            if (this.currentBlobUrl) {
                URL.revokeObjectURL(this.currentBlobUrl);
            }
            this.isLoaded = false;
            this.isFullScreen = false;
            this.fileName = '';
            this.currentBlobUrl = null;
            this.$refs.pdfIframe.src = '';
            this.$refs.fileInput.value = '';
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>