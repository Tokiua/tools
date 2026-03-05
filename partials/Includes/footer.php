<footer class="bg-black text-white pt-20 pb-10 px-6 mt-20">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-20 mb-16">
        <div>
            <img src="../../assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" alt="Nexosyne" class="h-12 mb-8 footer-logo" style="filter: drop-shadow(2px 0 0 white) drop-shadow(-2px 0 0 white) drop-shadow(0 2px 0 white) drop-shadow(0 -2px 0 white);">
            <p class="text-gray-400 font-bold text-sm leading-relaxed max-w-sm">
                Herramientas de alto rendimiento. Privacidad total: Procesamiento en memoria volátil sin almacenamiento en servidor.
            </p>
        </div>
        <div class="flex gap-16 md:justify-end">
            <div class="flex flex-col gap-4">
                <span class="text-theme font-black text-xs uppercase tracking-widest italic">Herramientas</span>
                <template x-for="item in menuItems">
                    <a @click="go(item.id)" class="font-bold text-sm hover:text-purple-400 transition cursor-pointer" x-text="item.name"></a>
                </template>
            </div>
            <div class="flex flex-col gap-4">
                <span class="text-theme font-black text-xs uppercase tracking-widest italic">Navegación</span>
                <a href="../../index.php" class="font-bold text-sm hover:text-purple-400 transition">Inicio</a>
                <a href="../../index.php#nosotros" class="font-bold text-sm hover:text-purple-400 transition">Tecnología</a>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto pt-8 border-t border-gray-900 flex flex-col md:flex-row justify-between items-center gap-4 text-gray-500 text-[10px] font-black uppercase tracking-[0.3em]">
        <span>© 2026 Nexosyne Multimedia</span>
        <a href="https://tools.nexosyne.com" class="hover:text-white transition italic">tools.nexosyne.com</a>
    </div>
</footer>

<script>
    function nexosyneCore() {
        return {
            mobileMenu: false,
            menuItems: [
                { id: 'tiktok', name: 'TikTok Downloader', icon: 'fab fa-tiktok', textCol: 'text-black', bgCol: 'bg-black' },
                { id: 'lumina', name: 'Lumina Stream', icon: 'fas fa-file-pdf', textCol: 'text-purple-600', bgCol: 'bg-purple-600' },
                { id: 'converter', name: 'Image Converter', icon: 'fas fa-sync-alt', textCol: 'text-emerald-500', bgCol: 'bg-emerald-500' },
                { id: 'resizer', name: 'Image Optimizer', icon: 'fas fa-images', textCol: 'text-blue-600', bgCol: 'bg-blue-600' },
                { id: 'compress', name: 'Image Compress', icon: 'fas fa-file-image', textCol: 'text-emerald-600', bgCol: 'bg-emerald-600' }
            ],
            go(tool) {
                const routes = {
                    tiktok: '../tiktok/index.php',
                    lumina: '../lector_doc/index.php',
                    converter: '../image-converter/index.php',
                    resizer: '../image-resizer/index.php',
                    compress: '../image-compress/index.php'
                };

                if(routes[tool]) {
                    window.location.href = routes[tool];
                }
            }
        }
    }
</script>
</body>
</html>