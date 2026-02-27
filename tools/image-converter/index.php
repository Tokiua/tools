<?php
$pageTitle = "Image Converter";
$themeHex = "#00D1B2"; // Verde turquesa moderno
include '../../partials/Includes/header.php';
?>

<main class="max-w-4xl mx-auto px-6 py-12" x-data="imageConverter()">
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-4 uppercase italic">
            Image <span style="color: <?= $themeHex ?>;">Converter</span>
        </h1>
        <p class="text-gray-500 font-bold uppercase text-[10px] tracking-[0.3em]">
            Convierte tus imágenes a WebP, PNG o JPG al instante
        </p>
    </div>

    <div class="tool-container bg-white rounded-[2rem] p-6 md:p-10 shadow-2xl border-4 border-black relative">
        
        <div x-show="loading" x-cloak 
             class="absolute inset-0 bg-white/90 z-50 flex flex-col items-center justify-center rounded-[2rem]">
            <div class="w-12 h-12 border-4 border-t-[#00D1B2] border-gray-200 rounded-full animate-spin mb-4"></div>
            <p class="font-black text-xs uppercase tracking-widest text-slate-800">Procesando imagen...</p>
        </div>

        <div class="flex flex-col gap-6">
            <div @click="$refs.fileInput.click()" 
                 class="border-4 border-dashed border-gray-200 rounded-3xl p-10 flex flex-col items-center justify-center cursor-pointer hover:border-[#00D1B2] transition-colors group">
                <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept="image/*">
                
                <template x-if="!file">
                    <div class="text-center">
                        <i class="fas fa-cloud-upload-alt text-5xl text-gray-300 group-hover:text-[#00D1B2] mb-4 transition-colors"></i>
                        <p class="font-black uppercase text-sm">Haz clic o arrastra una imagen</p>
                        <p class="text-gray-400 text-[10px] mt-2 font-bold uppercase tracking-widest">JPG, PNG, WEBP (Máx 10MB)</p>
                    </div>
                </template>

                <template x-if="file">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-file-image text-[#00D1B2] text-2xl"></i>
                        <span class="font-black text-xs uppercase tracking-tighter" x-text="file.name"></span>
                        <button @click.stop="file = null" class="text-red-500 hover:scale-110 transition"><i class="fas fa-times-circle"></i></button>
                    </div>
                </template>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-gray-50 p-4 rounded-2xl border-2 border-black/5">
                <span class="font-black uppercase text-[10px] tracking-widest text-gray-400 italic">Convertir a:</span>
                <div class="flex gap-2">
                    <template x-for="fmt in formats">
                        <button @click="targetFormat = fmt" 
                                :class="targetFormat === fmt ? 'bg-black text-white' : 'bg-white text-black border-2 border-black/10'"
                                class="px-6 py-2 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all"
                                x-text="fmt"></button>
                    </template>
                </div>
            </div>

            <button @click="convertImage" :disabled="!file"
                    class="bg-black text-white w-full py-5 rounded-2xl font-black uppercase hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
                <span>Convertir Ahora</span>
                <i class="fas fa-magic"></i>
            </button>
        </div>

        <template x-if="result">
            <div class="mt-8 p-6 bg-[#00D1B2]/10 rounded-3xl border-2 border-[#00D1B2]/30 flex flex-col md:flex-row items-center justify-between gap-4 animate-fade-in">
                <div class="flex items-center gap-4">
                    <div class="bg-white p-2 rounded-lg shadow-sm border border-black/5 text-[10px] font-black uppercase" x-text="targetFormat"></div>
                    <span class="font-bold text-xs uppercase truncate max-w-[200px]" x-text="result.name"></span>
                </div>
                <a :href="result.url" :download="result.name" 
                   class="bg-black text-white px-8 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest hover:brightness-125 transition shadow-lg flex items-center gap-2">
                    Descargar <i class="fas fa-download"></i>
                </a>
            </div>
        </template>
    </div>
</main>

<script>
function imageConverter() {
    return {
        file: null,
        loading: false,
        formats: ['webp', 'png', 'jpg'],
        targetFormat: 'webp',
        result: null,
        handleFile(e) {
            const file = e.target.files[0];
            if (file) this.file = file;
        },
        async convertImage() {
            if (!this.file) return;
            this.loading = true;
            this.result = null;

            const formData = new FormData();
            formData.append('image', this.file);
            formData.append('format', this.targetFormat);

            try {
                const res = await fetch('process_img.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    this.result = data;
                } else {
                    alert(data.error);
                }
            } catch (e) {
                alert('Error al procesar la imagen.');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<?php include '../../partials/Includes/footer.php'; ?>