<?php
$pageTitle = "Lumina Stream - Professional Editor";
$themeHex = "#ef4444"; 
include '../../partials/Includes/header.php';
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

<style>
    :root { --accent: #ef4444; }

    .text-theme { color: var(--accent); }
    
    .editor-wrapper {
        position: relative; width: 100%; height: 80vh;
        background: #f8f9fa; border-radius: 2.5rem;
        overflow: hidden; display: flex; flex-direction: column;
        border: 2px solid #eee; transition: all 0.3s ease;
    }

    .editor-wrapper.is-fullscreen {
        position: fixed !important; top: 0; left: 0;
        width: 100vw !important; height: 100vh !important;
        z-index: 9999; border-radius: 0; background: #111;
    }

    .pro-toolbar {
        flex-shrink: 0; background: #fff; padding: 15px 25px;
        display: flex; align-items: center; justify-content: space-between;
        border-bottom: 2px solid #f0f0f0; z-index: 20;
    }

    /* VISOR CORREGIDO: Eliminamos flex para permitir scroll izquierdo total */
    #viewer-canvas-container {
        flex-grow: 1; 
        overflow: auto; 
        background: #e5e7eb;
        cursor: grab; 
        display: block; 
        position: relative;
        text-align: center;
        padding: 150px; /* Padding equilibrado para margen de maniobra */
    }
    #viewer-canvas-container:active { cursor: grabbing; }

    /* Hoja PDF: inline-block para respetar el text-align center */
    .pdf-page-wrapper {
        display: inline-block;
        margin-bottom: 40px; 
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        background: white; 
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        vertical-align: top;
        line-height: 0; 
        border: 1px solid #ddd;
    }

    canvas { width: 100% !important; height: auto !important; display: block; }

    .btn-tool {
        background: #f3f4f6; color: #000; border: none;
        padding: 10px 14px; border-radius: 12px; font-weight: 900;
        font-size: 0.7rem; cursor: pointer; text-transform: uppercase;
        transition: all 0.2s; display: flex; align-items: center; gap: 8px;
    }
    .btn-tool:hover { background: #000; color: #fff; transform: translateY(-2px); }
    .btn-tool.active { background: var(--accent); color: #fff; }

    .editor-footer {
        background: #fff; padding: 12px 25px; border-top: 2px solid #f0f0f0;
        display: flex; justify-content: space-between; align-items: center;
        color: #9ca3af; font-size: 10px; font-weight: 800; letter-spacing: 0.1em;
    }
</style>

<main class="max-w-6xl mx-auto px-4 py-12" x-data="luminaViewerPro()">
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-2 tracking-tight uppercase italic text-black">
            Lumina <span class="text-theme">Stream</span>
        </h1>
        <p class="text-gray-500 font-bold uppercase text-[10px] md:text-xs tracking-[0.4em]">
            Advanced PDF Rendering Engine by Nexosyne
        </p>
    </div>

    <div class="editor-wrapper shadow-2xl" :class="fullScreen ? 'is-fullscreen' : ''">
        <div class="pro-toolbar" x-show="isLoaded" x-transition>
            <div class="flex gap-3 items-center">
                <button @click="vRotate()" class="btn-tool"><i class="fas fa-sync-alt"></i></button>
                <div class="h-6 w-[2px] bg-gray-100 mx-1"></div>
                <button @click="vZoomStep(-0.2)" class="btn-tool"><i class="fas fa-minus"></i></button>
                <div class="bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
                    <span class="text-[11px] font-black text-black" x-text="Math.round(currentZoom * 100) + '%'"></span>
                </div>
                <button @click="vZoomStep(0.2)" class="btn-tool"><i class="fas fa-plus"></i></button>
            </div>
            
            <div class="hidden lg:flex items-center gap-3">
                <i class="fas fa-file-pdf text-red-500"></i>
                <span class="text-[10px] text-black font-black uppercase italic tracking-tighter" x-text="fileName"></span>
            </div>

            <div class="flex gap-3">
                <button @click="toggleFS()" class="btn-tool" :class="fullScreen ? 'active' : ''"><i class="fas fa-expand-arrows-alt"></i></button>
                <button @click="reset()" class="btn-tool bg-black text-white hover:bg-red-600"><i class="fas fa-times"></i></button>
            </div>
        </div>

        <div x-show="!isLoaded" @click="$refs.fileInput.click()" 
             class="flex-grow flex flex-col items-center justify-center bg-white cursor-pointer group transition-all">
            <template x-if="!isProcessing">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:text-red-500 transition-all border-2 border-dashed border-gray-200">
                        <i class="fas fa-file-upload text-3xl"></i>
                    </div>
                    <h3 class="text-black font-black text-xl uppercase italic">Cargar Documento</h3>
                    <p class="text-gray-400 text-[10px] font-bold uppercase mt-2 tracking-widest">Arrastra o haz clic para procesar PDF</p>
                </div>
            </template>
            <template x-if="isProcessing">
                <div class="flex flex-col items-center">
                    <i class="fas fa-circle-notch animate-spin text-4xl text-red-500 mb-4"></i>
                    <p class="text-black font-black text-xs uppercase italic tracking-widest">Generando Renders de Alta Fidelidad...</p>
                </div>
            </template>
        </div>

        <div id="viewer-canvas-container" 
             x-show="isLoaded"
             @mousedown="startDragging($event)"
             @mousemove="drag($event)"
             @mouseup="stopDragging()"
             @mouseleave="stopDragging()"
             @touchstart="startDragging($event)"
             @touchmove="drag($event)"
             @touchend="stopDragging()">
        </div>

        <div class="editor-footer" x-show="isLoaded">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                <span>CORE: <span class="text-black italic">NEXOSYNE-V3</span></span>
            </div>
            <div class="flex gap-6">
                <span>PÁGINAS: <span class="text-black" x-text="totalPages"></span></span>
                <span>STATUS: <span class="text-black">VOLATILE MODE</span></span>
            </div>
        </div>
    </div>

    <div class="mt-24 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
            <h2 class="text-3xl font-black uppercase tracking-tighter text-black italic mb-6">Visualización <span class="text-theme">Ultra-Rápida</span></h2>
            <p class="text-gray-500 font-bold text-sm leading-relaxed mb-4">
                Lumina Stream procesa tus documentos PDF directamente en tu navegador. Olvídate de esperar a que los archivos se carguen en un servidor; aquí la privacidad es lo primero.
            </p>
            <div class="flex gap-4">
                <div class="px-4 py-2 bg-gray-100 rounded-lg text-[10px] font-black uppercase">Sin Logs</div>
                <div class="px-4 py-2 bg-gray-100 rounded-lg text-[10px] font-black uppercase">Fidelity 2.0</div>
                <div class="px-4 py-2 bg-gray-100 rounded-lg text-[10px] font-black uppercase">Zero Storage</div>
            </div>
        </div>
        <div class="bg-black p-8 rounded-[2.5rem] shadow-2xl border-b-4 border-red-600">
            <div class="text-red-500 text-3xl mb-4"><i class="fas fa-shield-alt"></i></div>
            <h3 class="text-white font-black uppercase italic text-xl mb-3">Privacidad Garantizada</h3>
            <p class="text-gray-400 text-xs font-bold leading-relaxed">
                Nuestra tecnología de renderizado no guarda ni un solo byte de tus documentos. Al cerrar la pestaña o presionar la "X", el contenido desaparece para siempre de la memoria volátil.
            </p>
        </div>
    </div>

    <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept=".pdf">
</main>

<script>
function luminaViewerPro() {
    return {
        isLoaded: false,
        isProcessing: false,
        fullScreen: false,
        currentZoom: 0.9,
        currentRotation: 0,
        fileName: '',
        totalPages: 0,
        isDragging: false,
        startX: 0, startY: 0,
        scrollLeft: 0, scrollTop: 0,

        async handleFile(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            this.fileName = file.name;
            this.isProcessing = true;
            
            const reader = new FileReader();
            reader.onload = async (f) => {
                try {
                    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
                    const pdfDoc = await pdfjsLib.getDocument(new Uint8Array(f.target.result)).promise;
                    this.totalPages = pdfDoc.numPages;
                    
                    const container = document.getElementById('viewer-canvas-container');
                    container.innerHTML = '';

                    for (let i = 1; i <= pdfDoc.numPages; i++) {
                        const page = await pdfDoc.getPage(i);
                        const viewport = page.getViewport({ scale: 2.5 }); 
                        const wrapper = document.createElement('div');
                        wrapper.className = 'pdf-page-wrapper';
                        
                        const canvas = document.createElement('canvas');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;

                        wrapper.appendChild(canvas);
                        container.appendChild(wrapper);
                    }
                    this.isLoaded = true;
                    this.isProcessing = false;
                    
                    this.$nextTick(() => {
                        this.applyZoom();
                        this.centerScroll();
                    });
                } catch (err) {
                    alert("Nexosyne Core: Error al procesar PDF");
                    this.reset();
                }
            };
            reader.readAsArrayBuffer(file);
        },

        centerScroll() {
            const container = document.getElementById('viewer-canvas-container');
            // Centrado inteligente basado en el ancho real del contenido
            const scrollX = (container.scrollWidth - container.clientWidth) / 2;
            container.scrollLeft = scrollX;
            container.scrollTop = 100; // Un pequeño margen superior
        },

        vZoomStep(val) {
            this.currentZoom = Math.min(Math.max(this.currentZoom + val, 0.1), 4.0);
            this.applyZoom();
        },

        applyZoom() {
            const wrappers = document.querySelectorAll('.pdf-page-wrapper');
            const baseWidth = 800; 
            const newWidth = baseWidth * this.currentZoom;
            
            wrappers.forEach(w => {
                w.style.width = newWidth + 'px';
            });
        },

        vRotate() {
            this.currentRotation = (this.currentRotation + 90) % 360;
            const wrappers = document.querySelectorAll('.pdf-page-wrapper');
            wrappers.forEach(w => {
                w.style.transform = `rotate(${this.currentRotation}deg)`;
            });
        },

        startDragging(e) {
            if (e.target.closest('.pro-toolbar') || e.target.tagName === 'BUTTON') return;
            this.isDragging = true;
            const container = document.getElementById('viewer-canvas-container');
            const x = (e.pageX || e.touches?.[0].pageX) - container.offsetLeft;
            const y = (e.pageY || e.touches?.[0].pageY) - container.offsetTop;
            
            this.startX = x;
            this.startY = y;
            this.scrollLeft = container.scrollLeft;
            this.scrollTop = container.scrollTop;
        },

        drag(e) {
            if (!this.isDragging) return;
            e.preventDefault();
            const container = document.getElementById('viewer-canvas-container');
            const x = (e.pageX || e.touches?.[0].pageX) - container.offsetLeft;
            const y = (e.pageY || e.touches?.[0].pageY) - container.offsetTop;

            const walkX = (x - this.startX);
            const walkY = (y - this.startY);
            
            container.scrollLeft = this.scrollLeft - walkX;
            container.scrollTop = this.scrollTop - walkY;
        },

        stopDragging() { this.isDragging = false; },

        toggleFS() {
            this.fullScreen = !this.fullScreen;
            setTimeout(() => {
                this.applyZoom();
                this.centerScroll();
            }, 200);
        },

        reset() {
            this.isLoaded = false;
            this.isProcessing = false;
            this.fullScreen = false;
            this.fileName = '';
            this.currentZoom = 0.9;
            this.currentRotation = 0;
            if (this.$refs.fileInput) this.$refs.fileInput.value = ''; 
            const container = document.getElementById('viewer-canvas-container');
            if (container) container.innerHTML = '';
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>