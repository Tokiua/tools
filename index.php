<?php
$pageTitle = 'Suite Multimedia Profesional';
$themeHex = '#7c3aed'; 
$basePath = './'; // Ruta base para el index principal
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexosyne Tools | <?php echo $pageTitle; ?></title>

    <meta name="theme-color" content="<?php echo $themeHex; ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="assets/img/nexosyne.ico">
    
    <style>
        :root { --theme-color: <?php echo $themeHex; ?>; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; scroll-behavior: smooth; overflow-x: hidden; }
        
        /* NAVEGACIÓN */
        .glass-nav { backdrop-filter: blur(12px); background: rgba(255, 255, 255, .95); border-bottom: 2px solid rgba(0, 0, 0, .05); }
        .text-theme { color: var(--theme-color); }
        .hover-text-theme:hover { color: var(--theme-color); }
        .bg-theme { background-color: var(--theme-color); }

        /* CARDS UNIFICADAS */
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
            border-color: var(--theme-color); 
        }

        .section-title { position: relative; display: inline-block; padding-bottom: 8px; margin-bottom: 32px; }
        .section-title::after {
            content: ''; position: absolute; left: 0; bottom: 0; width: 40px; height: 4px;
            background: var(--theme-color); border-radius: 2px;
        }

        .purple-gradient-text { background: linear-gradient(90deg, #000, var(--theme-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-logo img { height: 120px; width: auto; filter: drop-shadow(0 15px 25px rgba(124, 58, 237, 0.2)); }
        [x-cloak] { display: none !important; }
        .tech-badge { background: #f3f4f6; padding: 4px 12px; border-radius: 99px; font-size: 10px; font-weight: 800; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
    </style>
</head>
<body class="antialiased text-slate-900" x-data="{ mobileMenu: false, ...nexosyneCore('<?php echo $basePath; ?>') }">

    <nav class="glass-nav sticky top-0 z-[100] py-4 px-6 md:px-12 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="index.php" class="flex items-center gap-3">
                <img src="assets/img/logo.png" alt="Logo" class="h-10">
                <span class="text-xl font-extrabold tracking-tighter text-black">
                    Nexosyne<span class="text-theme">Tools</span>
                </span>
            </a>
        </div>

        <div class="hidden md:flex gap-8 text-xs font-black uppercase tracking-widest text-gray-500 items-center">
            <a href="index.php" class="hover-text-theme transition text-black">INICIO</a>

            <div class="relative" x-data="{ dropdown: false }" @mouseleave="dropdown = false">
                <button @mouseover="dropdown = true" class="flex items-center gap-2 text-black hover-text-theme transition py-2">
                    HERRAMIENTAS <i class="fas fa-chevron-down text-[10px]"></i>
                </button>
                <div x-show="dropdown" x-cloak 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute left-0 mt-0 w-64 bg-white border-2 border-gray-100 shadow-2xl rounded-2xl overflow-hidden">
                    <div class="p-2 flex flex-col">
                        <template x-for="item in menuItems" :key="item.id">
                            <a @click="go(item.id)" class="flex items-center gap-3 p-3 hover:bg-purple-50 rounded-xl transition group cursor-pointer">
                                <div :class="`w-8 h-8 rounded-lg flex items-center justify-center text-white text-[10px] ${item.bgCol}`">
                                    <i :class="item.icon"></i>
                                </div>
                                <span class="font-bold text-slate-700 text-[11px]" x-text="item.name"></span>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <a href="#nosotros" class="hover-text-theme transition text-black">Tecnología</a>
        </div>

        <div class="md:hidden flex items-center">
            <button @click="mobileMenu = true" class="text-black text-2xl w-10 h-10 flex items-center justify-center">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <div x-show="mobileMenu" x-cloak class="fixed inset-0 z-[200] bg-white flex flex-col p-8"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0">
        
        <div class="flex justify-between items-center mb-10">
            <span class="font-black text-xl tracking-tighter">NEXOSYNE <span class="text-theme">MENU</span></span>
            <button @click="mobileMenu = false" class="text-3xl">&times;</button>
        </div>

        <div class="flex flex-col gap-3 overflow-y-auto">
            <template x-for="item in menuItems">
                <a @click="go(item.id)" class="flex items-center gap-4 p-5 rounded-2xl border-2 border-gray-50 bg-gray-50/50 font-extrabold uppercase text-xs shadow-sm cursor-pointer">
                    <div :class="`w-10 h-10 rounded-lg flex items-center justify-center text-white ${item.bgCol}`">
                        <i :class="item.icon"></i>
                    </div>
                    <span x-text="item.name"></span>
                </a>
            </template>
            <a href="#nosotros" @click="mobileMenu = false" class="mt-4 p-5 font-black text-xs uppercase tracking-widest text-slate-800 bg-gray-50 rounded-2xl flex items-center">
                <i class="fas fa-microchip mr-3 text-theme"></i> Tecnología
            </a>
        </div>
    </div>

    <header class="pt-20 pb-12 px-6 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="hero-logo mb-8 flex justify-center">
                <img src="assets/img/logo.png" alt="Logo" class="animate-pulse">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="tools/tiktok/index.php" class="card-unified group p-8 rounded-[2.5rem] flex flex-col">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="tools/image-converter/index.php" class="card-unified group p-8 rounded-[2.5rem] flex flex-col">
                    <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors text-white shadow-lg">
                        <i class="fas fa-sync-alt text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-3 uppercase tracking-tighter">Image Converter</h3>
                    <p class="text-gray-500 leading-relaxed mb-6 flex-grow font-medium">Transforma tus imágenes a WebP, PNG o JPG de forma masiva.</p>
                    <div class="flex items-center text-emerald-600 font-black text-xs gap-2 uppercase">Ejecutar Herramienta <i class="fas fa-arrow-right ml-2"></i></div>
                </a>

                <a href="tools/image-resizer/index.php" class="card-unified group p-8 rounded-[2.5rem] flex flex-col">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors text-purple-600 shadow-lg">
                        <i class="fas fa-compress-arrows-alt text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-3 uppercase tracking-tighter">Image Optimizer</h3>
                    <p class="text-gray-500 leading-relaxed mb-6 flex-grow font-medium">Optimiza el peso y redimensiona imágenes sin sacrificar calidad.</p>
                    <div class="flex items-center text-purple-600 font-black text-xs gap-2 uppercase">Ejecutar Herramienta <i class="fas fa-arrow-right ml-2"></i></div>
                </a>

                <a href="tools/image-compress/index.php" class="card-unified group p-8 rounded-[2.5rem] flex flex-col border-emerald-100 bg-emerald-50/10">
                    <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-black transition-colors text-white shadow-lg">
                        <i class="fas fa-file-image text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-3 uppercase tracking-tighter">Image Compress</h3>
                    <p class="text-gray-500 leading-relaxed mb-6 flex-grow font-medium">Compresión avanzada local. Reduce el peso al máximo para web.</p>
                    <div class="flex items-center text-emerald-600 font-black text-xs gap-2 uppercase">Comprimir Ahora <i class="fas fa-bolt ml-2"></i></div>
                </a>
            </div>
        </section>

        <section>
            <h2 class="section-title text-sm font-black uppercase tracking-[0.2em] text-slate-400">Documentos HD</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="tools/lector_doc/index.php" class="card-unified group p-8 rounded-[2.5rem] flex flex-col">
                    <div class="w-14 h-14 bg-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-black transition-colors text-white shadow-lg">
                        <i class="fas fa-file-pdf text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-3 uppercase tracking-tighter">Lumina Stream</h3>
                    <p class="text-gray-500 leading-relaxed mb-6 flex-grow font-medium">Visualizador PDF profesional con renderizado en tiempo real y volátil.</p>
                    <div class="flex items-center text-red-600 font-black text-xs gap-2 uppercase">Abrir Documento <i class="fas fa-bolt ml-2"></i></div>
                </a>
            </div>
        </section>

        <section id="nosotros" class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center border-t border-gray-100 pt-20">
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
            </div>
        </section>
    </main>

    <footer class="bg-black text-white pt-20 pb-10 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-20 mb-16">
            <div>
                <img src="assets/img/logo.png" alt="Logo" class="h-12 mb-8 filter brightness-0 invert">
                <p class="text-gray-400 font-bold text-sm leading-relaxed max-w-sm">
                    La suite multimedia profesional. Privacidad radical garantizada: Todo se toma, se trabaja y se entrega sin almacenar nada.
                </p>
            </div>
            <div class="flex gap-16 md:justify-end">
                <div class="flex flex-col gap-4">
                    <span class="text-purple-600 font-black text-xs uppercase tracking-widest italic">Herramientas</span>
                    <a href="tools/tiktok/index.php" class="font-bold text-sm hover:text-purple-400 transition">TikTok Master</a>
                    <a href="tools/lector_doc/index.php" class="font-bold text-sm hover:text-purple-400 transition">Lumina Stream</a>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-8 border-t border-gray-900 flex flex-row justify-between items-center text-gray-500 text-[10px] font-black uppercase tracking-[0.3em]">
            <span>© 2026 Nexosyne Multimedia</span>
            <a href="#" class="hover:text-white transition italic">tools.nexosyne.com</a>
        </div>
    </footer>

    <script>
        function nexosyneCore(base) {
            return {
                menuItems: [
                    { id: 'tiktok', name: 'TikTok Master', icon: 'fab fa-tiktok', bgCol: 'bg-black' },
                    { id: 'lector_doc', name: 'Lumina Stream', icon: 'fas fa-file-pdf', bgCol: 'bg-red-600' },
                    { id: 'image-converter', name: 'Image Converter', icon: 'fas fa-sync-alt', bgCol: 'bg-emerald-500' },
                    { id: 'image-resizer', name: 'Image Optimizer', icon: 'fas fa-compress-arrows-alt', bgCol: 'bg-purple-600' },
                    { id: 'image-compress', name: 'Image Compress', icon: 'fas fa-file-image', bgCol: 'bg-emerald-600' }
                ],
                go(id) {
                    window.location.href = base + `tools/${id}/index.php`;
                }
            }
        }
    </script>
</body>
</html>