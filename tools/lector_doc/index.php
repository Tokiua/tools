<?php
$pageTitle = "Lumina Stream - Professional PDF Reader";
$themeHex = "#ef4444"; 
include '../../partials/Includes/header.php';
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<style>
    /* Fix para que el menú siempre esté por encima del visor */
    nav { z-index: 100 !important; }
    
    #lumina-app-container {
        position: relative;
        z-index: 10; /* Menor que el nav */
    }

    #lumina-viewport {
        background-color: #333;
        width: 100%;
        height: 75vh;
        border: 2px solid #000;
        border-radius: 2rem;
        position: relative;
        overflow-y: auto;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        -webkit-overflow-scrolling: touch;
    }

    /* Estilo para Pantalla Completa Nativa */
    #lumina-viewport:fullscreen {
        width: 100vw !important;
        height: 100vh !important;
        border-radius: 0;
        padding: 0;
        background-color: #1a1a1a;
    }

    #pdf-render-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px 10px;
        gap: 15px;
    }

    canvas {
        max-width: 100%;
        height: auto !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.5);
        border-radius: 4px;
        background: white;
    }

    .btn-lumina {
        background-color: #000; color: #fff; border: 2px solid #ef4444;
        transition: all 0.3s ease;
    }
    .btn-lumina:hover { background-color: #ef4444; transform: translateY(-2px); }

    .scan-line {
        width: 100%; height: 4px; background: #ef4444; position: absolute;
        top: 0; left: 0; box-shadow: 0 0 15px #ef4444; animation: scan 2s linear infinite;
        z-index: 60;
    }
    @keyframes scan { 0% { top: 0%; } 100% { top: 100%; } }

    /* Botón flotante para salir de pantalla completa en móviles */
    .exit-fullscreen-btn {
        position: fixed; top: 20px; right: 20px; z-index: 9999;
        background: #ef4444; color: white; border-radius: 50%;
        width: 45px; height: 45px; display: none; align-items: center; justify-content: center;
    }
    #lumina-viewport:fullscreen .exit-fullscreen-btn { display: flex; }
</style>

<main id="lumina-app-container" class="max-w-5xl mx-auto px-6 py-12 md:py-16" x-data="luminaReader()">
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tight uppercase italic text-black">
            Lumina <span class="text-theme">Stream</span>
        </h1>
        <h3 class="text-xl md:text-2xl font-black uppercase italic tracking-tighter text-black mb-4">
            Visualizador <span class="text-theme">HD Universal</span>
        </h3>
        <p class="text-gray-500 font-bold uppercase text-[10px] md:text-xs tracking-[0.3em]">
            Lectura Segura • Procesamiento Volátil
        </p>
    </div>

    <div class="card-unified p-4 md:p-10 relative shadow-2xl">
        
        <div x-show="!isLoaded" class="space-y-8">
            <div @click="$refs.fileInput.click()" 
                 class="w-full border-4 border-dashed border-gray-100 rounded-[3rem] py-24 flex flex-col items-center justify-center cursor-pointer hover:border-theme hover:bg-red-50/30 transition-all group">
                <div class="w-20 h-20 bg-gray-50 text-gray-200 rounded-3xl flex items-center justify-center mb-6 group-hover:bg-theme group-hover:text-white transition-all shadow-sm">
                    <i class="fas fa-file-pdf text-4xl"></i>
                </div>
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Seleccionar PDF para lectura</h3>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak x-transition class="space-y-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-900 p-4 rounded-[2rem] shadow-xl">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-theme text-white rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="max-w-[150px] md:max-w-[300px]">
                        <p class="text-[8px] font-black text-theme uppercase tracking-widest">Documento en RAM</p>
                        <p class="font-bold text-white text-xs truncate" x-text="fileName"></p>
                    </div>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button @click="goFullscreen()" class="flex-1 bg-white/10 text-white px-4 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-white/20 transition">
                        <i class="fas fa-expand mr-1"></i> Pantalla Completa
                    </button>
                    <button @click="confirmChange" class="flex-1 btn-lumina px-4 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>

            <div id="lumina-viewport">
                <button @click="exitFullscreen()" class="exit-fullscreen-btn">
                    <i class="fas fa-times"></i>
                </button>

                <div x-show="loading" class="absolute inset-0 bg-white flex flex-col items-center justify-center z-50">
                    <div class="scan-line"></div>
                    <div class="w-12 h-12 border-4 border-theme border-t-transparent rounded-full animate-spin mb-4"></div>
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-black">Inyectando datos...</p>
                </div>

                <div id="pdf-render-container"></div>
            </div>
        </div>
    </div>

    <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-black p-8 rounded-[2.5rem] text-white border-2 border-theme/20 shadow-xl">
            <i class="fas fa-bolt text-theme text-3xl mb-4"></i>
            <h4 class="font-black uppercase text-xs tracking-widest mb-2">Motor Volátil</h4>
            <p class="text-gray-400 text-[10px] font-bold leading-relaxed uppercase">El archivo nunca toca el disco duro del servidor. Vive y muere en tu memoria RAM.</p>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border-2 border-gray-100">
            <i class="fas fa-shield-virus text-theme text-3xl mb-4"></i>
            <h4 class="font-black uppercase text-xs tracking-widest mb-2">Aislamiento</h4>
            <p class="text-gray-400 text-[10px] font-bold leading-relaxed uppercase">Renderizado independiente de plugins de terceros o lectores externos.</p>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border-2 border-gray-100">
            <i class="fas fa-mobile-screen text-theme text-3xl mb-4"></i>
            <h4 class="font-black uppercase text-xs tracking-widest mb-2">Universal</h4>
            <p class="text-gray-400 text-[10px] font-bold leading-relaxed uppercase">Optimizado para iPhone, Android y Desktop con gestos táctiles nativos.</p>
        </div>
    </div>

    <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept=".pdf">
</main>

<script>
// Configuración crítica de PDF.js
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
                    container.innerHTML = ''; 

                    // Renderizado HD optimizado
                    for (let n = 1; n <= pdf.numPages; n++) {
                        const page = await pdf.getPage(n);
                        // Ajuste de escala para claridad en móviles
                        const viewport = page.getViewport({ scale: window.innerWidth < 768 ? 1.2 : 1.8 });
                        
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };

                        await page.render(renderContext).promise;
                        container.appendChild(canvas);
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error en el núcleo de renderizado.');
                    this.isLoaded = false;
                } finally {
                    setTimeout(() => { this.loading = false; }, 800);
                }
            };
            reader.readAsArrayBuffer(file);
        },

        goFullscreen() {
            const elem = document.getElementById('lumina-viewport');
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) { /* Safari */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { /* IE11 */
                elem.msRequestFullscreen();
            }
        },

        exitFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        },

        confirmChange() {
            if (confirm("¿Purgar memoria y cargar otro documento?")) {
                this.isLoaded = false;
                this.fileName = '';
                document.getElementById('pdf-render-container').innerHTML = '';
                this.$refs.fileInput.click();
            }
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>