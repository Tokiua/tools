<?php
$pageTitle = "Nexosyne Image Compress";
$themeHex = "#10b981"; 
include '../../partials/Includes/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>

<main class="max-w-4xl mx-auto px-6 py-12 md:py-16" x-data="imageOptimizer()">
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tight uppercase italic text-black">
            Image <span class="text-theme">Compress</span>
        </h1>
        <p class="text-gray-500 font-bold uppercase text-[10px] tracking-[0.3em]">
            Procesamiento 100% Local • Zero-Storage
        </p>
    </div>

    <div class="card-unified p-6 md:p-10 relative shadow-2xl bg-white rounded-3xl mb-12">
        <div class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" x-model="mode" value="lossless" class="peer hidden">
                    <div class="p-4 rounded-xl border-2 border-gray-100 peer-checked:border-theme peer-checked:bg-emerald-50 transition-all text-center">
                        <p class="font-black uppercase text-[10px]">Optimización Normal</p>
                        <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">Equilibrio calidad/peso</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" x-model="mode" value="lossy" class="peer hidden">
                    <div class="p-4 rounded-xl border-2 border-gray-100 peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all text-center">
                        <p class="font-black uppercase text-[10px]">Compresión Agresiva</p>
                        <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">Máxima reducción de peso</p>
                    </div>
                </label>
            </div>

            <div class="space-y-4">
                <div x-show="files.length < 3 && processedFiles.length === 0" 
                     @click="$refs.fileInput.click()" 
                     class="w-full p-10 rounded-3xl border-2 border-dashed border-gray-200 hover:border-theme transition-all bg-gray-50/30 flex flex-col items-center justify-center cursor-pointer">
                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-300 mb-2"></i>
                    <p class="font-bold text-gray-500 text-xs uppercase">Arrastra o selecciona imágenes</p>
                </div>
                
                <input type="file" x-ref="fileInput" multiple accept="image/*" class="hidden" @change="handleFiles">

                <div class="grid grid-cols-3 gap-4" x-show="files.length > 0 && processedFiles.length === 0">
                    <template x-for="(file, index) in files" :key="index">
                        <div class="relative group animate-fade-in">
                            <img :src="file.preview" class="w-full h-24 object-cover rounded-xl border-2 border-gray-100">
                            <button type="button" @click="removeFile(index)" class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-[10px]">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
            
            <div class="flex gap-4">
                <button type="button" @click="compressImages" :disabled="files.length === 0 || loading || processedFiles.length > 0"
                    class="flex-[2] bg-black text-white py-6 rounded-2xl font-black text-base uppercase tracking-widest hover:bg-theme transition-all disabled:opacity-30 shadow-xl flex items-center justify-center gap-4">
                    <span x-text="loading ? 'Comprimiendo...' : 'Comprimir ahora'"></span>
                    <i class="fas" :class="loading ? 'fa-circle-notch animate-spin' : 'fa-bolt'"></i>
                </button>

                <button type="button" @click="clearAll" 
                    class="flex-1 bg-gray-100 text-gray-400 py-6 rounded-2xl font-black text-base uppercase hover:bg-red-50 hover:text-red-500 transition-all border border-gray-200">
                    Limpiar
                </button>
            </div>
        </div>

        <div x-show="processedFiles.length > 0" x-transition class="mt-10 border-t border-gray-100 pt-8">
            <h3 class="text-center font-black uppercase text-[10px] tracking-[0.2em] mb-6 text-gray-400">Resultados del proceso</h3>
            <div class="space-y-3">
                <template x-for="(item, index) in processedFiles" :key="index">
                    <div class="flex flex-col md:flex-row items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 animate-fade-in gap-4">
                        <div class="flex items-center gap-4 w-full">
                            <img :src="item.preview" class="w-14 h-14 rounded-lg object-cover shadow-sm">
                            <div class="flex-1">
                                <p class="text-[10px] font-black uppercase text-black truncate max-w-[150px]" x-text="item.name"></p>
                                <div class="flex gap-2 mt-1">
                                    <span class="text-[9px] bg-gray-200 px-2 py-0.5 rounded font-bold text-gray-500 uppercase" x-text="item.oldSize"></span>
                                    <i class="fas fa-arrow-right text-[8px] text-gray-300 self-center"></i>
                                    <span class="text-[9px] bg-emerald-100 px-2 py-0.5 rounded font-bold text-emerald-600 uppercase" x-text="item.newSize"></span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-black text-emerald-500" x-text="item.saving"></span>
                            </div>
                        </div>
                        <button @click="download(item)" class="w-full md:w-auto bg-black text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase hover:bg-theme transition-all flex items-center justify-center gap-2">
                            Descargar <i class="fas fa-download"></i>
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="space-y-6">
            <div class="card-unified p-8 bg-white rounded-[2rem] shadow-sm border border-gray-100">
                <h3 class="text-black font-black text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fas fa-question-circle text-theme"></i> ¿Qué es esta herramienta?
                </h3>
                <p class="text-gray-500 text-[11px] font-bold uppercase leading-relaxed">
                    Es un compresor de imágenes de última generación que funciona mediante <span class="text-black">Web Workers</span>. A diferencia de otros sitios, nosotros no subimos tus fotos a ningún servidor; la compresión ocurre directamente en la memoria de tu dispositivo.
                </p>
            </div>

            <div class="card-unified p-8 bg-white rounded-[2rem] shadow-sm border border-gray-100">
                <h3 class="text-black font-black text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fas fa-list-ol text-theme"></i> Guía de Uso
                </h3>
                <ul class="text-gray-500 text-[10px] font-black uppercase space-y-3">
                    <li class="flex items-start gap-2">
                        <span class="bg-theme text-white w-4 h-4 rounded-full flex items-center justify-center text-[8px]">1</span>
                        <span>Selecciona el modo (Normal o Agresivo).</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="bg-theme text-white w-4 h-4 rounded-full flex items-center justify-center text-[8px]">2</span>
                        <span>Sube hasta 3 imágenes (JPG, PNG o WebP).</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="bg-theme text-white w-4 h-4 rounded-full flex items-center justify-center text-[8px]">3</span>
                        <span>Presiona "Comprimir ahora" y espera los resultados.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="bg-theme text-white w-4 h-4 rounded-full flex items-center justify-center text-[8px]">4</span>
                        <span>Descarga cada archivo optimizado individualmente.</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card-unified p-8 bg-orange-50/50 rounded-[2rem] shadow-sm border border-orange-100">
                <h3 class="text-orange-600 font-black text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i> Posibles Problemas
                </h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-[10px] font-black text-black uppercase mb-1">¿El archivo pesa más?</h4>
                        <p class="text-gray-500 text-[10px] font-bold uppercase leading-tight">
                            Si la imagen ya fue comprimida por apps como WhatsApp, nuestra re-codificación para asegurar calidad estándar puede aumentar ligeramente el peso.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-black text-black uppercase mb-1">Error al procesar</h4>
                        <p class="text-gray-500 text-[10px] font-bold uppercase leading-tight">
                            Archivos extremadamente pesados (+20MB) pueden agotar la memoria de navegadores móviles antiguos.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-unified p-8 bg-emerald-50/50 rounded-[2rem] shadow-sm border border-emerald-100">
                <h3 class="text-theme font-black text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fas fa-shield-alt"></i> Seguridad Zero-Storage
                </h3>
                <p class="text-gray-500 text-[10px] font-bold uppercase leading-relaxed">
                    Al usar <span class="italic text-black">Client-Side Compression</span>, tus fotos nunca salen de tu computadora. Esto garantiza privacidad total y cumplimiento con normativas de protección de datos sensibles.
                </p>
            </div>
        </div>
    </div>
