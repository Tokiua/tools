<?php
$pageTitle = "Lumina Stream - Professional PDF Reader";
$themeHex = "#ef4444"; 
include '../../partials/Includes/header.php';
?>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    :root { --theme: #ef4444; }
    
    /* Layout base para evitar scroll innecesario */
    body { overflow-x: hidden; }

    .lumina-card {
        background: #111;
        border-radius: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        width: 100%;
    }

    /* CONTENEDOR DINÁMICO: Se adapta al alto de la pantalla */
    .viewer-wrapper {
        width: 100%;
        height: 70vh; /* Altura responsiva predeterminada */
        background: #222;
        position: relative;
    }

    /* PANTALLA COMPLETA REAL */
    .fullscreen-mode {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 999999 !important;
        background: #000;
        border-radius: 0 !important;
        margin: 0 !important;
    }

    .fullscreen-mode .viewer-wrapper {
        height: 100vh !important;
        flex-grow: 1;
    }

    /* BOTÓN X - Ajustado para no estorbar controles de PDF.js */
    .btn-exit-fs {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1000000;
        background: var(--theme);
        color: white !important;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid #000;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    iframe { 
        width: 100%; 
        height: 100%; 
        border: none;
        display: block;
    }

    /* Cajas de información con estilo pedido */
    .info-box {
        background: #ffffff;
        border: 3px solid #000000;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 6px 6px 0px #000; /* Efecto retro/dinámico */
    }
    .info-box h4 {
        color: #000;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 0.9rem;
        margin-bottom: 8px;
        display: block;
        border-bottom: 2px solid var(--theme);
    }
    .info-box p {
        color: #000;
        font-size: 0.85rem;
        font-weight: 500;
        margin: 0;
    }

    /* Ajuste para móviles */
    @media (max-width: 768px) {
        .viewer-wrapper { height: 60vh; }
        .mobile-filename { max-width: 100px; }
    }

    [x-cloak] { display: none !important; }
</style>

<main class="max-w-7xl mx-auto px-2 md:px-4 py-6" x-data="luminaPro()">
    
    <div class="text-center mb-6" x-show="!isFullScreen">
        <h1 class="text-4xl md:text-6xl font-black uppercase italic text-black leading-none">
            Lumina <span class="text-theme">Stream</span>
        </h1>
        <p class="text-[9px] tracking-[0.2em] font-bold text-gray-400 uppercase mt-2">Tecnología de Memoria Volátil • 2026</p>
    </div>

    <div class="lumina-card" :class="isFullScreen ? 'fullscreen-mode' : ''">
        
        <button x-show="isFullScreen" @click="isFullScreen = false" class="btn-exit-fs" x-cloak>
            <i class="fas fa-times"></i>
        </button>

        <div x-show="!isLoaded" class="p-8 md:p-20 flex flex-col items-center justify-center text-center">
            <div @click="$refs.fileInput.click()" 
                 class="border-2 border-dashed border-white/20 w-full max-w-md py-12 rounded-3xl cursor-pointer hover:border-theme transition-colors">
                <i class="fas fa-file-pdf text-4xl text-gray-600 mb-4"></i>
                <h3 class="text-white font-bold uppercase text-xs">Tocar para abrir PDF</h3>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak class="flex flex-col h-full w-full">
            <div x-show="!isFullScreen" class="bg-black p-2 md:p-3 flex justify-between items-center border-b border-white/10">
                <div class="flex items-center gap-2">
                    <span class="text-white text-[10px] font-bold uppercase mobile-filename" x-text="fileName"></span>
                </div>
                <div class="flex gap-2">
                    <button @click="isFullScreen = true" class="bg-white/10 text-white p-2 rounded-lg text-[10px] font-black uppercase">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button @click="reset()" class="bg-red-600/20 text-red-500 p-2 rounded-lg text-[10px] font-black">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="viewer-wrapper">
                <iframe x-ref="pdfIframe" src="" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4" x-show="!isFullScreen">
        <div class="info-box">
            <h4>Seguridad Local</h4>
            <p>El documento se lee desde la memoria de tu dispositivo. No se almacena nada en el servidor.</p>
        </div>
        <div class="info-box">
            <h4>Optimización</h4>
            <p>Si el lector se ve pequeño, usa el botón de ampliar para ajustar el PDF a toda tu pantalla.</p>
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
        // Ruta relativa desde la carpeta actual para encontrar assets en la raíz
        viewerUrl: '../../assets/pdfjs/web/viewer.html', 

        handleFile(e) {
            const file = e.target.files[0];
            if (file) this.injectPDF(file);
        },

        injectPDF(file) {
            if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
            this.fileName = file.name;
            this.isLoaded = true;
            this.currentBlobUrl = URL.createObjectURL(file);
            
            this.$nextTick(() => {
                // Forzamos el src del iframe
                this.$refs.pdfIframe.src = `${this.viewerUrl}?file=${encodeURIComponent(this.currentBlobUrl)}`;
            });
        },

        reset() {
            if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
            this.isLoaded = false;
            this.isFullScreen = false;
            this.fileName = '';
            this.$refs.pdfIframe.src = '';
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>