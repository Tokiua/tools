<?php
$pageTitle = "Lumina Stream - Professional Editor";
$themeHex = "#ef4444"; 
include '../../partials/Includes/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

<style>
    .editor-wrapper {
        position: relative; width: 100%; height: 75vh;
        background: #111; border-radius: 2.5rem;
        overflow: hidden; border: 3px solid #000;
        display: flex; flex-direction: column;
    }

    .editor-wrapper.is-fullscreen {
        position: fixed !important; top: 0; left: 0;
        width: 100vw !important; height: 100vh !important;
        z-index: 9999; border-radius: 0; border: none;
    }

    .pro-toolbar {
        width: 100%; background: #000; padding: 10px 20px;
        display: flex; align-items: center; justify-content: space-between;
        z-index: 10002; border-bottom: 1px solid #222; flex-wrap: wrap; gap: 10px;
    }

    .tool-section { display: flex; align-items: center; gap: 8px; }

    /* Estilo para los controles de Zoom */
    .zoom-controls {
        display: flex; align-items: center; background: #222;
        border-radius: 10px; padding: 2px; border: 1px solid #333;
    }

    .zoom-input {
        background: transparent; color: white; border: none;
        width: 50px; text-align: center; font-size: 0.8rem;
        font-weight: bold; outline: none; padding: 5px 0;
    }

    .zoom-select {
        background: #333; color: white; border: none;
        border-radius: 6px; font-size: 0.7rem; font-weight: bold;
        padding: 4px 8px; outline: none; cursor: pointer;
    }

    .btn-tool {
        background: #222; color: white; border: none;
        padding: 8px 12px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: 0.3s; font-size: 0.75rem;
        font-weight: bold; text-transform: uppercase;
    }
    .btn-tool:hover { background: #333; color: #ef4444; }
    .btn-tool.accent { background: #ef4444; }

    #viewer-canvas-container {
        flex-grow: 1; overflow-y: auto; padding: 30px;
        display: flex; flex-direction: column; align-items: center;
        background: #1a1a1a; scroll-behavior: smooth;
    }

    .pdf-page-img {
        /* Eliminamos max-width fijo para que el zoom mande */
        margin-bottom: 30px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.6);
        border-radius: 4px; transition: width 0.2s ease-out, transform 0.3s;
        transform-origin: center center;
    }

    .footer-instructions {
        margin-top: 2rem; padding: 2rem; background: #fff;
        border-radius: 2rem; border: 1px solid #eee;
    }
</style>

<main class="max-w-6xl mx-auto px-4 py-10" x-data="luminaViewerPro()">
    <div class="text-center mb-8" x-show="!fullScreen">
        <h1 class="text-5xl font-black uppercase italic tracking-tighter">Lumina <span class="text-theme">Pro</span></h1>
        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest">Nexosyne Document Architecture 2026</p>
    </div>

    <div class="editor-wrapper shadow-2xl" :class="fullScreen ? 'is-fullscreen' : ''">
        <div class="pro-toolbar" x-show="isLoaded">
            <div class="tool-section">
                <button @click="vRotate()" class="btn-tool"><i class="fas fa-sync-alt mr-2"></i> Rotar</button>
                
                <div class="zoom-controls">
                    <button @click="vZoomStep(-0.1)" class="btn-tool" style="background:transparent"><i class="fas fa-minus"></i></button>
                    
                    <div class="flex items-center px-2 border-l border-r border-[#333]">
                        <input type="text" 
                               class="zoom-input" 
                               :value="Math.round(currentZoom * 100) + '%'"
                               @keydown.enter="manualZoom($event.target.value)"
                               @blur="manualZoom($event.target.value)">
                    </div>

                    <button @click="vZoomStep(0.1)" class="btn-tool" style="background:transparent"><i class="fas fa-plus"></i></button>
                    
                    <select class="zoom-select ml-2" @change="manualZoom($event.target.value)">
                        <option value="">Ajustar</option>
                        <option value="50%">50%</option>
                        <option value="75%">75%</option>
                        <option value="100%">100%</option>
                        <option value="125%">125%</option>
                        <option value="150%">150%</option>
                        <option value="200%">200%</option>
                        <option value="300%">300%</option>
                    </select>
                </div>
            </div>

            <div class="tool-section">
                <button @click="toggleFS()" class="btn-tool" :class="fullScreen ? 'accent' : ''">
                    <i class="fas" :class="fullScreen ? 'fa-compress mr-2' : 'fa-expand mr-2'"></i>
                    <span x-text="fullScreen ? 'Salir' : 'Pantalla Completa'"></span>
                </button>
                <button @click="reset()" class="btn-tool" x-show="!fullScreen">Cerrar</button>
            </div>
        </div>

        <div x-show="!isLoaded" @click="$refs.fileInput.click()" class="flex-grow flex flex-col items-center justify-center cursor-pointer hover:bg-[#1f1f1f] transition-all bg-[#151515]">
            <div class="w-20 h-20 bg-black rounded-full flex items-center justify-center mb-6 border border-gray-800">
                <i class="fas fa-file-pdf text-3xl text-theme"></i>
            </div>
            <h3 class="text-white font-black uppercase italic tracking-tighter text-center px-4">Arrastra o selecciona un documento</h3>
        </div>

        <div id="viewer-canvas-container" x-show="isLoaded"></div>
    </div>

    <div class="footer-instructions shadow-sm" x-show="!fullScreen">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="flex flex-col">
                <span class="text-theme font-black mb-2 text-lg">PRECISION ZOOM</span>
                <p class="text-[10px] font-bold text-gray-400 uppercase leading-relaxed">
                    Escribe el porcentaje manual y presiona Enter o usa el selector predefinido para ajustes de lectura rápida.
                </p>
            </div>
            <div class="flex flex-col">
                <span class="text-black font-black mb-2 text-lg">PERSISTENT UI</span>
                <p class="text-[10px] font-bold text-gray-400 uppercase leading-relaxed">
                    La barra de herramientas se mantiene anclada en la parte superior, incluso en modo pantalla completa, facilitando el control total.
                </p>
            </div>
            <div class="flex flex-col">
                <span class="text-black font-black mb-2 text-lg">MEMORY PURGE</span>
                <p class="text-[10px] font-bold text-gray-400 uppercase leading-relaxed">
                    Siguiendo los protocolos de Nexosyne, ningún dato se guarda en el servidor. Todo el procesamiento ocurre en tiempo real.
                </p>
            </div>
        </div>
    </div>

    <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept=".pdf">
