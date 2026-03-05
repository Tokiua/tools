<?php
$pageTitle = "Lumina Stream - Professional PDF Reader";
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

    .viewer-container {
        width: 100%;
        height: 70vh;
        background: #111;
        border-radius: 2rem;
        overflow: hidden;
        border: 3px solid #000;
        position: relative;
    }

    iframe { width: 100%; height: 100%; border: none; }

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
    
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tight uppercase italic text-black">
            Lumina <span class="text-theme">Stream</span>
        </h1>
        <h3 class="text-xl md:text-2xl font-black uppercase italic tracking-tighter text-black mb-4">
            Visualizador <span class="text-theme">RAM-Only</span> Multidispositivo
        </h3>
    </div>

    <div class="card-unified p-4 md:p-8 shadow-2xl">
        
        <div x-show="!isLoaded" class="py-12 md:py-20 flex flex-col items-center justify-center text-center">
            <div @click="$refs.fileInput.click()" 
                 class="w-full max-w-lg p-10 border-4 border-dashed border-gray-100 rounded-[3rem] cursor-pointer hover:border-theme transition-all group bg-gray-50/50">
                <div class="w-20 h-20 bg-white shadow-xl rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-cloud-upload-alt text-3xl text-theme"></i>
                </div>
                <h3 class="text-xl font-black uppercase italic">Abrir PDF en RAM</h3>
                <p class="text-gray-400 font-bold text-[10px] uppercase mt-2">Compatible con Android, iOS y PC</p>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak class="flex flex-col h-full w-full">
            <div class="flex flex-wrap justify-between items-center mb-4 gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                <div class="flex items-center gap-3">
                    <span class="font-black text-black text-xs uppercase italic" x-text="shortName"></span>
                </div>
                <button @click="reset()" class="bg-black text-white px-6 py-2 rounded-xl font-black text-[10px] uppercase">
                    <i class="fas fa-sync-alt mr-2"></i> Cambiar archivo
                </button>
            </div>

            <div class="viewer-container">
                <div x-show="processing" class="absolute inset-0 flex items-center justify-center bg-black/90 z-10">
                    <div class="text-center">
                        <i class="fas fa-circle-notch animate-spin text-theme text-4xl mb-4"></i>
                        <p class="text-white font-black uppercase text-xs">Cifrando para vista móvil...</p>
                    </div>
                </div>
                <iframe x-ref="pdfIframe" src=""></iframe>
            </div>
        </div>
    </div>

    <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="info-box-nex">
            <h4 class="font-black uppercase italic text-sm mb-2">¿Por qué es seguro?</h4>
            <p class="text-gray-500 font-bold text-[10px] leading-relaxed uppercase">
                Al usar <span class="text-black">Base64 Data Streams</span>, el documento viaja como texto cifrado directamente al visor sin necesidad de archivos temporales en el servidor ni enlaces Blob inestables.
            </p>
        </div>
        <div class="info-box-nex">
            <h4 class="font-black uppercase italic text-sm mb-2">Modo Móvil Activo</h4>
            <p class="text-gray-500 font-bold text-[10px] leading-relaxed uppercase">
                Si estás en celular, hemos optimizado el motor para que el scroll y el zoom funcionen de forma nativa sin lags.
            </p>
        </div>
    </div>

    <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept=".pdf">
</main>

<script>
function luminaCore() {
    return {
        isLoaded: false,
        processing: false,
        fileName: '',
        shortName: '',
        viewerUrl: '../../assets/pdfjs/web/viewer.html', 

        handleFile(e) {
            const file = e.target.files[0];
            if (file && file.type === 'application/pdf') {
                this.renderPDF(file);
            }
        },

        async renderPDF(file) {
            this.fileName = file.name;
            this.shortName = this.fileName.length > 15 ? this.fileName.substring(0, 12) + '...' : this.fileName;
            this.isLoaded = true;
            this.processing = true;

            const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

            if (isMobile) {
                // SOLUCIÓN PARA MÓVIL: FileReader a Base64
                // Esto evita el error de "Pantalla Negra" por falta de permisos al Blob
                const reader = new FileReader();
                reader.onload = () => {
                    const base64Data = reader.result;
                    const finalUrl = `${this.viewerUrl}?file=${encodeURIComponent(base64Data)}#zoom=page-width`;
                    this.$refs.pdfIframe.src = finalUrl;
                    this.processing = false;
                };
                reader.readAsDataURL(file);
            } else {
                // SOLUCIÓN PARA PC: Blob URL (Más rápido para archivos grandes)
                const blobUrl = URL.createObjectURL(file);
                const finalUrl = `${this.viewerUrl}?file=${encodeURIComponent(blobUrl)}`;
                this.$refs.pdfIframe.src = finalUrl;
                this.processing = false;
            }
        },

        reset() {
            this.isLoaded = false;
            this.processing = false;
            this.$refs.pdfIframe.src = '';
            this.$refs.fileInput.value = '';
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>