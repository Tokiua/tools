<?php
$pageTitle = "Image Optimizer HD";
$themeHex = "#8B4513"; // Color Café
include '../../partials/Includes/header.php';
?>

<main class="max-w-4xl mx-auto px-6 py-12 md:py-16" x-data="imageApp()">
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tight uppercase italic text-black">
            Image <span class="text-theme">Optimizer</span>
        </h1>
        <p class="text-gray-500 font-bold uppercase text-[10px] md:text-xs tracking-[0.3em]">
            Redimensiona y optimiza tus fotos al instante
        </p>
    </div>

    <div class="card-unified p-6 md:p-10 relative">
        
        <div x-show="isProcessing" x-cloak class="absolute inset-0 bg-white/90 z-50 flex flex-col items-center justify-center rounded-[2rem]">
            <div class="w-12 h-12 border-4 border-gray-100 border-t-theme rounded-full animate-spin mb-4"></div>
            <p class="font-black uppercase text-[10px] tracking-widest text-theme">Procesando Descarga...</p>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-8">
            <div class="space-y-3">
                <label class="text-xs font-black uppercase tracking-widest text-theme italic">1. Selecciona tu imagen:</label>
                <div class="relative border-4 border-dashed border-gray-100 rounded-3xl p-6 transition-all hover:border-orange-900 flex flex-col items-center justify-center min-h-[200px] bg-gray-50/30">
                    <input type="file" name="image" id="imageInput" @change="handleFile" 
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                    
                    <template x-if="!previewUrl">
                        <div class="text-center">
                            <i class="fas fa-cloud-arrow-up text-4xl text-gray-200 mb-4"></i>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Haz clic o arrastra una imagen</p>
                        </div>
                    </template>

                    <template x-if="previewUrl">
                        <div class="flex flex-col items-center">
                            <img :src="previewUrl" class="max-h-64 rounded-xl shadow-2xl border-2 border-black object-contain mb-4">
                            <span class="bg-black text-white text-[9px] font-black px-4 py-2 rounded-full uppercase tracking-widest">Imagen Cargada</span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest ml-1">Ancho (PX)</label>
                    <input type="number" name="width" id="width" placeholder="1920" 
                           class="w-full p-4 rounded-xl border-2 border-gray-100 focus:border-theme outline-none font-bold" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest ml-1">Alto (PX)</label>
                    <input type="number" name="height" id="height" placeholder="1080" 
                           class="w-full p-4 rounded-xl border-2 border-gray-100 focus:border-theme outline-none font-bold" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest ml-1">Formato</label>
                    <select name="format" id="format" class="w-full p-4 rounded-xl border-2 border-gray-100 focus:border-theme outline-none font-bold text-xs bg-white">
                        <option value="webp">WebP (Ultra Ligero)</option>
                        <option value="jpg">JPG (Estándar)</option>
                        <option value="png">PNG (Transparente)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full bg-black text-white py-6 rounded-2xl font-black text-base uppercase tracking-widest hover:bg-orange-950 transition-all shadow-xl flex items-center justify-center gap-4 group">
                <span>Optimizar e Imprimir</span>
                <i class="fas fa-bolt group-hover:rotate-12 transition-transform"></i>
            </button>
        </form>
    </div>
    <div class="mt-24 space-y-20">
        
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="text-3xl font-black uppercase tracking-tighter text-black italic mb-6">¿Qué es Nexosyne Image Optimizer?</h2>
            <p class="text-gray-500 font-bold text-sm leading-relaxed">
                Es un motor de procesamiento visual avanzado que permite redimensionar, cambiar de formato y optimizar el peso de tus imágenes sin comprometer la nitidez. Diseñado para creadores y desarrolladores que necesitan precisión técnica al instante.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-[2.5rem] border-2 border-gray-50 shadow-sm text-center">
                <div class="w-12 h-12 bg-orange-100 text-[#8B4513] rounded-full flex items-center justify-center mx-auto mb-6 font-black text-xl">1</div>
                <h3 class="font-black uppercase text-xs mb-3 tracking-widest">Sube tu Imagen</h3>
                <p class="text-gray-400 text-[10px] font-bold uppercase leading-relaxed">Selecciona cualquier archivo JPG, PNG o WebP desde tu dispositivo.</p>
            </div>
            <div class="bg-white p-8 rounded-[2.5rem] border-2 border-gray-50 shadow-sm text-center">
                <div class="w-12 h-12 bg-orange-100 text-[#8B4513] rounded-full flex items-center justify-center mx-auto mb-6 font-black text-xl">2</div>
                <h3 class="font-black uppercase text-xs mb-3 tracking-widest">Configura Medidas</h3>
                <p class="text-gray-400 text-[10px] font-bold uppercase leading-relaxed">Define el ancho y alto exactos, y elige el formato de salida ideal para tu proyecto.</p>
            </div>
            <div class="bg-black p-8 rounded-[2.5rem] text-center shadow-2xl">
                <div class="w-12 h-12 bg-[#8B4513] text-white rounded-full flex items-center justify-center mx-auto mb-6 font-black text-xl">3</div>
                <h3 class="font-black uppercase text-xs mb-3 tracking-widest text-white">Descarga y Aplica</h3>
                <p class="text-gray-500 text-[10px] font-bold uppercase leading-relaxed">Recibe tu imagen optimizada directamente en tu carpeta de descargas.</p>
            </div>
        </div>

        <div class="card-unified p-8 md:p-12 bg-gray-50/50 border-dashed">
            <div class="flex flex-col md:flex-row gap-10 items-center">
                <div class="md:w-1/3 text-center md:text-left">
                    <div class="text-5xl mb-4 text-[#8B4513]"><i class="fas fa-microchip"></i></div>
                    <h2 class="text-2xl font-black uppercase tracking-tighter italic">Procesamiento en <span class="text-[#8B4513]">RAM</span></h2>
                </div>
                <div class="md:w-2/3 space-y-4">
                    <p class="text-gray-600 font-bold text-xs leading-relaxed uppercase tracking-wide">
                        Privacidad técnica: <span class="text-black">Tus fotos nunca tocan el disco duro de nuestro servidor</span>. 
                    </p>
                    <p class="text-gray-500 text-[11px] font-semibold leading-relaxed">
                        Nuestra tecnología funciona mediante el flujo de datos volátiles. Al cargar una imagen, el motor la procesa estrictamente en la memoria temporal (RAM). Una vez que la descarga se inicia en tu navegador, el espacio de memoria utilizado se destruye automáticamente. No guardamos registros, no almacenamos miniaturas, ni mantenemos copias de seguridad. Es un flujo puro de entrada y salida.
                    </p>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
    function imageApp() {
        return {
            previewUrl: null,
            isProcessing: false,
            handleFile(e) {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.previewUrl = event.target.result;
                };
                reader.readAsDataURL(file);
            },
            async handleSubmit() {
                const fileInput = document.getElementById('imageInput');
                if (!fileInput.files[0]) return alert("Selecciona una imagen");

                this.isProcessing = true;
                
                const formData = new FormData();
                formData.append('image', fileInput.files[0]);
                formData.append('width', document.getElementById('width').value);
                formData.append('height', document.getElementById('height').value);
                formData.append('format', document.getElementById('format').value);

                try {
                    const response = await fetch('process_img.php', {
                        method: 'POST',
                        body: formData
                    });

                    if (response.ok) {
                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        
                        const format = document.getElementById('format').value;
                        a.download = `nexosyne_${Date.now()}.${format}`;
                        
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        a.remove();
                    } else {
                        alert("Error en el motor de imagen.");
                    }
                } catch (error) {
                    alert("Error de conexión con Nexosyne Engine.");
                } finally {
                    this.isProcessing = false;
                }
            }
        }
    }
</script>

<?php include '../../partials/Includes/footer.php'; ?>