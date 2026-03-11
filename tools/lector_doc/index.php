<?php
$pageTitle = "Lumina Stream - PDF Master";
$themeHex = "#ef4444"; 
include '../../partials/Includes/header.php';
?>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    :root { --theme: #ef4444; }
    .card-unified { background: #ffffff; border-radius: 2.5rem; border: 2px solid #f3f4f6; overflow: hidden; }
    
    .viewer-wrapper {
        position: relative;
        width: 100%;
        height: 75vh;
        background: #1a1a1a;
        border-radius: 2rem;
        overflow: hidden;
        border: 3px solid #000;
        transition: all 0.3s ease;
    }

    /* Pantalla Completa */
    .viewer-wrapper.is-fullscreen {
        position: fixed !important;
        top: 0; left: 0;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 9999;
        border-radius: 0;
        border: none;
    }

    iframe { width: 100%; height: 100%; border: none; display: block; }

    /* Botón X Flotante (Bajo la barra de herramientas original) */
    .btn-exit-fs {
        position: absolute;
        top: 90px;
        right: 25px;
        z-index: 10000;
        background: var(--theme);
        color: white;
        width: 45px; height: 45px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 3px solid #000;
        box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        cursor: pointer;
        font-size: 1.2rem;
    }

    [x-cloak] { display: none !important; }
</style>

<main class="max-w-5xl mx-auto px-4 py-10" x-data="luminaCore()">
    
    <div class="text-center mb-10" x-show="!fullScreen">
        <h1 class="text-4xl md:text-6xl font-black mb-2 text-black uppercase italic">
            Lumina <span class="text-theme">Stream</span>
        </h1>
        <p class="text-gray-400 font-bold uppercase text-[9px] tracking-[0.3em]">Memoria Volátil • Nexosyne 2026</p>
    </div>

    <div class="card-unified p-4 md:p-6 shadow-2xl" :class="fullScreen ? 'p-0 border-none' : ''">
        
        <div x-show="!isLoaded" class="py-16 flex flex-col items-center justify-center">
            <div @click="$refs.fileInput.click()" class="w-full max-w-md p-10 border-4 border-dashed border-gray-100 rounded-[3rem] cursor-pointer hover:border-theme bg-gray-50/50 transition-all group text-center">
                <i class="fas fa-file-pdf text-5xl text-gray-300 group-hover:text-theme mb-6 transition-colors"></i>
                <h3 class="text-xl font-black uppercase italic">Abrir Documento</h3>
                <p class="text-gray-400 font-bold text-[10px] uppercase">Compatible con XAMPP y Hostinger</p>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak class="flex flex-col w-full">
            <div x-show="!fullScreen" class="flex justify-between items-center mb-4 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                <span class="font-black text-black text-[10px] uppercase italic truncate max-w-[200px]" x-text="shortName"></span>
                <div class="flex gap-2">
                    <button @click="toggleFS()" class="bg-black text-white px-4 py-2 rounded-xl font-black text-[9px] uppercase hover:bg-theme transition-colors">PANTALLA COMPLETA</button>
                    <button @click="reset()" class="bg-gray-200 text-black px-4 py-2 rounded-xl font-black text-[9px] uppercase hover:bg-red-500 hover:text-white transition-colors">BORRAR</button>
                </div>
            </div>

            <div class="viewer-wrapper" :class="fullScreen ? 'is-fullscreen' : ''">
                <button x-show="fullScreen" @click="toggleFS()" class="btn-exit-fs"><i class="fas fa-times"></i></button>
                <iframe x-ref="pdfIframe" src=""></iframe>
            </div>
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

        // LÓGICA DE RUTA INTELIGENTE
        getViewerPath() {
            const currentPath = window.location.pathname;
            // Si la URL contiene "herramienta", estamos en local (XAMPP)
            if (currentPath.includes('/herramienta/')) {
                return '/herramienta/assets/pdfjs/web/viewer.html';
            } 
            // Si no, estamos en Hostinger (Raíz)
            return '/assets/pdfjs/web/viewer.html';
        },

        handleFile(e) {
            const file = e.target.files[0];
            if (!file || file.type !== 'application/pdf') return;

            if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
            
            this.shortName = file.name;
            this.currentBlobUrl = URL.createObjectURL(file);
            this.isLoaded = true;

            this.$nextTick(() => {
                const path = this.getViewerPath();
                // Usamos zoom automático por ancho de página
                const finalUrl = `${path}?file=${encodeURIComponent(this.currentBlobUrl)}#view=FitH`;
                this.$refs.pdfIframe.src = finalUrl;
            });
        },

        toggleFS() {
            this.fullScreen = !this.fullScreen;
            document.body.style.overflow = this.fullScreen ? 'hidden' : 'auto';
        },

        reset() {
            if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
            this.isLoaded = false;
            this.fullScreen = false;
            document.body.style.overflow = 'auto';
            this.$refs.pdfIframe.src = '';
            this.$refs.fileInput.value = '';
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>