</main>

<script>
function luminaViewerPro() {
    return {
        isLoaded: false,
        fullScreen: false,
        viewer: null,
        currentRotation: 0,
        currentZoom: 1.0, // 100%

        async handleFile(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = async (f) => {
                const data = new Uint8Array(f.target.result);
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
                
                const pdfDoc = await pdfjsLib.getDocument(data).promise;
                const container = document.getElementById('viewer-canvas-container');
                container.innerHTML = '';

                for (let i = 1; i <= pdfDoc.numPages; i++) {
                    const page = await pdfDoc.getPage(i);
                    const viewport = page.getViewport({ scale: 2.0 }); // Alta definición
                    const canvas = document.createElement('canvas');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
                    
                    const img = document.createElement('img');
                    img.src = canvas.toDataURL();
                    img.className = 'pdf-page-img';
                    img.style.width = '90%'; // Ancho inicial
                    container.appendChild(img);
                }

                this.isLoaded = true;
                this.initViewer();
            };
            reader.readAsArrayBuffer(file);
        },

        initViewer() {
            this.$nextTick(() => {
                const container = document.getElementById('viewer-canvas-container');
                if (this.viewer) this.viewer.destroy();

                this.viewer = new Viewer(container, {
                    button: true,
                    navbar: true,
                    title: false,
                    toolbar: true,
                    zoomable: true,
                    rotatable: true,
                    transition: false
                });
            });
        },

        vRotate() {
            this.currentRotation += 90;
            const images = document.querySelectorAll('.pdf-page-img');
            images.forEach(img => {
                img.style.transform = `rotate(${this.currentRotation}deg)`;
            });
        },

        vZoomStep(val) {
            this.currentZoom = Math.min(Math.max(this.currentZoom + val, 0.2), 4.0);
            this.applyZoom();
        },

        manualZoom(val) {
            if (!val) return;
            // Limpiar el valor (quitar % si lo tiene)
            let numeric = parseFloat(val.replace('%', ''));
            if (!isNaN(numeric)) {
                this.currentZoom = numeric / 100;
                this.applyZoom();
            }
        },

        applyZoom() {
            const images = document.querySelectorAll('.pdf-page-img');
            images.forEach(img => {
                // El zoom profesional escala el ancho relativo al contenedor
                img.style.width = (this.currentZoom * 90) + '%';
            });
            // Sincronizar con Viewer.js si está abierto el modal
            if (this.viewer) {
                this.viewer.zoomTo(this.currentZoom);
            }
        },

        toggleFS() {
            this.fullScreen = !this.fullScreen;
            document.body.style.overflow = this.fullScreen ? 'hidden' : 'auto';
        },

        reset() {
            this.isLoaded = false;
            this.fullScreen = false;
            this.currentRotation = 0;
            this.currentZoom = 1.0;
            if (this.viewer) this.viewer.destroy();
            document.getElementById('viewer-canvas-container').innerHTML = '';
            this.$refs.fileInput.value = '';
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>