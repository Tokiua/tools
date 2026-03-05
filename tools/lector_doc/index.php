<?php
$pageTitle = "Lumina Stream - PDF Master";
$themeHex = "#ef4444"; 
include '../../partials/Includes/header.php';
?>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    :root { --theme: #ef4444; }
    
    .card-unified {
        background: #ffffff;
        border-radius: 2.5rem;
        border: 2px solid #f3f4f6;
        transition: all 0.3s ease;
    }

    /* Contenedor del Visor */
    .viewer-wrapper {
        position: relative;
        width: 100%;
        height: 75vh;
        background: #1a1a1a;
        border-radius: 2rem;
        overflow: hidden;
        border: 3px solid #000;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Estado Fullscreen */
    .viewer-wrapper.is-fullscreen {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 9999;
        border-radius: 0;
        border: none;
    }

    iframe { width: 100%; height: 100%; border: none; display: block; }

    /* Botón Cerrar Flotante (Más abajo de la esquina) */
    .btn-exit-fs {
        position: absolute;
        top: 80px; /* Bajado para no tapar la barra de herramientas */
        right: 20px;
        z-index: 10000;
        background: var(--theme);
        color: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #000;
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        cursor: pointer;
        transition: transform 0.2s;
    }

    .btn-exit-fs:hover { transform: scale(1.1); }

    .info-box-nex {
        background: #fff;
        border: 3px solid #000;
        border-radius: 1.5rem;
        padding: 1.25rem;
        box-shadow: 6px 6px 0px #000;
    }

    [x-cloak] { display: none !important; }
</style>

<main class="max-w-5xl mx-auto px-4 py-10" x-data="luminaCore()">
    
    <div class="text-center mb-10" x-show="!fullScreen">
        <h1 class="text-4xl md:text-6xl font-black mb-2 tracking-tight uppercase italic text-black leading-none">
            Lumina <span class="text-theme">Stream</span>
        </h1>
        <p class="text-gray-400 font-bold uppercase text-[9px] tracking-[0.3em]">Memoria Volátil • Pro Mode</p>
    </div>

    <div class="card-unified p-4 md:p-6 shadow-2xl" :class="fullScreen ? 'p-0 border-none' : ''">
        
        <div x-show="!isLoaded" class="py-16 flex flex-col items-center justify-center text-center">
            <div @click="$refs.fileInput.click()" 
                 class="w-full max-w-md p-10 border-4 border-dashed border-gray-100 rounded-[3rem] cursor-pointer hover:border-theme transition-all bg-gray-50/50 group">
                <i class="fas fa-file-pdf text-5xl text-gray-300 group-hover:text-theme mb-6 transition-colors"></i>
                <h3 class="text-xl font-black uppercase italic mb-2">Abrir Documento</h3>
                <p class="text-gray-400 font-bold text-[10px] uppercase">Lectura segura en tiempo real</p>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak class="flex flex-col w-full">
            <div x-show="!fullScreen" class="flex justify-between items-center mb-4 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-theme animate-pulse"></div>
                    <span class="font-black text-black text-[10px] uppercase italic truncate max-w-[150px]" x-text="shortName"></span>
                </div>
                <div class="flex gap-2">
                    <button @click="toggleFS()" class="bg-black text-white px-4 py-2 rounded-xl font-black text-[9px] uppercase hover:bg-theme transition-colors">
                        <i class="fas fa-expand mr-1"></i> Pantalla Completa
                    </button>
                    <button @click="reset()" class="bg-gray-200 text-black px-4 py-2 rounded-xl font-black text-[9px] uppercase hover:bg-red-500 hover:text-white transition-colors">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="viewer-wrapper" :class="fullScreen ? 'is-fullscreen' : ''">
                <button x-show="fullScreen" @click="toggleFS()" class="btn-exit-fs" x-cloak>
                    <i class="fas fa-times"></i>
                </button>
                
                <iframe x-ref="pdfIframe" src="" allow="fullscreen"></iframe>
            </div>
        </div>
    </div>

    <div x-show="!fullScreen" class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="info-box-nex">
            <h4 class="font-black uppercase italic text-xs mb-2">Visualización Adaptativa</h4>
            <p class="text-gray-500 font-bold text-[10px] uppercase">
                El visor ajusta automáticamente el ancho del PDF según tu dispositivo (Móvil o PC).
            </p>
        </div>
        <div class="info-box-nex">
            <h4 class="font-black uppercase italic text-xs mb-2">Privacidad RAM</h4>
            <p class="text-gray-500 font-bold text-[10px] uppercase text-theme">
                Nada se almacena en Hostinger. Todo el procesamiento es local.
            </p>
        </div>
    </div>

    <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept=".pdf">
</main>

<script>
function luminaCore() {
    return {
        isLoaded: false,
        fullScreen: false,
        shortName: '',
        currentBlobUrl: null,
        viewerPath: '/herramienta/assets/pdfjs/web/viewer.html', 

        handleFile(e) {
            const file = e.target.files[0];
            if (!file || file.type !== 'application/pdf') return;

            if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);

            this.shortName = file.name;
            this.currentBlobUrl = URL.createObjectURL(file);
            this.isLoaded = true;

            this.$nextTick(() => {
                // Forzamos el modo "Fit" para que se adapte a cualquier pantalla desde el inicio
                const finalUrl = `${this.viewerPath}?file=${encodeURIComponent(this.currentBlobUrl)}#view=FitH`;
                this.$refs.pdfIframe.src = finalUrl;
            });
        },

        toggleFS() {
            this.fullScreen = !this.fullScreen;
            // Bloquear scroll del cuerpo cuando está en pantalla completa
            document.body.style.overflow = this.fullScreen ? 'hidden' : 'auto';
        },

        reset() {
            if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
            this.isLoaded = false;
            this.fullScreen = false;
            document.body.style.overflow = 'auto';
            this.currentBlobUrl = null;
            this.$refs.pdfIframe.src = '';
            this.$refs.fileInput.value = '';
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>