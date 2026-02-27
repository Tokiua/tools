<?php
$pageTitle = "TikTok Downloader";
$themeHex = "#7c3aed"; // Color Morado
include '../../partials/Includes/header.php';
?>

<main class="max-w-4xl mx-auto px-6 py-12 md:py-16">
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tight uppercase italic text-black">TikTok <span class="text-theme">Saver</span></h1>
        <p class="text-gray-500 font-bold uppercase text-[10px] md:text-xs tracking-[0.3em]">Descarga videos sin marca de agua</p>
    </div>

    <div class="tool-container bg-white rounded-[2rem] p-6 md:p-10 relative">
        <!-- Aquí iría la lógica específica de TikTok (Formulario, etc) -->
        <!-- Usando clases genéricas para mantener el estilo -->
        <form class="space-y-8">
            <div class="space-y-3">
                <label class="text-xs font-black uppercase tracking-widest text-theme italic">Pegar Enlace de TikTok:</label>
                <input type="text" placeholder="https://www.tiktok.com/@usuario/video/..." class="input-style">
            </div>
            
            <button type="button" class="w-full bg-black text-white py-6 rounded-2xl font-black text-base uppercase tracking-widest hover-bg-theme transition-all shadow-xl flex items-center justify-center gap-4 group">
                <span>Descargar Video</span>
                <i class="fas fa-download group-hover:translate-y-1 transition-transform"></i>
            </button>
        </form>
    </div>

    <div class="mt-24 space-y-12">
        <div class="text-center">
            <h2 class="text-3xl font-black uppercase tracking-tighter text-black italic">Características</h2>
            <div class="w-20 h-2 bg-theme mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
            <div class="bg-white p-8 rounded-3xl border-2 border-gray-100 shadow-sm">
                <div class="text-theme text-3xl font-black mb-4">HD</div>
                <h3 class="font-black uppercase text-sm mb-2 italic">Calidad Original</h3>
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider leading-relaxed">Obtén el video en la máxima resolución disponible.</p>
            </div>
            <div class="bg-white p-8 rounded-3xl border-2 border-gray-100 shadow-sm">
                <div class="text-theme text-3xl font-black mb-4"><i class="fas fa-ban"></i></div>
                <h3 class="font-black uppercase text-sm mb-2 italic">Sin Marca</h3>
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider leading-relaxed">Eliminamos el logo de TikTok automáticamente.</p>
            </div>
            <div class="bg-black text-white p-8 rounded-3xl border-2 border-black shadow-lg">
                <div class="text-theme text-3xl font-black mb-4"><i class="fas fa-music"></i></div>
                <h3 class="font-black uppercase text-sm mb-2 italic">MP3 Audio</h3>
                <p class="text-gray-500 text-[10px] font-bold uppercase tracking-wider leading-relaxed">Opción para extraer solo el audio del trend.</p>
            </div>
        </div>
    </div>
</main>

<script>
    function imageApp() { return {} } // Placeholder para evitar error de Alpine si no se usa
</script>

<?php include '../../partials/Includes/footer.php'; ?>