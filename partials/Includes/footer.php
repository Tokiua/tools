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
                    { id: 'alpha', name: 'Alpha Studio IA', icon: 'fas fa-wand-magic-sparkles', textCol: 'text-purple-600', bgCol: 'bg-purple-600' },
                    { id: 'converter', name: 'Image Converter', icon: 'fas fa-sync-alt', textCol: 'text-emerald-500', bgCol: 'bg-emerald-500' },
                    { id: 'resizer', name: 'Image Optimizer', icon: 'fas fa-images', textCol: 'text-blue-600', bgCol: 'bg-blue-600' }
                ],
                go(tool) {
                    // RUTAS CORREGIDAS: Desde una herramienta a otra dentro de la carpeta tools/
                    const routes = {
                        tiktok: 'Li4vdGlrdG9rL2luZGV4LnBocA==',           // ../tiktok/index.php
                        alpha: 'Li4vYmctcmVtb3Zlci9pbmRleC5waHA=',         // ../bg-remover/index.php
                        converter: 'Li4vaW1hZ2UtY29udmVydGVyL2luZGV4LnBocA==', // ../image-converter/index.php
                        resizer: 'Li4vaW1hZ2UtcmVzaXplci9pbmRleC5waHA='      // ../image-resizer/index.php
                    };
                    
                    const noise = () => {
                        const c = "ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789!@#$%^&*";
                        let r = ""; for (let i = 0; i < 45; i++) r += c.charAt(Math.floor(Math.random() * c.length));
                        return r;
                    };

                    if(routes[tool]) {
                        // Decodifica y redirige usando la ruta relativa limpia
                        window.location.href = `${atob(routes[tool])}?hash=${noise()}&nx_secure=${Date.now()}`;
                    }
                }
            }
        }
    </script>
</body>
</html>