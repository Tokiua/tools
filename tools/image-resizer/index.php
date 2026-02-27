<?php
$pageTitle = "Image Optimizer HD";
$themeHex = "#8B4513"; // Color Café
include '../../partials/Includes/header.php';
?>

    <main class="max-w-4xl mx-auto px-6 py-12 md:py-16">
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tight uppercase italic text-black">Image <span class="text-theme">Optimizer</span></h1>
            <p class="text-gray-500 font-bold uppercase text-[10px] md:text-xs tracking-[0.3em]">Redimensiona y optimiza tus fotos al instante</p>
        </div>

        <div class="tool-container bg-white rounded-[2rem] p-6 md:p-10 relative">
            
            <div x-show="isProcessing" x-cloak class="absolute inset-0 bg-white/90 z-50 flex flex-col items-center justify-center rounded-[2rem]">
                <div class="w-12 h-12 border-4 border-gray-100 border-t-theme rounded-full animate-spin mb-4" style="border-top-color: var(--theme-color);"></div>
                <p class="font-black uppercase text-[10px] tracking-widest text-theme">Procesando Descarga<span class="loading-dots"></span></p>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-8">
                <div class="space-y-3">
                    <label class="text-xs font-black uppercase tracking-widest text-theme italic">1. Selecciona tu imagen:</label>
                    <div class="relative border-4 border-dashed border-gray-100 rounded-3xl p-6 transition-all hover-border-theme flex flex-col items-center justify-center min-h-[200px]" style="&:hover { border-color: var(--theme-color); }">
                        <input type="file" name="image" id="imageInput" @change="handleFile" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                        
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
                        <input type="number" name="width" id="width" placeholder="1920" class="input-style" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest ml-1">Alto (PX)</label>
                        <input type="number" name="height" id="height" placeholder="1080" class="input-style" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest ml-1">Formato</label>
                        <select name="format" id="format" class="input-style text-xs">
                            <option value="webp">WebP (Ultra Ligero)</option>
                            <option value="jpg">JPG (Estándar)</option>
                            <option value="png">PNG (Transparente)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full bg-black text-white py-6 rounded-2xl font-black text-base uppercase tracking-widest hover-bg-theme transition-all shadow-xl flex items-center justify-center gap-4 group">
                    <span>Optimizar e Imprimir</span>
                    <i class="fas fa-bolt group-hover:rotate-12 transition-transform"></i>
                </button>
            </form>
        </div>

        <div class="mt-24 space-y-12">
            <div class="text-center">
                <h2 class="text-3xl font-black uppercase tracking-tighter text-black italic">Proceso Nexosyne</h2>
                <div class="w-20 h-2 bg-theme mx-auto mt-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <div class="bg-white p-8 rounded-3xl border-2 border-gray-100 shadow-sm">
                    <div class="text-theme text-3xl font-black mb-4">01</div>
                    <h3 class="font-black uppercase text-sm mb-2 italic">Carga Directa</h3>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider leading-relaxed">Sube cualquier formato: JPG, PNG o WebP sin restricciones de peso inicial.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border-2 border-gray-100 shadow-sm">
                    <div class="text-theme text-3xl font-black mb-4">02</div>
                    <h3 class="font-black uppercase text-sm mb-2 italic">Ajuste Exacto</h3>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider leading-relaxed">Nuestro motor recalcula los píxeles para mantener la nitidez incluso en redimensiones extremas.</p>
                </div>
                <div class="bg-black text-white p-8 rounded-3xl border-2 border-black shadow-lg">
                    <div class="text-orange-400 text-3xl font-black mb-4">03</div>
                    <h3 class="font-black uppercase text-sm mb-2 italic">Auto-Download</h3>
                    <p class="text-gray-500 text-[10px] font-bold uppercase tracking-wider leading-relaxed">Sin esperas. El archivo se genera en el servidor y se dispara la descarga a tu carpeta local.</p>
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
                    reader.onload = (event) => this.previewUrl = event.target.result;
                    reader.readAsDataURL(file);
                },
                async handleSubmit() {
                    this.isProcessing = true;
                    
                    const formData = new FormData();
                    formData.append('image', document.getElementById('imageInput').files[0]);
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
                            
                            // Extraer nombre de archivo del header o usar genérico
                            const contentDisposition = response.headers.get('Content-Disposition');
                            let fileName = 'nexosyne_image.' + document.getElementById('format').value;
                            if (contentDisposition && contentDisposition.includes('filename=')) {
                                fileName = contentDisposition.split('filename=')[1].replace(/"/g, '');
                            }
                            
                            a.download = fileName;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            a.remove();
                        } else {
                            alert("Error al procesar la imagen.");
                        }
                    } catch (error) {
                        console.error(error);
                        alert("Error de conexión.");
                    } finally {
                        this.isProcessing = false;
                    }
                }
            }
        }
    </script>

<?php include '../../partials/Includes/footer.php'; ?>