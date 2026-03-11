<?php
$pageTitle = "Lumina Stream - PDF Master";
$themeHex = "#ef4444"; // Tu rojo Nexosyne
include '../../partials/Includes/header.php';
?>

<style>
    /* Estilos específicos para el visor que no están en el header global */
    .viewer-wrapper {
        position: relative; width: 100%; height: 75vh;
        background: #1a1a1a; border-radius: 2rem;
        overflow: hidden; border: 3px solid #000;
        transition: all 0.3s ease;
    }
    .viewer-wrapper.is-fullscreen {
        position: fixed !important; top: 0; left: 0;
        width: 100vw !important; height: 100vh !important;
        z-index: 9999; border-radius: 0; border: none;
    }
    iframe { width: 100%; height: 100%; border: none; display: block; }
    .btn-exit-fs {
        position: absolute; top: 25px; right: 25px;
        z-index: 10000; background: #ef4444;
        color: white; width: 45px; height: 45px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        border: 3px solid #000; box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        cursor: pointer;
    }
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
            <div @click="$refs.fileInput.click()" 
                 class="w-full max-w-md p-10 border-4 border-dashed border-gray-100 rounded-[3rem] cursor-pointer hover:border-theme bg-gray-50/50 transition-all group text-center">
                <i class="fas fa-file-pdf text-5xl text-gray-300 group-hover:text-theme mb-6 transition-colors"></i>
                <h3 class="text-xl font-black uppercase italic">Abrir Documento</h3>
                <p class="text-gray-400 font-bold text-[10px] uppercase italic">Procesamiento Local Seguro</p>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak class="flex flex-col w-full">
            <div x-show="!fullScreen" class="flex justify-between items-center mb-4 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                <span class="font-black text-black text-[10px] uppercase italic truncate max-w-[200px]" x-text="shortName"></span>
                <div class="flex gap-2">
                    <button @click="toggleFS()" class="bg-black text-white px-4 py-2 rounded-xl font-black text-[9px] uppercase hover:bg-theme transition-colors">PANTALLA COMPLETA</button>
                    <button @click="reset()" class="bg-gray-200 text-black px-4 py-2 rounded-xl font-black text-[9px] uppercase hover:bg-red-500 hover:text-white transition-colors">CERRAR</button>
                </div>
            </div>

            <div class="viewer-wrapper" :class="fullScreen ? 'is-fullscreen' : ''">
                <button x-show="fullScreen" @click="toggleFS()" class="btn-exit-fs">
                    <i class="fas fa-times"></i>
                </button>
                <iframe x-ref="pdfIframe" src=""></iframe>
            </div>
        </div>
    </div>

    <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept=".pdf">

    <div class="mt-24 space-y-20" x-show="!fullScreen">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center bg-gray-50/50 p-8 md:p-12 rounded-[2.5rem] border-2 border-dashed border-gray-200">
            <div>
                <div class="text-5xl mb-6 text-theme"><i class="fas fa-eye-slash"></i></div>
                <h2 class="text-3xl font-black uppercase tracking-tighter italic mb-4">Privacidad <span class="text-theme">Absoluta</span></h2>
                <p class="text-gray-500 font-bold text-sm leading-relaxed uppercase">
                    Lumina Stream no sube tus archivos al servidor. El PDF se carga directamente en el visor de tu navegador usando tecnología de memoria volátil.
                </p>
            </div>
            <div class="space-y-4">
                <div class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm">
                    <i class="fas fa-check-circle text-theme"></i>
                    <span class="font-black text-[10px] uppercase tracking-widest">Sin registros en base de datos</span>
                </div>
                <div class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm">
                    <i class="fas fa-check-circle text-theme"></i>
                    <span class="font-black text-[10px] uppercase tracking-widest">Carga instantánea HD</span>
                </div>
                <div class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm">
                    <i class="fas fa-check-circle text-theme"></i>
                    <span class="font-black text-[10px] uppercase tracking-widest">Compatible con archivos pesados</span>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function luminaCore() {
    return {
        isLoaded: false,
        fullScreen: false,
        shortName: '',
        currentBlobUrl: null,

        getViewerPath() {
            const host = window.location.hostname;
            const path = window.location.pathname;
            // Detección automática de ruta para XAMPP y Hostinger
            if (host === 'localhost' || path.includes('/herramienta/')) {
                return '/herramienta/assets/pdfjs/web/viewer.html';
            }
            return '/assets/pdfjs/web/viewer.html';
        },

        handleFile(e) {
            const file = e.target.files[0];
            if (!file || file.type !== 'application/pdf') return;

            // Liberar memoria anterior si existe
            if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
            
            this.shortName = file.name;
            this.currentBlobUrl = URL.createObjectURL(file);
            this.isLoaded = true;

            this.$nextTick(() => {
                const path = this.getViewerPath();
                // Forzamos la carga del iframe con el blob del PDF local
                this.$refs.pdfIframe.src = `${path}?file=${encodeURIComponent(this.currentBlobUrl)}#view=FitH`;
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