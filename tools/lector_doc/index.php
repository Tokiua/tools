<?php
$pageTitle = "Lumina Stream - Professional PDF Reader";
$themeHex = "#ef4444"; 
include '../../partials/Includes/header.php';
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
    
    #lumina-viewport {
        background-color: #525659 !important; /* Color oscuro tipo visor profesional */
        width: 100%;
        height: 80vh;
        border: 2px solid #000;
        border-radius: 2.5rem;
        position: relative;
        overflow-y: auto; /* Permitir scroll en móviles */
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    #pdf-render-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px 10px;
    }

    canvas {
        max-width: 100%;
        height: auto !important;
        margin-bottom: 20px;
        box-shadow: 0 0 20px rgba(0,0,0,0.4);
        border-radius: 4px;
    }

    .btn-lumina {
        background-color: #000; color: #fff; border: 2px solid #ef4444;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-lumina:hover { background-color: #ef4444; transform: translateY(-2px); }

    /* Animación de escaneo */
    .scan-line {
        width: 100%; height: 4px; background: #ef4444; position: absolute;
        top: 0; left: 0; box-shadow: 0 0 15px #ef4444; animation: scan 2s linear infinite;
        z-index: 60;
    }
    @keyframes scan { 0% { top: 0%; } 100% { top: 100%; } }
</style>

<main class="max-w-5xl mx-auto px-6 py-12 md:py-16" x-data="luminaReader()">
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tight uppercase italic text-black">
            Lumina <span class="text-theme">Stream</span>
        </h1>
        <h3 class="text-xl md:text-2xl font-black uppercase italic tracking-tighter text-black mb-4">
            Visualizador <span class="text-theme">Multiplataforma</span> HD
        </h3>
        <p class="text-gray-500 font-bold uppercase text-[10px] md:text-xs tracking-[0.3em]">
            Lectura Segura • Renderizado en Cliente (RAM)
        </p>
    </div>

    <div class="card-unified p-6 md:p-10 relative shadow-2xl">
        
        <div x-show="!isLoaded" class="space-y-8">
            <div @click="$refs.fileInput.click()" 
                 class="w-full border-4 border-dashed border-gray-100 rounded-[3rem] py-24 flex flex-col items-center justify-center cursor-pointer hover:border-theme hover:bg-red-50/30 transition-all group">
                <div class="w-20 h-20 bg-gray-50 text-gray-200 rounded-3xl flex items-center justify-center mb-6 group-hover:bg-theme group-hover:text-white group-hover:rotate-12 transition-all shadow-sm">
                    <i class="fas fa-file-pdf text-4xl"></i>
                </div>
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Cargar Archivo PDF</h3>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak x-transition class="space-y-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-900 p-5 rounded-[2rem] shadow-xl">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-theme text-white rounded-2xl flex items-center justify-center shadow-lg -rotate-3">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-theme uppercase tracking-widest">Modo Adaptativo</p>
                        <p class="font-extrabold text-white text-sm truncate max-w-[200px]" x-text="fileName"></p>
                    </div>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button @click="confirmChange" class="w-full md:w-auto btn-lumina px-6 py-4 rounded-xl font-black text-[10px] uppercase tracking-widest">
                        <i class="fas fa-sync-alt mr-2"></i> Cambiar Documento
                    </button>
                </div>
            </div>

            <div id="lumina-viewport">
                <div x-show="loading" class="absolute inset-0 bg-white flex flex-col items-center justify-center z-50">
                    <div class="scan-line"></div>
                    <div class="w-14 h-14 border-4 border-theme border-t-transparent rounded-full animate-spin mb-4"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-black">Procesando páginas...</p>
                </div>

                <div id="pdf-render-container"></div>
            </div>
        </div>
    </div>

    <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept=".pdf">
</main>

<script>
// Configuración de PDF.js
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

function luminaReader() {
    return {
        isLoaded: false, 
        loading: false, 
        fileName: '', 
        
        async handleFile(e) {
            const file = e.target.files[0];
            if (!file || file.type !== 'application/pdf') return;

            this.loading = true;
            this.isLoaded = true;
            this.fileName = file.name;

            const reader = new FileReader();
            reader.onload = async (event) => {
                const typedarray = new Uint8Array(event.target.result);
                
                try {
                    const pdf = await pdfjsLib.getDocument(typedarray).promise;
                    const container = document.getElementById('pdf-render-container');
                    container.innerHTML = ''; // Limpiar previo

                    // Renderizar todas las páginas
                    for (let n = 1; n <= pdf.numPages; n++) {
                        const page = await pdf.getPage(n);
                        const viewport = page.getViewport({ scale: 1.5 });
                        
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        await page.render({ canvasContext: context, viewport: viewport }).promise;
                        container.appendChild(canvas);
                    }
                } catch (err) {
                    alert('Error renderizando el PDF.');
                    this.isLoaded = false;
                } finally {
                    this.loading = false;
                }
            };
            reader.readAsArrayBuffer(file);
        },

        confirmChange() {
            if (confirm("¿Cambiar documento?")) {
                this.isLoaded = false;
                this.$refs.fileInput.click();
            }
        }
    }
}
</script>