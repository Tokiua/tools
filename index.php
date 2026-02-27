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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; scroll-behavior: smooth; }
        .glass-nav { backdrop-filter: blur(12px); background: rgba(255, 255, 255, 0.95); border-bottom: 2px solid #7c3aed20; }
        
        .card-shadow { 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            border: 2px solid #f3f4f6;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .card-shadow:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 25px 50px -12px rgba(124, 58, 237, 0.15); 
            border-color: #7c3aed; 
        }
        
        .purple-gradient-text { background: linear-gradient(90deg, #000, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-logo img { height: 140px; width: auto; filter: drop-shadow(0 15px 25px rgba(124, 58, 237, 0.2)); }

        .footer-logo {
            filter: drop-shadow(2px 0 0 white) drop-shadow(-2px 0 0 white) drop-shadow(0 2px 0 white) drop-shadow(0 -2px 0 white);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-slate-900">

    <nav class="glass-nav sticky top-0 z-50 py-4 px-6 md:px-12 flex justify-between items-center" x-data="{ open: false }">
        <div class="flex items-center gap-3">
            <a href="https://www.nexosyne.com" class="flex items-center gap-3">
                <img src="assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" alt="Logo" class="h-10">
                <span class="text-xl font-extrabold tracking-tighter text-black">
                    Nexosyne<span class="text-purple-600">Tools</span>
                </span>
            </a>
        </div>

        <div class="hidden md:flex gap-8 text-xs font-black uppercase tracking-widest text-gray-400 items-center">
            
            <div class="relative" @mouseleave="open = false">
                <button @mouseover="open = true" class="flex items-center gap-2 hover:text-purple-600 transition py-2">
                    Herramientas <i class="fas fa-chevron-down text-[10px]"></i>
                </button>
                
                <div x-show="open" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                     class="absolute left-0 mt-0 w-64 bg-white border-2 border-gray-100 shadow-2xl rounded-2xl overflow-hidden z-50">
                    <div class="p-2 flex flex-col">
                        <a href="tools/tiktok/index.php" class="flex items-center gap-3 p-3 hover:bg-purple-50 rounded-xl transition group">
                            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center text-white text-xs group-hover:bg-purple-600">
                                <i class="fab fa-tiktok"></i>
                            </div>
                            <span class="text-slate-700 font-bold">TikTok Downloader</span>
                        </a>
                        <a href="tools/image-converter/index.php" class="flex items-center gap-3 p-3 hover:bg-emerald-50 rounded-xl transition group">
                            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white text-xs group-hover:bg-emerald-600">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <span class="text-slate-700 font-bold">Image Converter</span>
                        </a>
                        <a href="tools/image-resizer/index.php" class="flex items-center gap-3 p-3 hover:bg-purple-50 rounded-xl transition group">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 text-xs group-hover:bg-purple-600 group-hover:text-white">
                                <i class="fas fa-images"></i>
                            </div>
                            <span class="text-slate-700 font-bold">Image Resizer</span>
                        </a>
                    </div>
                </div>
            </div>

            <a href="#como-usar" class="hover:text-purple-600 transition">¿Cómo usar?</a>
            <a href="#nosotros" class="hover:text-purple-600 transition">Tecnología</a>
        </div>
    </nav>

    <header class="pt-24 pb-16 px-6 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="hero-logo mb-10 flex justify-center">
                <img src="assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" alt="Nexosyne Hero Logo" class="animate-pulse">
            </div>
            <h1 class="text-5xl md:text-7xl font-black leading-[1.1] mb-6">
                Procesamiento <br><span class="purple-gradient-text">Instantáneo y Privado.</span>
            </h1>
            <p class="text-gray-500 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-medium">
                Sin cuentas, sin esperas, sin rastro. La herramienta definitiva para creadores de contenido que valoran su tiempo y seguridad.
            </p>
        </div>
    </header>

    <main id="herramientas" class="max-w-7xl mx-auto px-6 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-24">
            <a href="tools/tiktok/index.php" class="card-shadow group bg-white p-10 rounded-[3rem] flex flex-col">
                <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center mb-8 group-hover:bg-purple-600 transition-colors shadow-lg">
                    <i class="fab fa-tiktok text-2xl text-white"></i>
                </div>
                <h3 class="text-3xl font-black mb-4 tracking-tighter uppercase">TikTok HD</h3>
                <p class="text-gray-500 leading-relaxed mb-8 flex-grow font-medium">
                    Extrae videos sin marca de agua en segundos. Simplemente pega el link y obtén tu archivo listo para compartir.
                </p>
                <div class="flex items-center text-purple-600 font-black text-sm gap-2">
                    LANZAR AHORA <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <a href="tools/image-converter/index.php" class="card-shadow group bg-white p-10 rounded-[3rem] flex flex-col">
                <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-emerald-600 transition-colors shadow-lg">
                    <i class="fas fa-sync-alt text-2xl text-white"></i>
                </div>
                <h3 class="text-3xl font-black mb-4 tracking-tighter uppercase">Image Converter</h3>
                <p class="text-gray-500 leading-relaxed mb-8 flex-grow font-medium">
                    Convierte imágenes a formatos WebP, PNG o JPG de forma instantánea. Optimiza la compatibilidad de tus archivos sin perder calidad.
                </p>
                <div class="flex items-center text-emerald-600 font-black text-sm gap-2">
                    LANZAR AHORA <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <a href="tools/image-resizer/index.php" class="card-shadow group bg-white p-10 rounded-[3rem] flex flex-col md:col-span-2">
                <div class="flex flex-col md:flex-row gap-10 items-center">
                    <div class="w-24 h-24 bg-purple-50 rounded-3xl flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all">
                        <i class="fas fa-expand-arrows-alt text-4xl"></i>
                    </div>
                    <div class="text-center md:text-left">
                        <h3 class="text-3xl font-black mb-3 tracking-tighter uppercase">Optimizador de Imágenes</h3>
                        <p class="text-gray-500 font-medium max-w-2xl leading-relaxed">
                            Reduce el peso, cambia el formato o redimensiona tus fotos para web sin perder nitidez. Soporta JPG, PNG y WEBP de forma masiva.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <section id="como-usar" class="bg-black rounded-[4rem] p-12 md:p-20 text-white mb-24">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black mb-4">Experiencia Fluida</h2>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">Cero Barreras • 100% Gratis</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <div class="space-y-4">
                    <div class="text-5xl font-black text-purple-500">01</div>
                    <h4 class="text-xl font-bold">Sin Registro</h4>
                    <p class="text-gray-400 text-sm leading-relaxed">Olvídate de formularios y correos. Entras, procesas y sales. Tu anonimato es nuestra prioridad.</p>
                </div>
                <div class="space-y-4">
                    <div class="text-5xl font-black text-purple-500">02</div>
                    <h4 class="text-xl font-bold">Pega o Sube</h4>
                    <p class="text-gray-400 text-sm leading-relaxed">Solo necesitas el enlace del video o arrastrar tu imagen al navegador. Nosotros hacemos el trabajo pesado.</p>
                </div>
                <div class="space-y-4">
                    <div class="text-5xl font-black text-purple-500">03</div>
                    <h4 class="text-xl font-bold">Descarga Directa</h4>
                    <p class="text-gray-400 text-sm leading-relaxed">El archivo se descarga directo a tu dispositivo. Sin pasar por nuestras bases de datos.</p>
                </div>
            </div>
        </section>

        <section id="nosotros" class="grid grid-cols-1 lg:grid-cols-3 gap-16 items-center px-4">
            <div class="lg:col-span-2 space-y-8">
                <h2 class="text-4xl font-black tracking-tight text-slate-900 uppercase">Arquitectura Ligera <br>& Sin Trabones</h2>
                <div class="prose prose-slate max-w-none text-gray-600 font-medium">
                    <p>Nexosyne Tools V2 ha sido optimizada para ofrecer una navegación <strong>ultrarrápida</strong>. A diferencia de otros sitios saturados de anuncios y scripts pesados, nuestra suite utiliza procesamiento en memoria volátil.</p>
                </div>
            </div>
            <div class="bg-white border-4 border-black p-8 rounded-[2.5rem] shadow-2xl">
                <h4 class="font-black text-black text-xs uppercase tracking-[0.2em] mb-6">Especificaciones V2</h4>
                <ul class="space-y-5 text-sm font-bold">
                    <li class="flex items-center gap-3 text-emerald-600"><i class="fas fa-check-circle"></i> Navegación Ligera (Cero Lag)</li>
                    <li class="flex items-center gap-3"><i class="fas fa-server"></i> Procesamiento Lado Servidor</li>
                    <li class="flex items-center gap-3"><i class="fas fa-history"></i> Limpieza Automática de Cache</li>
                    <li class="flex items-center gap-3"><i class="fas fa-bolt"></i> Compresión Gzip Activada</li>
                </ul>
            </div>
        </section>
    </main>

    <footer class="bg-black text-white pt-20 pb-10 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-20 mb-16">
            <div>
                <img src="assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" alt="Logo" class="h-12 mb-8 footer-logo">
                <p class="text-gray-400 font-bold text-sm leading-relaxed max-w-sm">
                    La suite multimedia más rápida y privada del mercado. Diseñada para funcionar sin fricciones en cualquier dispositivo.
                </p>
            </div>
            <div class="flex gap-16 md:justify-end">
                <div class="flex flex-col gap-4">
                    <span class="text-purple-600 font-black text-xs uppercase tracking-widest">Navegación</span>
                    <a href="#" class="font-bold text-sm hover:text-purple-400 transition">Inicio</a>
                    <a href="#herramientas" class="font-bold text-sm hover:text-purple-400 transition">Herramientas</a>
                </div>
                <div class="flex flex-col gap-4">
                    <span class="text-purple-600 font-black text-xs uppercase tracking-widest">Legal</span>
                    <a href="#" class="font-bold text-sm hover:text-purple-400 transition">Privacidad</a>
                    <a href="#" class="font-bold text-sm hover:text-purple-400 transition">Términos</a>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-8 border-t border-gray-900 flex flex-col md:flex-row justify-between items-center gap-4 text-gray-500 text-[10px] font-black uppercase tracking-[0.3em]">
            <span>© 2026 Nexosyne Multimedia</span>
            <a href="https://tools.nexosyne.com" class="hover:text-white transition">tools.nexosyne.com</a>
        </div>
    </footer>

</body>
</html>