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
    .btn-studio:hover:not(:disabled) { background: var(--cafe-p); transform: scale(1.03); }
    .btn-studio:disabled { background: #ccc; cursor: not-allowed; opacity: 0.6; }
    
    .drop-zone {
        border: 4px dashed #e5e7eb;
        border-radius: 3rem;
        padding: 60px;
        transition: all 0.3s;
        cursor: pointer;
        background: #fafafa;
    }
    .drop-zone:hover { border-color: #000; background: #fff; }

    /* Estilo Bloqueo */
    .limit-reached {
        background: #fff1f1;
        border: 4px solid #f87171;
        color: #991b1b;
    }
</style>

<main class="max-w-5xl mx-auto px-6 py-16" x-data="alphaStudio()" x-init="checkLimits()">
    
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
        <h3 class="text-xl md:text-2xl font-black uppercase italic tracking-tighter text-black mb-4">
            Remueve el fondo al 100% y logra <span class="text-theme">transparencia total</span>
        </h3>
        
        <div class="inline-flex items-center gap-2 bg-black text-white px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest mb-6">
            <i class="fas fa-battery-half" style="color: var(--accent)"></i>
            Créditos diarios: <span x-text="3 - usageCount"></span> / 3
        </div>

        <div class="flex items-center justify-center gap-3">
            <span class="h-[3px] w-12 bg-black"></span>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.4em]">Engine de Transparencia 2026</p>
            <span class="h-[3px] w-12 bg-black"></span>
        </div>
    </div>

    <div class="bg-white rounded-[3.5rem] p-8 md:p-12 shadow-2xl border-4 border-black relative overflow-hidden" :class="isLocked ? 'limit-reached' : ''">
        
        <div x-show="isLocked" x-cloak class="text-center py-20 animate-fade-in">
            <div class="w-24 h-24 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-8 border-4 border-red-600">
                <i class="fas fa-clock text-4xl"></i>
            </div>
            <h2 class="text-3xl font-black uppercase italic mb-4">Límite Diario Alcanzado</h2>
            <p class="text-red-900 font-bold uppercase text-[10px] tracking-widest mb-10 max-w-md mx-auto leading-loose">
                Lo sentimos, has agotado tus 3 créditos gratuitos. <br> Regresa después de 24 horas para seguir procesando imágenes en Alpha Studio.
            </p>
            <div class="font-black text-xs uppercase tracking-widest text-red-600">
                Reinicio disponible en: <span x-text="timeLeft"></span>
            </div>
        </div>

        <div x-show="!isLocked">
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
    </div>

    <div class="mt-32 space-y-20 border-t-2 border-dashed border-gray-200 pt-20">
        
        <div class="text-center max-w-3xl mx-auto">
            <h2 class="text-4xl font-black uppercase tracking-tighter text-black italic mb-6">¿Qué es <span style="color: var(--cafe-p);">Alpha Studio</span>?</h2>
            <p class="text-gray-500 font-bold text-sm leading-relaxed uppercase tracking-wide">
                Es un laboratorio de segmentación visual de precisión. Nuestra herramienta utiliza inteligencia artificial avanzada para analizar mapas de bits y separar el sujeto principal del fondo. <strong>Limitado a 3 usos diarios para optimizar el rendimiento de la red Nexosyne.</strong>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center">
                <div class="w-16 h-16 bg-white border-4 border-black rounded-full flex items-center justify-center mx-auto mb-6 shadow-[8px_8px_0px_#000]">
                    <i class="fas fa-file-import text-xl"></i>
                </div>
                <h3 class="font-black uppercase text-xs mb-4">1. Inyectar Asset</h3>
                <p class="text-[10px] text-gray-400 font-bold leading-relaxed uppercase">Sube una imagen. Los datos se procesan pero no se almacenan en nuestros servidores.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-[8px_8px_0px_var(--accent)]">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
                <h3 class="font-black uppercase text-xs mb-4">2. Protección de Red</h3>
                <p class="text-[10px] text-gray-400 font-bold leading-relaxed uppercase">Aplicamos un límite de 3 procesos cada 24 horas para garantizar la estabilidad del motor IA.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-white border-4 border-black rounded-full flex items-center justify-center mx-auto mb-6 shadow-[8px_8px_0px_#000]">
                    <i class="fas fa-history text-xl"></i>
                </div>
                <h3 class="font-black uppercase text-xs mb-4">3. Reinicio Automático</h3>
                <p class="text-[10px] text-gray-400 font-bold leading-relaxed uppercase">Tu contador se reinicia automáticamente pasado el ciclo de 24 horas desde tu primer uso.</p>
            </div>
        </div>
    </div>

</main>

<script>
function alphaStudio() {
    return {
        file: null, preview: null, resultUrl: null, 
        loading: false, resultReady: false, notifications: [],
        usageCount: 0, isLocked: false, timeLeft: '',

        checkLimits() {
            const data = JSON.parse(localStorage.getItem('alpha_usage') || '{"count":0, "firstUse":null}');
            const now = Date.now();
            const cycle = 24 * 60 * 60 * 1000; // 24 Horas

            if (data.firstUse && (now - data.firstUse > cycle)) {
                // El ciclo terminó, reiniciamos
                localStorage.removeItem('alpha_usage');
                this.usageCount = 0;
                this.isLocked = false;
            } else {
                this.usageCount = data.count;
                if (data.count >= 3) {
                    this.isLocked = true;
                    this.startTimer(data.firstUse + cycle);
                }
            }
        },

        startTimer(endTime) {
            const update = () => {
                const now = Date.now();
                const diff = endTime - now;
                if (diff <= 0) {
                    this.isLocked = false;
                    localStorage.removeItem('alpha_usage');
                    return;
                }
                const h = Math.floor(diff / 3600000);
                const m = Math.floor((diff % 3600000) / 60000);
                const s = Math.floor((diff % 60000) / 1000);
                this.timeLeft = `${h}h ${m}m ${s}s`;
            };
            update();
            setInterval(update, 1000);
        },

        registerUsage() {
            let data = JSON.parse(localStorage.getItem('alpha_usage') || '{"count":0, "firstUse":null}');
            if (!data.firstUse) data.firstUse = Date.now();
            data.count++;
            localStorage.setItem('alpha_usage', JSON.stringify(data));
            this.usageCount = data.count;
            if (data.count >= 3) this.isLocked = true;
        },

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
            if (this.isLocked) return;
            
            this.loading = true;
            const fd = new FormData();
            fd.append('image', this.file);

            try {
                const r = await fetch('process_img.php', { method: 'POST', body: fd });
                const d = await r.json();
                if (d.success) {
                    this.resultUrl = atob(d.token);
                    this.resultReady = true;
                    this.registerUsage(); // Registrar uso exitoso
                    this.notify('Procesamiento completado');
                } else {
                    this.notify(d.error || 'Error en la matriz de IA', 'error');
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
            this.checkLimits(); // Revisar si al resetear ya se cumplió el tiempo
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