</main>

<script>
function imageOptimizer() {
    return {
        mode: 'lossless',
        files: [],
        processedFiles: [],
        loading: false,

        handleFiles(e) {
            const uploaded = Array.from(e.target.files);
            if (this.files.length + uploaded.length > 3) {
                alert("Máximo 3 imágenes.");
                return;
            }
            uploaded.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.files.push({
                        file: file,
                        preview: e.target.result,
                        name: file.name,
                        size: file.size
                    });
                };
                reader.readAsDataURL(file);
            });
        },

        async compressImages() {
            this.loading = true;
            const options = {
                maxSizeMB: this.mode === 'lossy' ? 0.4 : 1.2,
                maxWidthOrHeight: 1920,
                useWebWorker: true,
                initialQuality: this.mode === 'lossy' ? 0.5 : 0.8
            };

            try {
                for (let item of this.files) {
                    const compressedBlob = await imageCompression(item.file, options);
                    const saving = ((1 - (compressedBlob.size / item.size)) * 100).toFixed(1);
                    
                    this.processedFiles.push({
                        name: item.name,
                        preview: URL.createObjectURL(compressedBlob),
                        blob: compressedBlob,
                        oldSize: (item.size / 1024).toFixed(1) + ' KB',
                        newSize: (compressedBlob.size / 1024).toFixed(1) + ' KB',
                        saving: saving > 0 ? '-' + saving + '%' : 'Optimizada'
                    });
                }
            } catch (error) {
                console.error(error);
                alert("Error comprimiendo.");
            } finally {
                this.loading = false;
            }
        },

        download(item) {
            const link = document.createElement('a');
            link.href = item.preview;
            link.download = "nx_" + item.name;
            link.click();
        },

        clearAll() {
            this.files = [];
            this.processedFiles = [];
            this.loading = false;
            this.$refs.fileInput.value = '';
        },

        removeFile(index) {
            this.files.splice(index, 1);
        }
    }
}
</script>

<style>
.animate-fade-in { animation: fadeIn 0.4s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<?php include '../../partials/Includes/footer.php'; ?>