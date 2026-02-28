<?php
$pageTitle = "Nexosyne Alpha Studio";
$themeHex = "#3d2b1f"; // Café Profundo de tu marca
include '../../partials/Includes/header.php';
?>

<style>
    :root { --cafe-p: #3d2b1f; --accent: #d4a373; --bg-studio: #f8f8f8; }
    [x-cloak] { display: none !important; }
    body { background-color: var(--bg-studio); }

    /* Animaciones */
    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .animate-success { animation: slideUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }

    /* Estilo de Tarjetas Técnicas */
    .tech-step {
        background: #fff;
        border: 4px solid #000;
        border-radius: 2.5rem;
        padding: 40px;
        position: relative;
        transition: all 0.3s ease;
    }
    .tech-step:hover { box-shadow: 15px 15px 0px rgba(0,0,0,0.05); transform: translateY(-5px); }
    
    .badge-number {
        position: absolute;
        top: -20px;
        left: 40px;
        background: #000;
        color: var(--accent);
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 18px;
        border: 4px solid #fff;
    }

    .btn-studio {
        background: #000;
        color: #fff;
        padding: 1.5rem 3rem;
        border-radius: 2rem;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 3px;
        transition: 0.3s;
        border: none;
        cursor: pointer;
    }
    .btn-studio:hover { background: var(--cafe-p); transform: scale(1.03); }
    
    /* Input de carga estilo Converter Pro */
    .drop-zone {
        border: 4px dashed #e5e7eb;
        border-radius: 3rem;
        padding: 60px;
        transition: all 0.3s;
        cursor: pointer;
        background: #fafafa;
    }
    .drop-zone:hover { border-color: #000; background: #fff; }
</style>

<main class="max-w-5xl mx-auto px-6 py-16" x-data="alphaStudio()">
    
    <div class="fixed top-5 right-5 z-[100] space-y-3">
        <template x-for="(note, index) in notifications" :key="index">
            <div :class="note.type === 'error' ? 'bg-red-600' : 'bg-black'" 
                 class="text-white px-8 py-5 rounded-2xl shadow-2xl border-2 border-white flex items-center gap-4 animate-slide-in">
                <i class="fas fa-bolt" style="color: var(--accent)"></i>
                <span class="font-black uppercase text-[10px] tracking-widest" x-text="note.message"></span>
            </div>
        </template>
    </div>

    <div class="text-center mb-16">
        <h1 class="text-5xl md:text-7xl font-black mb-4 uppercase italic">
            Alpha <span style="color: var(--cafe-p);">Studio</span>
        </h1>
        <div class="flex items-center justify-center gap-3">
            <span class="h-[3px] w-12 bg-black"></span>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.4em]">Engine de Transparencia 2026</p>
            <span class="h-[3px] w-12 bg-black"></span>
        </div>
    </div>

    <div class="bg-white rounded-[3.5rem] p-8 md:p-12 shadow-2xl border-4 border-black relative overflow-hidden">
        
        <div x-show="loading" x-cloak class="absolute inset-0 bg-white/95 z-50 flex flex-col items-center justify-center">
            <div class="w-16 h-16 border-8 border-t-[var(--cafe-p)] border-gray-100 rounded-full animate-spin mb-6"></div>
            <p class="font-black text-xs uppercase tracking-[0.5em] italic">Aislando Canal Alpha...</p>
        </div>

        <div x-show="!resultReady" class="animate-fade-in">
            <div @click="$refs.fileInput.click()" class="drop-zone text-center group">
                <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept="image/*">
                
                <template x-if="!file">
                    <div>
                        <div class="w-20 h-20 bg-white shadow-lg rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform border-2 border-gray-50">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-200 group-hover:text-black"></i>
                        </div>
                        <p class="font-black uppercase text-sm tracking-widest">Inyectar Imagen de Trabajo</p>
                        <p class="text-[9px] text-gray-400 font-bold uppercase mt-3 tracking-[0.2em]">JPG, PNG o WEBP</p>
                    </div>
                </template>

                <template x-if="file">
                    <div class="animate-fade-in">
                        <div class="relative inline-block">
                            <img :src="preview" class="w-64 h-64 object-cover rounded-[2.5rem] border-4 border-black shadow-2xl">
                            <button @click.stop="reset" class="absolute -top-4 -right-4 bg-red-600 text-white w-10 h-10 rounded-full border-4 border-white flex items-center justify-center hover:bg-black transition">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p class="mt-6 font-black text-[10px] uppercase text-gray-400 italic" x-text="file.name"></p>
                    </div>
                </template>
            </div>

            <div x-show="file" class="mt-12 text-center">
                <button @click="processImage" class="btn-studio">
                    Iniciar Procesamiento Binario <i class="fas fa-chevron-right ml-2"></i>
                </button>
            </div>
        </div>

        <div x-show="resultReady" x-cloak class="animate-success text-center py-10">
            <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-8 border-4 border-green-600">
                <i class="fas fa-check text-4xl"></i>
            </div>
            
            <h2 class="text-3xl font-black uppercase italic mb-4">¡Renderizado Completo!</h2>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mb-10 max-w-md mx-auto">
                El fondo ha sido removido con éxito. Tu activo en formato <span class="text-black">PNG Alpha</span> ya está siendo procesado por el navegador.
            </p>

            <div class="flex flex-col md:flex-row items-center justify-center gap-4">
                <button @click="downloadResult" class="btn-studio !bg-green-600 flex items-center gap-3">
                    <i class="fas fa-download"></i> Descargar Resultado
                </button>
                <button @click="reset" class="btn-studio !bg-gray-100 !text-black border-2 border-black">
                    Quitar fondo de otra imagen
                </button>
            </div>
        </div>
    </div>

    <div class="mt-32 space-y-20 border-t-2 border-dashed border-gray-200 pt-20">
        
        <div class="text-center max-w-3xl mx-auto">
            <h2 class="text-4xl font-black uppercase tracking-tighter text-black italic mb-6">¿Qué es <span style="color: var(--cafe-p);">Alpha Studio</span>?</h2>
            <p class="text-gray-500 font-bold text-sm leading-relaxed uppercase tracking-wide">
                Es un laboratorio de segmentación visual de precisión. Nuestra herramienta utiliza inteligencia artificial avanzada para analizar mapas de bits y separar el sujeto principal del fondo, generando un canal de transparencia (Alpha) de alta fidelidad para uso profesional.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center">
                <div class="w-16 h-16 bg-white border-4 border-black rounded-full flex items-center justify-center mx-auto mb-6 shadow-[8px_8px_0px_#000]">
                    <i class="fas fa-file-import text-xl"></i>
                </div>
                <h3 class="font-black uppercase text-xs mb-4">1. Inyectar Asset</h3>
                <p class="text-[10px] text-gray-400 font-bold leading-relaxed uppercase">Sube una imagen donde el sujeto esté claramente definido. Funciona mejor con retratos, productos o logotipos.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-[8px_8px_0px_var(--accent)]">
                    <i class="fas fa-microchip text-xl"></i>
                </div>
                <h3 class="font-black uppercase text-xs mb-4">2. Procesamiento IA</h3>
                <p class="text-[10px] text-gray-400 font-bold leading-relaxed uppercase">Nuestra matriz identifica los bordes y calcula la transparencia píxel a píxel en la nube.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-white border-4 border-black rounded-full flex items-center justify-center mx-auto mb-6 shadow-[8px_8px_0px_#000]">
                    <i class="fas fa-file-export text-xl"></i>
                </div>
                <h3 class="font-black uppercase text-xs mb-4">3. Exportación Alpha</h3>
                <p class="text-[10px] text-gray-400 font-bold leading-relaxed uppercase">Recibe un archivo PNG de 24 bits listo para tus montajes en Photoshop, Canva o Premiere.</p>
            </div>
        </div>

        <div class="bg-black text-white p-10 md:p-16 rounded-[4rem] relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row gap-12 items-center">
                <div class="md:w-1/2">
                    <h2 class="text-3xl font-black uppercase italic mb-6">Tecnología de <br><span style="color: var(--accent);">Nube Volátil</span></h2>
                    <p class="text-gray-400 text-xs font-bold leading-relaxed uppercase">
                        Alpha Studio opera bajo el principio de <span class="text-white">Zero-Persistence</span>. 
                    </p>
                </div>
                <div class="md:w-1/2 border-l-2 border-gray-800 pl-8">
                    <p class="text-[11px] text-gray-500 font-bold leading-loose uppercase tracking-widest">
                        Al procesar una imagen, los datos se transfieren mediante un túnel cifrado a nuestros nodos de IA. Una vez generado el archivo con transparencia y entregado a tu navegador, la instancia de trabajo se destruye. No almacenamos historiales de tus archivos ni mantenemos copias en el servidor, garantizando la privacidad absoluta de tus activos creativos.
                    </p>
                </div>
            </div>
            <div class="absolute -bottom-10 -right-10 text-[10rem] text-gray-900/50 font-black italic select-none">ALPHA</div>
        </div>
    </div>

</main>

<script>
function alphaStudio() {
    return {
        file: null, preview: null, resultUrl: null, 
        loading: false, resultReady: false, notifications: [],

        notify(message, type = 'success') {
            this.notifications.push({ message, type });
            setTimeout(() => { this.notifications.shift(); }, 3500);
        },

        handleFile(e) {
            const f = e.target.files[0];
            if (!f) return;
            this.file = f;
            const reader = new FileReader();
            reader.onload = (e) => { this.preview = e.target.result; };
            reader.readAsDataURL(f);
        },

        async processImage() {
            this.loading = true;
            const fd = new FormData();
            fd.append('image', this.file);

            try {
                const r = await fetch('process_img.php', { method: 'POST', body: fd });
                const d = await r.json();
                if (d.success) {
                    this.resultUrl = atob(d.token);
                    this.resultReady = true;
                    this.notify('Procesamiento completado');
                } else {
                    this.notify('Error en la matriz de IA', 'error');
                }
            } catch (e) {
                this.notify('Fallo de conexión', 'error');
            }
            this.loading = false;
        },

        async downloadResult() {
            this.notify('Iniciando descarga de activo...');
            const res = await fetch(this.resultUrl);
            const blob = await res.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'alpha_studio_render.png';
            a.click();
        },

        reset() {
            this.file = null; 
            this.preview = null; 
            this.resultUrl = null; 
            this.resultReady = false;
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>