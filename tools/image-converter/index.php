<?php
$pageTitle = "Image Converter Pro";
$themeHex = "#00D1B2"; 
include '../../partials/Includes/header.php';
?>

<style>
    [x-cloak] { display: none !important; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    .image-glow { transition: all 0.3s ease; }
    .image-glow:hover { box-shadow: 0 0 15px <?= $themeHex ?>; transform: scale(1.02); }
</style>

<main class="max-w-4xl mx-auto px-6 py-12" x-data="imageConverter()">
    
    <div class="fixed top-5 right-5 z-[100] space-y-3">
        <template x-for="(note, index) in notifications" :key="index">
            <div :class="note.type === 'error' ? 'bg-red-600' : 'bg-black'" 
                 class="text-white px-6 py-4 rounded-2xl shadow-2xl border-2 border-white flex items-center gap-3 animate-slide-in">
                <i :class="note.type === 'error' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle'" class="text-[#00D1B2]"></i>
                <span class="font-black uppercase text-[10px] tracking-widest" x-text="note.message"></span>
            </div>
        </template>
    </div>

    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-4 uppercase italic">
            Image <span style="color: <?= $themeHex ?>;">Converter</span>
        </h1>
        <h3 class="text-xl md:text-2xl font-black uppercase italic tracking-tighter text-black mb-4">
    Cambia el formato de tu archivo y <span class="text-theme">transforma tu activo</span>
</h3>
        <p class="text-gray-500 font-bold uppercase text-[10px] tracking-[0.3em]">
            Procesamiento por lote inteligente | Cero Almacenamiento
        </p>
    </div>

    <div class="bg-white rounded-[2rem] p-6 md:p-10 shadow-2xl border-4 border-black relative">
        
        <div x-show="loading" x-cloak class="absolute inset-0 bg-white/90 z-50 flex flex-col items-center justify-center rounded-[1.8rem]">
            <div class="w-12 h-12 border-4 border-t-[#00D1B2] border-gray-200 rounded-full animate-spin mb-4"></div>
            <p class="font-black text-[10px] uppercase tracking-widest" x-text="statusText"></p>
        </div>

        <div class="space-y-8">
            <template x-if="files.length > 0">
                <div class="flex items-center justify-center gap-2 bg-gray-100 py-2 rounded-full border-2 border-black">
                    <span class="text-[9px] font-black uppercase italic text-gray-400">Solo permitiendo:</span>
                    <span class="bg-black text-[#00D1B2] px-3 py-1 rounded-full text-[9px] font-black uppercase" x-text="allowedTypeLabel"></span>
                </div>
            </template>

            <div @click="$refs.fileInput.click()" 
                 @dragover.prevent="droping = true" @dragleave.prevent="droping = false" @drop.prevent="handleDrop"
                 :class="droping ? 'border-[#00D1B2] bg-[#00D1B2]/5' : 'border-gray-200'"
                 class="border-4 border-dashed rounded-[2.5rem] p-8 flex flex-col items-center justify-center cursor-pointer hover:border-black transition-all group">
                
                <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept="image/*" multiple>
                
                <template x-if="files.length === 0">
                    <div class="text-center py-10">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition">
                            <i class="fas fa-images text-3xl text-gray-300"></i>
                        </div>
                        <p class="font-black uppercase text-sm">Arrastra tus archivos</p>
                        <p class="text-[9px] text-gray-400 font-bold uppercase mt-2 tracking-widest italic">Sube el primero para definir el tipo de lote</p>
                    </div>
                </template>

                <template x-if="files.length > 0">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 w-full">
                        <template x-for="(f, index) in files" :key="index">
                            <div class="group relative aspect-square bg-black rounded-3xl overflow-hidden border-2 border-black image-glow">
                                <img :src="f.preview" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-4">
                                    <p class="text-white font-black text-[8px] uppercase truncate w-full" x-text="f.name"></p>
                                </div>
                                <button @click.stop="removeFile(index)" class="absolute top-2 right-2 bg-red-500 text-white w-7 h-7 rounded-xl flex items-center justify-center shadow-xl hover:bg-black transition">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        </template>
                        <template x-if="files.length < 3">
                            <div class="aspect-square border-4 border-dashed border-gray-100 rounded-3xl flex items-center justify-center hover:border-[#00D1B2] transition-colors">
                                <i class="fas fa-plus text-gray-200 text-2xl"></i>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <div x-show="files.length > 0" x-transition class="space-y-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <h3 class="font-black uppercase text-[11px] italic tracking-widest">¿A qué formato lo llevamos?</h3>
                    <div class="flex flex-wrap justify-center gap-2">
                        <template x-for="fmt in ['webp', 'png', 'jpg', 'ico', 'bmp']">
                            <button @click="targetFormat = fmt" 
                                    :class="targetFormat === fmt ? 'bg-[#00D1B2] text-black border-black' : 'bg-white text-black border-gray-200'"
                                    class="px-5 py-2 border-2 rounded-xl font-black uppercase text-[10px] transition-all"
                                    x-text="fmt"></button>
                        </template>
                    </div>
                </div>
                <button @click="convertBatch" class="bg-black text-[#00D1B2] w-full py-6 rounded-3xl font-black uppercase text-sm hover:shadow-[0_15px_30px_-10px_rgba(0,209,178,0.4)] transition-all flex items-center justify-center gap-4">
                    <span>Procesar Lote y Entregar</span>
                    <i class="fas fa-bolt text-white"></i>
                </button>
            </div>

            <div x-show="results.length > 0" class="pt-8 border-t-4 border-black border-dotted space-y-4">
                <template x-for="res in results">
                    <div class="p-5 bg-black rounded-2xl flex items-center justify-between border-2 border-[#00D1B2]">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 bg-[#00D1B2] rounded-lg flex items-center justify-center font-black text-black text-[10px]" x-text="targetFormat"></div>
                            <span class="text-white font-bold text-[10px] uppercase truncate max-w-[150px]" x-text="res.name"></span>
                        </div>
                        <a :href="res.url" :download="res.name" @click="notify('Descargando archivo...')" 
                           class="bg-white text-black px-6 py-2 rounded-xl font-black uppercase text-[9px] hover:bg-[#00D1B2] transition">
                            Descargar
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="mt-20">
        <h3 class="text-2xl font-black uppercase italic mb-8 text-center">Matriz de <span style="color: <?= $themeHex ?>;">Procesamiento</span></h3>
        <div class="overflow-x-auto rounded-[2rem] border-4 border-black shadow-xl">
            <table class="w-full text-left">
                <thead class="bg-black text-white text-[10px] font-black uppercase">
                    <tr>
                        <th class="p-5">Lo que el sistema acepta</th>
                        <th class="p-5">Lo que el sistema entrega</th>
                        <th class="p-5">Memoria RAM (Servidor)</th>
                    </tr>
                </thead>
                <tbody class="text-[10px] font-bold uppercase text-gray-500">
                    <tr class="border-b">
                        <td class="p-5 text-black">JPG, JPEG, PNG, WEBP, BMP, GIF</td>
                        <td class="p-5 text-black font-black italic">Conversión cruzada total</td>
                        <td class="p-5 text-green-500 italic">Limpieza automática <i class="fas fa-broom ml-1"></i></td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="p-5">Cualquier formato de imagen</td>
                        <td class="p-5 text-black">ICO (Favicon 32x32px)</td>
                        <td class="p-5 text-green-500 italic">Sin rastros en disco <i class="fas fa-shield-alt ml-1"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
function imageConverter() {
    return {
        files: [], results: [], notifications: [], 
        loading: false, targetFormat: 'webp', statusText: '',
        allowedType: null, allowedTypeLabel: '',

        notify(message, type = 'success') {
            this.notifications.push({ message, type });
            setTimeout(() => { this.notifications.shift(); }, 3000);
        },

        handleFile(e) { this.addFiles(e.target.files); },
        handleDrop(e) { this.droping = false; this.addFiles(e.dataTransfer.files); },

        addFiles(incoming) {
            const arrayFiles = Array.from(incoming);
            
            arrayFiles.forEach(file => {
                if (this.files.length >= 3) {
                    this.notify('Límite de 3 archivos por lote', 'error');
                    return;
                }

                // Definir tipo permitido basado en el primer archivo
                if (this.files.length === 0) {
                    this.allowedType = file.type;
                    this.allowedTypeLabel = file.name.split('.').pop();
                }

                if (file.type !== this.allowedType) {
                    this.notify(`Solo puedes subir archivos .${this.allowedTypeLabel} en este lote`, 'error');
                    return;
                }

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.files.push({ file: file, name: file.name, preview: e.target.result });
                    };
                    reader.readAsDataURL(file);
                }
            });
        },

        removeFile(index) {
            this.files.splice(index, 1);
            if (this.files.length === 0) {
                this.allowedType = null;
                this.allowedTypeLabel = '';
            }
        },

        resetAll() {
            this.files = []; this.results = []; this.allowedType = null;
            this.$refs.fileInput.value = '';
            this.notify('Memoria del navegador limpia');
        },

        async convertBatch() {
            this.loading = true;
            this.results = [];
            
            for (let i = 0; i < this.files.length; i++) {
                this.statusText = `Procesando volátil: ${this.files[i].name}`;
                const fd = new FormData();
                fd.append('image', this.files[i].file);
                fd.append('format', this.targetFormat);

                try {
                    const r = await fetch('process_img.php', { method: 'POST', body: fd });
                    const d = await r.json();
                    if (d.success) {
                        this.results.push(d);
                    } else {
                        this.notify(d.error || 'Error en conversión', 'error');
                    }
                } catch (e) {
                    this.notify('Error de conexión con el servidor', 'error');
                }
            }
            this.loading = false;
            this.files = []; // Limpieza inmediata de originales
            this.allowedType = null;
            this.notify('¡Lote procesado con éxito!');
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>