<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexosyne Tools | Suite Multimedia Profesional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; scroll-behavior: smooth; overflow-x: hidden; }
        .glass-nav { backdrop-filter: blur(12px); background: rgba(255, 255, 255, 0.95); border-bottom: 2px solid #7c3aed20; }
        
        .card-unified { 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            border: 2px solid #f3f4f6; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            background: white;
            height: 100%;
        }
        .card-unified:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 25px 50px -12px rgba(124, 58, 237, 0.15); 
            border-color: #7c3aed; 
        }

        .purple-gradient-text { background: linear-gradient(90deg, #000, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-logo img { height: 120px; width: auto; filter: drop-shadow(0 15px 25px rgba(124, 58, 237, 0.2)); }
        .footer-logo { filter: drop-shadow(2px 0 0 white) drop-shadow(-2px 0 0 white) drop-shadow(0 2px 0 white) drop-shadow(0 -2px 0 white); }
        [x-cloak] { display: none !important; }
        
        /* Estilo para los badges de tecnología */
        .tech-badge { background: #f3f4f6; padding: 4px 12px; border-radius: 99px; font-size: 10px; font-weight: 800; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
    </style>
</head>
<body class="antialiased text-slate-900" x-data="nexosyneCore()">

    <nav class="glass-nav sticky top-0 z-50 py-4 px-6 md:px-12 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="index.php" class="flex items-center gap-3">
                <img src="assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" alt="Logo" class="h-10">
                <span class="text-xl font-extrabold tracking-tighter text-black">
                    Nexosyne<span class="text-purple-600">Tools</span>
                </span>
            </a>
        </div>

        <div class="hidden md:flex gap-8 text-xs font-black uppercase tracking-widest text-gray-400 items-center" x-data="{ open: false }">
            <div class="relative" @mouseleave="open = false">
                <button @mouseover="open = true" class="flex items-center gap-2 hover:text-purple-600 transition py-2 text-black">
                    Herramientas <i class="fas fa-chevron-down text-[10px]"></i>
                </button>
                <div x-show="open" x-cloak class="absolute left-0 mt-0 w-64 bg-white border-2 border-gray-100 shadow-2xl rounded-2xl overflow-hidden z-50">
                    <div class="p-2 flex flex-col">
                        <template x-for="item in menuItems">
                            <a @click="go(item.id)" class="flex items-center gap-3 p-3 hover:bg-purple-50 rounded-xl transition group cursor-pointer">
                                <div :class="`w-8 h-8 rounded-lg flex items-center justify-center text-white text-[10px] ${item.color}`">
                                    <i :class="item.icon"></i>
                                </div>
                                <span class="text-slate-700 font-bold" x-text="item.name"></span>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
            <a href="#nosotros" class="hover:text-purple-600 transition text-black">Tecnología</a>
        </div>

        <button class="md:hidden text-2xl" @click="mobileMenu = !mobileMenu">
            <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
        </button>

        <div x-show="mobileMenu" x-cloak class="absolute top-full left-0 w-full bg-white border-b-2 border-gray-100 md:hidden flex flex-col p-6 shadow-xl z-[60]" x-transition>
            <template x-for="item in menuItems">
                <a @click="go(item.id)" class="flex items-center gap-4 py-4 border-b border-gray-50 group cursor-pointer">
                    <div :class="`w-10 h-10 rounded-xl flex items-center justify-center text-white ${item.color}`">
                        <i :class="item.icon"></i>
                    </div>
                    <span class="font-bold text-slate-700 uppercase text-xs" x-text="item.name"></span>
                </a>
            </template>
        </div>
    </nav>

    <header class="pt-20 pb-12 px-6 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="hero-logo mb-8 flex justify-center">
                <img src="assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" alt="Logo" class="animate-pulse">
            </div>
            <h1 class="text-4xl md:text-7xl font-black leading-tight mb-6">
                Procesamiento <br><span class="purple-gradient-text">Sin Almacenamiento.</span>
            </h1>
            <p class="text-gray-500 text-lg max-w-xl mx-auto font-medium">Privacidad radical: tus archivos nunca tocan nuestro disco duro. Todo se procesa en memoria volátil y se destruye al instante.</p>
        </div>
    </header>

    <main id="herramientas" class="max-w-7xl mx-auto px-6 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-24">
            <div @click="go('tiktok')" class="card-unified group p-8 rounded-[2.5rem] flex flex-col cursor-pointer">
                <div class="w-14 h-14 bg-black rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 transition-colors text-white shadow-lg">
                    <i class="fab fa-tiktok text-xl"></i>
                </div>
                <h3 class="text-2xl font-black mb-3 uppercase tracking-tighter">TikTok HD</h3>
                <p class="text-gray-500 leading-relaxed mb-6 flex-grow font-medium">Baja videos sin marca de agua al instante.</p>
                <div class="flex items-center text-purple-600 font-black text-xs gap-2 uppercase">Ejecutar Herramienta <i class="fas fa-arrow-right ml-2"></i></div>
            </div>

            <div @click="go('alpha')" class="card-unified group p-8 rounded-[2.5rem] flex flex-col cursor-pointer">
                <div class="w-14 h-14 bg-[#3d2b1f] rounded-2xl flex items-center justify-center mb-6 group-hover:bg-black transition-colors text-[#d4a373] shadow-lg">
                    <i class="fas fa-wand-magic-sparkles text-xl"></i>
                </div>
                <h3 class="text-2xl font-black mb-3 uppercase tracking-tighter">Alpha Studio</h3>
                <p class="text-gray-500 leading-relaxed mb-6 flex-grow font-medium">Remueve fondos con inteligencia artificial profesional.</p>
                <div class="flex items-center text-[#3d2b1f] font-black text-xs gap-2 uppercase">Ejecutar Herramienta <i class="fas fa-bolt ml-2"></i></div>
            </div>

            <div @click="go('converter')" class="card-unified group p-8 rounded-[2.5rem] flex flex-col cursor-pointer">
                <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors text-white shadow-lg">
                    <i class="fas fa-sync-alt text-xl"></i>
                </div>
                <h3 class="text-2xl font-black mb-3 uppercase tracking-tighter">Image Converter</h3>
                <p class="text-gray-500 leading-relaxed mb-6 flex-grow font-medium">Convierte imágenes a WebP, PNG o JPG rápido.</p>
                <div class="flex items-center text-emerald-600 font-black text-xs gap-2 uppercase">Ejecutar Herramienta <i class="fas fa-arrow-right ml-2"></i></div>
            </div>

            <div @click="go('resizer')" class="card-unified group p-8 rounded-[2.5rem] flex flex-col cursor-pointer">
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors text-purple-600 shadow-lg">
                    <i class="fas fa-expand-arrows-alt text-xl"></i>
                </div>
                <h3 class="text-2xl font-black mb-3 uppercase tracking-tighter">Optimizador</h3>
                <p class="text-gray-500 leading-relaxed mb-6 flex-grow font-medium">Redimensiona y comprime imágenes sin perder calidad.</p>
                <div class="flex items-center text-purple-600 font-black text-xs gap-2 uppercase">Ejecutar Herramienta <i class="fas fa-arrow-right ml-2"></i></div>
            </div>
        </div>

        <hr class="border-gray-100 mb-24">

        <section id="nosotros" class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-purple-600 font-black text-xs uppercase tracking-[0.3em] mb-4 block italic">Core Technology</span>
                <h2 class="text-4xl font-black mb-6 leading-tight uppercase">¿Qué es Nexosyne Tools?</h2>
                <p class="text-gray-500 font-medium leading-relaxed mb-8">
                    Es un ecosistema de herramientas de alto rendimiento diseñadas bajo la filosofía <span class="text-black font-bold">Zero-Storage</span>. A diferencia de otros convertidores, nosotros no guardamos una copia de tus archivos en el servidor para procesarlos; utilizamos el hardware de última generación y memoria RAM volátil para realizar la tarea y entregar el resultado en milisegundos.
                </p>
                <div class="flex flex-wrap gap-3">
                    <span class="tech-badge">Tailwind CSS 3.4</span>
                    <span class="tech-badge">Alpine.js 3.x</span>
                    <span class="tech-badge">PHP 8.2</span>
                    <span class="tech-badge">IA de Visión</span>
                    <span class="tech-badge">WebAssembly</span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-6 border-2 border-gray-50 rounded-[2rem] bg-gray-50/30">
                    <i class="fas fa-shield-halved text-purple-600 mb-4 text-xl"></i>
                    <h4 class="font-black text-sm uppercase mb-2">Seguridad</h4>
                    <p class="text-gray-500 text-xs font-bold leading-relaxed">Cifrado de rutas y tokens volátiles por sesión.</p>
                </div>
                <div class="p-6 border-2 border-gray-50 rounded-[2rem] bg-gray-50/30">
                    <i class="fas fa-bolt text-amber-500 mb-4 text-xl"></i>
                    <h4 class="font-black text-sm uppercase mb-2">Velocidad</h4>
                    <p class="text-gray-500 text-xs font-bold leading-relaxed">Optimizamos el procesamiento en el lado del cliente.</p>
                </div>
                <div class="p-6 border-2 border-gray-50 rounded-[2rem] bg-gray-50/30">
                    <i class="fas fa-microchip text-blue-500 mb-4 text-xl"></i>
                    <h4 class="font-black text-sm uppercase mb-2">IA Pura</h4>
                    <p class="text-gray-500 text-xs font-bold leading-relaxed">Modelos de redes neuronales para Alpha Studio.</p>
                </div>
                <div class="p-6 border-2 border-gray-50 rounded-[2rem] bg-gray-50/30">
                    <i class="fas fa-cloud-slash text-emerald-500 mb-4 text-xl"></i>
                    <h4 class="font-black text-sm uppercase mb-2">No Cloud</h4>
                    <p class="text-gray-500 text-xs font-bold leading-relaxed">Tus datos no se suben a nubes de terceros.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-black text-white pt-20 pb-10 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-20 mb-16">
            <div>
                <img src="assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" alt="Logo" class="h-12 mb-8 footer-logo">
                <p class="text-gray-400 font-bold text-sm leading-relaxed max-w-sm">
                    La suite multimedia profesional. Privacidad radical garantizada: Todo se toma, se trabaja y se entrega sin almacenar nada.
                </p>
            </div>
            <div class="flex gap-16 md:justify-end">
                <div class="flex flex-col gap-4">
                    <span class="text-purple-600 font-black text-xs uppercase tracking-widest italic">Herramientas</span>
                    <a @click="go('tiktok')" class="font-bold text-sm hover:text-purple-400 cursor-pointer">TikTok HD</a>
                    <a @click="go('alpha')" class="font-bold text-sm hover:text-purple-400 cursor-pointer">Alpha Studio</a>
                    <a @click="go('converter')" class="font-bold text-sm hover:text-purple-400 cursor-pointer">Image Converter</a>
                    <a @click="go('resizer')" class="font-bold text-sm hover:text-purple-400 cursor-pointer">Image Optimizer</a>
                </div>
                <div class="flex flex-col gap-4">
                    <span class="text-purple-600 font-black text-xs uppercase tracking-widest italic">Legal</span>
                    <a href="#" class="font-bold text-sm hover:text-purple-400">Privacidad</a>
                    <a href="#" class="font-bold text-sm hover:text-purple-400">Términos</a>
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
                    { id: 'tiktok', name: 'TikTok Downloader', icon: 'fab fa-tiktok', color: 'bg-black' },
                    { id: 'alpha', name: 'Alpha Studio', icon: 'fas fa-wand-magic-sparkles', color: 'bg-[#3d2b1f]' },
                    { id: 'converter', name: 'Image Converter', icon: 'fas fa-sync-alt', color: 'bg-emerald-500' },
                    { id: 'resizer', name: 'Image Resizer', icon: 'fas fa-images', color: 'bg-purple-600' }
                ],
                go(tool) {
                    const routes = {
                        tiktok: 'dG9vbHMvdGlrdG9rL2luZGV4LnBocA==', 
                        alpha: 'dG9vbHMvYmctcmVtb3Zlci9pbmRleC5waHA=', 
                        converter: 'dG9vbHMvaW1hZ2UtY29udmVydGVyL2luZGV4LnBocA==', 
                        resizer: 'dG9vbHMvaW1hZ2UtcmVzaXplci9pbmRleC5waHA=' 
                    };
                    
                    const noise = () => {
                        const c = "ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789!@#$%^&*";
                        let r = ""; for (let i = 0; i < 45; i++) r += c.charAt(Math.floor(Math.random() * c.length));
                        return r;
                    };

                    if(routes[tool]) {
                        window.location.href = `${atob(routes[tool])}?secure_hash=${noise()}&nx_timestamp=${Date.now()}`;
                    }
                }
            }
        }
    </script>
</body>
</html>