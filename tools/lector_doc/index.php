<?php
$pageTitle = "Lumina Stream - Professional PDF Reader";
$themeHex = "#ef4444"; 
include '../../partials/Includes/header.php';
?>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    :root { --theme: #ef4444; }
    
    body { overflow-x: hidden; touch-action: pan-y; }

    .lumina-card {
        background: #111;
        border-radius: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        width: 100%;
    }

    /* Altura fija en móviles para asegurar que el iframe cargue */
    .viewer-wrapper {
        width: 100%;
        height: 60vh; 
        background: #222;
        position: relative;
        -webkit-overflow-scrolling: touch; /* Suavidad en iOS */
    }

    .fullscreen-mode {
        position: fixed !important;
        top: 0 !important; left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 999999 !important;
        background: #000;
        border-radius: 0 !important;
    }

    .fullscreen-mode .viewer-wrapper {
        height: 100% !important;
    }

    .btn-exit-fs {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1000001;
        background: var(--theme);
        color: white !important;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #000;
    }

    iframe { width: 100%; height: 100%; border: none; }

    /* Estilo de información - Neubrutalism */
    .info-box {
        background: #ffffff;
        border: 3px solid #000000;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 4px 4px 0px #000;
    }
    .info-box h4 { color: #000; font-weight: 900; text-transform: uppercase; font-size: 0.8rem; margin-bottom: 5px; border-bottom: 2px solid var(--theme); display: inline-block; }
    .info-box p { color: #000; font-size: 0.75rem; font-weight: 600; }

    [x-cloak] { display: none !important; }
</style>

<main class="max-w-7xl mx-auto px-2 py-4" x-data="luminaPro()">
    
    <div class="text-center mb-6" x-show="!isFullScreen">
        <h1 class="text-3xl font-black uppercase italic text-black leading-none">
            Lumina <span class="text-theme">Stream</span>
        </h1>
        <p class="text-[8px] tracking-[0.2em] font-bold text-gray-400 uppercase mt-1">Memoria Volátil • 2026</p>
    </div>

    <div class="lumina-card" :class="isFullScreen ? 'fullscreen-mode' : ''">
        
        <button x-show="isFullScreen" @click="isFullScreen = false" class="btn-exit-fs" x-cloak>
            <i class="fas fa-times"></i>
        </button>

        <div x-show="!isLoaded" class="p-10 flex flex-col items-center justify-center text-center">
            <div @click="$refs.fileInput.click()" class="border-2 border-dashed border-white/20 w-full py-10 rounded-3xl">
                <i class="fas fa-file-pdf text-3xl text-gray-600 mb-2"></i>
                <h3 class="text-white font-bold uppercase text-[10px]">Abrir PDF</h3>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak class="flex flex-col h-full w-full">
            <div x-show="!isFullScreen" class="bg-black p-2 flex justify-between items-center border-b border-white/10">
                <div class="flex items-center gap-2">
                    <span class="text-white text-[10px] font-bold uppercase" x-text="truncatedName"></span>
                </div>
                <div class="flex gap-2">
                    <button @click="isFullScreen = true" class="bg-white/10 text-white p-2 rounded-lg text-[10px]">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button @click="reset()" class="bg-red-600/20 text-red-500 p-2 rounded-lg text-[10px]">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="viewer-wrapper">
                <iframe x-ref="pdfIframe" src="" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4" x-show="!isFullScreen">
        <div class="info-box">
            <h4>Lectura Segura</h4>
            <p>El archivo se procesa en RAM. Si el lector no carga en tu celular, asegúrate de haber seleccionado un archivo .pdf válido.</p>
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
        truncatedName: '', // Nueva variable para el nombre corto
        currentBlobUrl: null,
        viewerUrl: '../../assets/pdfjs/web/viewer.html', 

        handleFile(e) {
            const file = e.target.files[0];
            if (file && file.type === 'application/pdf') {
                this.injectPDF(file);
            } else {
                alert('Por favor selecciona un archivo PDF válido.');
            }
        },

        injectPDF(file) {
            if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
            
            this.fileName = file.name;
            // Lógica de truncado: Si el nombre tiene más de 12 letras, corta y pone ...
            this.truncatedName = this.fileName.length > 15 
                ? this.fileName.substring(0, 12) + '...' 
                : this.fileName;

            this.isLoaded = true;
            this.currentBlobUrl = URL.createObjectURL(file);
            
            this.$nextTick(() => {
                /** * Ajuste para celular:
                 * Se añade #zoom=page-width para que el visor se ajuste al ancho del celular automáticamente.
                 */
                const finalUrl = `${this.viewerUrl}?file=${encodeURIComponent(this.currentBlobUrl)}#zoom=page-width`;
                this.$refs.pdfIframe.src = finalUrl;
            });
        },

        reset() {
            if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
            this.isLoaded = false;
            this.isFullScreen = false;
            this.fileName = '';
            this.truncatedName = '';
            this.$refs.pdfIframe.src = '';
            this.$refs.fileInput.value = '';
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>