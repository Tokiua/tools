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
    <link rel="icon" type="image/png" href="assets/img/nexosyne.ico">
    
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

        .section-title {
            position: relative;
            display: inline-block;
            padding-bottom: 8px;
            margin-bottom: 32px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 4px;
            background: #7c3aed;
            border-radius: 2px;
        }

        .purple-gradient-text { background: linear-gradient(90deg, #000, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-logo img { height: 120px; width: auto; filter: drop-shadow(0 15px 25px rgba(124, 58, 237, 0.2)); }
        .footer-logo { filter: drop-shadow(2px 0 0 white) drop-shadow(-2px 0 0 white) drop-shadow(0 2px 0 white) drop-shadow(0 -2px 0 white); }
        [x-cloak] { display: none !important; }
        
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

        <div class="hidden md:flex gap-8 text-xs font-black uppercase tracking-widest text-gray-400 items-center">
            <div class="relative" x-data="{ open: false }" @mouseleave="open = false">
                <button @mouseover="open = true" class="flex items-center gap-2 hover:text-purple-600 transition py-2 text-black">
                    Herramientas <i class="fas fa-chevron-down text-[10px]"></i>
                </button>
                <div x-show="open" x-cloak class="absolute left-0 mt-0 w-64 bg-white border-2 border-gray-100 shadow-2xl rounded-2xl overflow-hidden z-50">
                    <div class="p-2 flex flex-col">
                        <template x-for="item in menuItems">
                            <a :href="item.route" class="flex items-center gap-3 p-3 hover:bg-purple-50 rounded-xl transition group cursor-pointer">
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
                <a :href="item.route" class="flex items-center gap-4 py-4 border-b border-gray-50 group cursor-pointer">
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

    <main id="herramientas" class="max-w-7xl mx-auto px-6 pb-24 space-y-20">
        
        <section>
            <h2 class="section-title text-sm font-black uppercase tracking-[0.2em] text-slate-400">Descarga Multimedia</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="tools/tiktok/index.php" class="card-unified group p-8 rounded-[2.5rem] flex flex-col cursor-pointer">
                    <div class="w-14 h-14 bg-black rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 transition-colors text-white shadow-lg">
                        <i class="fab fa-tiktok text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-3 uppercase tracking-tighter">TikTok Master</h3>
                    <p class="text-gray-500 leading-relaxed mb-6 flex-grow font-medium">Baja videos sin marca de agua al instante con la mejor calidad HD.</p>
                    <div class="flex items-center text-purple-600 font-black text-xs gap-2 uppercase">Ejecutar Herramienta <i class="fas fa-arrow-right ml-2"></i></div>
                </a>
                </div>
        </section>

        <section>
            <h2 class="section-title text-sm font-black uppercase tracking-[0.2em] text-slate-400">Gestión de Imágenes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="tools/image-converter/index.php" class="card-unified group p-8 rounded-[2.5rem] flex flex-col cursor-pointer">
                    <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors text-white shadow-lg">
                        <i class="fas fa-sync-alt text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-3 uppercase tracking-tighter">Image Converter</h3>
                    <p class="text-gray-500 leading-relaxed mb-6 flex-grow font-medium">Transforma tus imágenes a WebP, PNG o JPG de forma masiva.</p>
                    <div class="flex items-center text-emerald-600 font-black text-xs gap-2 uppercase">Ejecutar Herramienta <i class="fas fa-arrow-right ml-2"></i></div>
                </a>

                <a href="tools/image-resizer/index.php" class="card-unified group p-8 rounded-[2.5rem] flex flex-col cursor-pointer">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors text-purple-600 shadow-lg">
                        <i class="fas fa-compress-arrows-alt text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-3 uppercase tracking-tighter">Image Optimizer</h3>
                    <p class="text-gray-500 leading-relaxed mb-6 flex-grow font-medium">Optimiza el peso y redimensiona imágenes sin sacrificar calidad.</p>
                    <div class="flex items-center text-purple-600 font-black text-xs gap-2 uppercase">Ejecutar Herramienta <i class="fas fa-arrow-right ml-2"></i></div>
                </a>
            </div>
        </section>

        <section>
            <h2 class="section-title text-sm font-black uppercase tracking-[0.2em] text-slate-400">Documentos HD</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="tools/lector_doc/index.php" class="card-unified group p-8 rounded-[2.5rem] flex flex-col cursor-pointer">
                    <div class="w-14 h-14 bg-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-black transition-colors text-white shadow-lg">
                        <i class="fas fa-file-pdf text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-3 uppercase tracking-tighter">Lumina Stream</h3>
                    <p class="text-gray-500 leading-relaxed mb-6 flex-grow font-medium">Visualizador PDF profesional con renderizado en tiempo real y volátil.</p>
                    <div class="flex items-center text-red-600 font-black text-xs gap-2 uppercase">Abrir Documento <i class="fas fa-bolt ml-2"></i></div>
                </a>
            </div>
        </section>

        <hr class="border-gray-100 my-24">

        <section id="nosotros" class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-purple-600 font-black text-xs uppercase tracking-[0.3em] mb-4 block italic">Core Technology</span>
                <h2 class="text-4xl font-black mb-6 leading-tight uppercase">¿Qué es Nexosyne Tools?</h2>
                <p class="text-gray-500 font-medium leading-relaxed mb-8">
                    Es un ecosistema de herramientas de alto rendimiento diseñadas bajo la filosofía <span class="text-black font-bold">Zero-Storage</span>. A diferencia de otros convertidores, nosotros no guardamos una copia de tus archivos en el servidor para procesarlos; utilizamos memoria RAM volátil para realizar la tarea y destruir el rastro al instante.
                </p>
                <div class="flex flex-wrap gap-3">
                    <span class="tech-badge">Tailwind CSS 3.4</span>
                    <span class="tech-badge">Alpine.js 3.x</span>
                    <span class="tech-badge">PHP 8.2</span>
                    <span class="tech-badge">No-Storage Mode</span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-6 border-2 border-gray-50 rounded-[2rem] bg-gray-50/30">
                    <i class="fas fa-shield-halved text-purple-600 mb-4 text-xl"></i>
                    <h4 class="font-black text-sm uppercase mb-2">Seguridad</h4>
                    <p class="text-gray-500 text-xs font-bold leading-relaxed">Rutas directas y procesos efímeros por sesión.</p>
                </div>
                <div class="p-6 border-2 border-gray-50 rounded-[2rem] bg-gray-50/30">
                    <i class="fas fa-bolt text-amber-500 mb-4 text-xl"></i>
                    <h4 class="font-black text-sm uppercase mb-2">Velocidad</h4>
                    <p class="text-gray-500 text-xs font-bold leading-relaxed">Optimizamos el procesamiento en tiempo real.</p>
                </div>
                <div class="p-6 border-2 border-gray-50 rounded-[2rem] bg-gray-50/30">
                    <i class="fas fa-microchip text-blue-500 mb-4 text-xl"></i>
                    <h4 class="font-black text-sm uppercase mb-2">Motor HD</h4>
                    <p class="text-gray-500 text-xs font-bold leading-relaxed">Lumina Stream utiliza renderizado vectorial.</p>
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
                    <a href="tools/tiktok/index.php" class="font-bold text-sm hover:text-purple-400">TikTok Master</a>
                    <a href="tools/lector_doc/index.php" class="font-bold text-sm hover:text-purple-400">Lumina Stream</a>
                    <a href="tools/image-converter/index.php" class="font-bold text-sm hover:text-purple-400">Image Converter</a>
                    <a href="tools/image-resizer/index.php" class="font-bold text-sm hover:text-purple-400">Image Optimizer</a>
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
                    { name: 'TikTok Master', icon: 'fab fa-tiktok', color: 'bg-black', route: 'tools/tiktok/index.php' },
                    { name: 'Lumina Stream', icon: 'fas fa-file-pdf', color: 'bg-red-600', route: 'tools/lector_doc/index.php' },
                    { name: 'Image Converter', icon: 'fas fa-sync-alt', color: 'bg-emerald-500', route: 'tools/image-converter/index.php' },
                    { name: 'Image Optimizer', icon: 'fas fa-compress-arrows-alt', color: 'bg-purple-600', route: 'tools/image-resizer/index.php' }
                ]
            }
        }
    </script>
</body>
</html>