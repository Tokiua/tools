<?php
$pageTitle = "Lumina Stream - Professional PDF Reader";
$themeHex = "#ef4444"; 
include '../../partials/Includes/header.php';
?>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    :root { --theme: #ef4444; }
    
    /* Estilo Unificado Nexosyne */
    .card-unified {
        background: #ffffff;
        border-radius: 2.5rem;
        border: 2px solid #f3f4f6;
        transition: all 0.3s ease;
    }

    .viewer-container {
        width: 100%;
        height: 75vh;
        background: #111;
        border-radius: 2rem;
        overflow: hidden;
        border: 3px solid #000;
        position: relative;
        /* Fix para scroll en iOS */
        -webkit-overflow-scrolling: touch;
    }

    .fullscreen-mode {
        position: fixed !important;
        top: 0; left: 0;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 9999;
        background: #000;
        border-radius: 0 !important;
    }

    .btn-exit {
        position: absolute;
        top: 20px;
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
        border: 2px solid #000;
        box-shadow: 0 4px 15px rgba(0,0,0,0.4);
    }

    iframe { width: 100%; height: 100%; border: none; display: block; }

    /* Estilo de Cajas de Info (Fondo Blanco, Borde Negro) */
    .info-box-nex {
        background: #fff;
        border: 3px solid #000;
        border-radius: 1.5rem;
        padding: 1.5rem;
        box-shadow: 8px 8px 0px #000;
    }

    [x-cloak] { display: none !important; }
</style>

<main class="max-w-5xl mx-auto px-6 py-12 md:py-16" x-data="luminaCore()">
    
    <div class="text-center mb-10" x-show="!isFullScreen">
        <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tight uppercase italic text-black">
            Lumina <span class="text-theme">Stream</span>
        </h1>
        <h3 class="text-xl md:text-2xl font-black uppercase italic tracking-tighter text-black mb-4">
            Lectura Profesional <span class="text-theme">Sin Huella</span> de Servidor
        </h3>
        <p class="text-gray-500 font-bold uppercase text-[10px] md:text-xs tracking-[0.3em]">
            Tecnología de Memoria Volátil • Nexosyne 2026
        </p>
    </div>

    <div class="card-unified p-4 md:p-8 shadow-2xl" :class="isFullScreen ? 'fullscreen-mode' : ''">
        
        <button x-show="isFullScreen" @click="isFullScreen = false" class="btn-exit" x-cloak>
            <i class="fas fa-times"></i>
        </button>

        <div x-show="!isLoaded" class="py-12 md:py-20 flex flex-col items-center justify-center text-center">
            <div @click="$refs.fileInput.click()" 
                 class="w-full max-w-lg p-10 border-4 border-dashed border-gray-100 rounded-[3rem] cursor-pointer hover:border-theme transition-all group bg-gray-50/50">
                <div class="w-20 h-20 bg-white shadow-xl rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-file-pdf text-3xl text-theme"></i>
                </div>
                <h3 class="text-xl font-black uppercase italic italic mb-2">Seleccionar Documento</h3>
                <p class="text-gray-400 font-bold text-[10px] uppercase tracking-widest">El archivo se procesará solo en tu RAM</p>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak class="flex flex-col h-full w-full">
            <div x-show="!isFullScreen" class="flex flex-wrap justify-between items-center mb-4 gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-theme text-white rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fas fa-file-pdf text-xs"></i>
                    </div>
                    <span class="font-black text-black text-xs uppercase italic tracking-tighter" x-text="shortName"></span>
                </div>
                
                <div class="flex items-center gap-2">
                    <button @click="isFullScreen = true" class="bg-black text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-theme transition-all">
                        <i class="fas fa-expand mr-2"></i> Pantalla Completa
                    </button>
                    <button @click="reset()" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="viewer-container">
                <iframe x-ref="pdfIframe" src="" allow="fullscreen"></iframe>
            </div>
        </div>
    </div>

    <div class="mt-20 space-y-12" x-show="!isFullScreen">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="info-box-nex">
                <h4 class="font-black uppercase italic text-sm mb-3">Seguridad Volátil</h4>
                <p class="text-gray-600 font-bold text-[11px] leading-relaxed uppercase">
                    A diferencia de otros lectores, <span class="text-theme">Nexosyne no almacena</span> tus documentos. El sistema usa un túnel de memoria RAM para mostrar el PDF.
                </p>
            </div>
            <div class="info-box-nex">
                <h4 class="font-black uppercase italic text-sm mb-3">Instrucciones</h4>
                <p class="text-gray-600 font-bold text-[11px] leading-relaxed uppercase">
                    Si el visor aparece en negro en tu celular, intenta <span class="text-black">recargar la página</span> o usar el botón de Pantalla Completa.
                </p>
            </div>
        </div>

        <div class="card-unified p-8 bg-black text-white border-none shadow-2xl">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="text-5xl text-theme"><i class="fas fa-microchip"></i></div>
                <div>
                    <h2 class="text-2xl font-black uppercase italic tracking-tighter mb-2 text-theme">Arquitectura Bridge Core</h2>
                    <p class="text-gray-400 font-bold text-[10px] uppercase tracking-widest leading-relaxed">
                        Nuestra infraestructura crea una URL temporal (Blob) que solo existe mientras la pestaña está abierta. Al cerrar, los datos desaparecen por completo.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept=".pdf">
</main>

<script>
function luminaCore() {
    return {
        isLoaded: false,
        isFullScreen: false,
        fileName: '',
        shortName: '',
        currentBlobUrl: null,
        // Ruta absoluta desde la raíz para evitar errores en Hostinger
        viewerUrl: '../../assets/pdfjs/web/viewer.html', 

        handleFile(e) {
            const file = e.target.files[0];
            if (file && file.type === 'application/pdf') {
                this.renderPDF(file);
            } else {
                alert('Formato no válido. Usa PDF.');
            }
        },

        renderPDF(file) {
            // Limpiar memoria previa
            if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
            
            this.fileName = file.name;
            // Truncado del nombre para celular (Primeros 15 caracteres)
            this.shortName = this.fileName.length > 18 
                ? this.fileName.substring(0, 15) + '...' 
                : this.fileName;

            this.isLoaded = true;
            this.currentBlobUrl = URL.createObjectURL(file);
            
            this.$nextTick(() => {
                const iframe = this.$refs.pdfIframe;
                // Ajuste específico para navegadores móviles nativos
                const mobileParams = '#view=FitH&pagemode=none&zoom=page-width';
                const finalUrl = `${this.viewerUrl}?file=${encodeURIComponent(this.currentBlobUrl)}${mobileParams}`;
                
                iframe.src = finalUrl;
            });
        },

        reset() {
            if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
            this.isLoaded = false;
            this.isFullScreen = false;
            this.fileName = '';
            this.shortName = '';
            this.$refs.pdfIframe.src = '';
            this.$refs.fileInput.value = '';
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>