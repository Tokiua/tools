<?php
$pageTitle = "Lumina Stream - PDF Master";
$themeHex = "#ef4444"; 
include '../../partials/Includes/header.php';
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.3.0/pdfobject.min.js"></script>

<style>
    .viewer-wrapper {
        position: relative; 
        width: 100%; 
        height: 75vh;
        background: #1a1a1a; 
        border-radius: 2rem;
        overflow: hidden; 
        border: 3px solid #000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    /* Estilo para el contenedor del PDF */
    .pdfobject-container { 
        width: 100%; 
        height: 100%; 
    }
    
    .viewer-wrapper.is-fullscreen {
        position: fixed !important; 
        top: 0; 
        left: 0;
        width: 100vw !important; 
        height: 100vh !important;
        z-index: 9999; 
        border-radius: 0;
    }

    /* Botón de cerrar pantalla completa mejorado */
    .btn-exit-fs {
        position: absolute; 
        top: 80px; /* Bajado para no tapar herramientas del PDF */
        right: 25px;
        z-index: 10001; 
        background: #ef4444;
        color: white; 
        width: 40px; 
        height: 40px;
        border-radius: 12px; 
        display: flex; 
        align-items: center; 
        justify-content: center;
        border: 2px solid #000; 
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        transition: transform 0.2s;
    }
    .btn-exit-fs:hover { transform: scale(1.1); }
</style>

<main class="max-w-5xl mx-auto px-4 py-10" x-data="luminaCore()">
    <div class="text-center mb-10" x-show="!fullScreen">
        <h1 class="text-4xl font-black mb-2 text-black uppercase italic">
            Lumina <span class="text-theme">Stream</span>
        </h1>
        <p class="text-gray-400 font-bold uppercase text-[9px] tracking-[0.3em]">Memoria Volátil • Nexosyne 2026</p>
    </div>

    <div class="card-unified p-4 md:p-6 shadow-2xl" :class="fullScreen ? 'p-0 border-none' : ''">
        
        <div x-show="!isLoaded" class="py-16 flex flex-col items-center justify-center">
            <div @click="$refs.fileInput.click()" 
                 class="w-full max-w-md p-10 border-4 border-dashed border-gray-100 rounded-[3rem] cursor-pointer hover:border-theme bg-gray-50/50 text-center">
                <i class="fas fa-file-pdf text-5xl text-gray-300 mb-6"></i>
                <h3 class="text-xl font-black uppercase italic">Seleccionar PDF</h3>
                <p class="text-[9px] font-bold text-gray-400 uppercase">Procesado localmente</p>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak class="flex flex-col w-full">
            <div x-show="!fullScreen" class="flex justify-between items-center mb-4 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                <span class="font-black text-black text-[10px] uppercase italic truncate max-w-[200px]" x-text="shortName"></span>
                <div class="flex gap-2">
                    <button @click="toggleFS()" class="bg-black text-white px-4 py-2 rounded-xl font-black text-[9px] uppercase hover:bg-theme">PANTALLA COMPLETA</button>
                    <button @click="reset()" class="bg-gray-200 text-black px-4 py-2 rounded-xl font-black text-[9px] uppercase">CERRAR</button>
                </div>
            </div>

            <div class="viewer-wrapper" id="pdf-viewer" :class="fullScreen ? 'is-fullscreen' : ''">
                <button x-show="fullScreen" @click="toggleFS()" class="btn-exit-fs" title="Salir de pantalla completa">
                    <i class="fas fa-times"></i>
                </button>
                <div id="pdf-container" class="w-full h-full"></div>
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
        pdfUrl: null,

        handleFile(e) {
            const file = e.target.files[0];
            if (!file || file.type !== 'application/pdf') return;

            if (this.pdfUrl) URL.revokeObjectURL(this.pdfUrl);
            
            this.shortName = file.name;
            this.pdfUrl = URL.createObjectURL(file);
            this.isLoaded = true;

            this.$nextTick(() => {
                // PDFObject incrusta el PDF de forma nativa
                PDFObject.embed(this.pdfUrl, "#pdf-container", {
                    pdfOpenParams: { view: 'FitV', toolbar: 1 }
                });
            });
        },

        toggleFS() {
            this.fullScreen = !this.fullScreen;
            document.body.style.overflow = this.fullScreen ? 'hidden' : 'auto';
            // Re-incrustar al cambiar tamaño para asegurar que se ajuste
            this.$nextTick(() => {
                PDFObject.embed(this.pdfUrl, "#pdf-container");
            });
        },

        reset() {
            if (this.pdfUrl) URL.revokeObjectURL(this.pdfUrl);
            this.isLoaded = false;
            this.fullScreen = false;
            document.body.style.overflow = 'auto';
            document.getElementById('pdf-container').innerHTML = '';
            this.$refs.fileInput.value = '';
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>