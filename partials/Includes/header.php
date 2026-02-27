<?php
// Configuración por defecto si no se define en la página
$pageTitle = isset($pageTitle) ? $pageTitle : 'Nexosyne Tools';
$themeHex = isset($themeHex) ? $themeHex : '#000000'; // Color por defecto
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> | Nexosyne Tools</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --theme-color: <?php echo $themeHex; ?>;
        }
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; scroll-behavior: smooth; }
        
        .text-theme { color: var(--theme-color); }
        .bg-theme { background-color: var(--theme-color); }
        .border-theme { border-color: var(--theme-color); }
        .hover-text-theme:hover { color: var(--theme-color); }
        .hover-bg-theme:hover { background-color: var(--theme-color); }
        .shadow-theme { box-shadow: 8px 8px 0px var(--theme-color); }
        .shadow-theme-lg { box-shadow: 15px 15px 0px var(--theme-color); }
        
        .glass-nav { 
            backdrop-filter: blur(12px); 
            background: rgba(255, 255, 255, 0.95); 
            border-bottom: 2px solid rgba(0,0,0,0.05); 
        }
        
        .tool-container { 
            border: 3px solid #000; 
            box-shadow: 8px 8px 0px var(--theme-color);
        }

        @media (min-width: 768px) {
            .tool-container { border: 4px solid #000; box-shadow: 15px 15px 0px var(--theme-color); }
        }
        
        .input-style {
            background-color: #f9fafb;
            border: 2px solid #f3f4f6;
            border-radius: 1rem;
            padding: 1rem;
            font-weight: 800;
            width: 100%;
            outline: none;
            transition: all 0.2s;
        }
        .input-style:focus {
            border-color: var(--theme-color);
            background-color: #fff;
        }

        [x-cloak] { display: none !important; }
        .loading-dots:after { content: '.'; animation: dots 1.5s infinite; }
        @keyframes dots { 0%, 20% { content: '.'; } 40% { content: '..'; } 60% { content: '...'; } 80%, 100% { content: ''; } }
    </style>
</head>
<body class="antialiased text-slate-900">

    <nav class="glass-nav sticky top-0 z-50 py-4 px-6 md:px-12 flex justify-between items-center" x-data="{ open: false }">
        <div class="flex items-center gap-3">
            <a href="../../index.php" class="flex items-center gap-3">
                <img src="../../assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" alt="Nexosyne Logo" class="h-10">
                <span class="text-xl font-extrabold tracking-tighter text-black">
                    Nexosyne<span class="text-theme">Tools</span>
                </span>
            </a>
        </div>

        <div class="hidden md:flex gap-8 text-xs font-black uppercase tracking-widest text-gray-400 items-center">
            <div class="relative" @mouseleave="open = false">
                <button @mouseover="open = true" class="flex items-center gap-2 text-black hover-text-theme transition py-2 uppercase">
                    Herramientas <i class="fas fa-chevron-down text-[10px]"></i>
                </button>
                <div x-show="open" x-cloak class="absolute left-0 mt-0 w-64 bg-white border-2 border-gray-100 shadow-2xl rounded-2xl overflow-hidden z-50">
                    <div class="p-2 flex flex-col">
                        <a href="../tiktok/index.php" class="flex items-center gap-3 p-3 hover:bg-purple-50 rounded-xl transition group text-slate-700">
                            <i class="fab fa-tiktok ml-2 text-black group-hover:text-purple-600"></i>
                            <span class="font-bold uppercase text-[10px] ml-1">TikTok Downloader</span>
                        </a>
                        <a href="../image-converter/index.php" class="flex items-center gap-3 p-3 hover:bg-emerald-50 rounded-xl transition group text-slate-700">
                             <i class="fas fa-sync-alt ml-2 text-emerald-500"></i>
                            <span class="font-bold uppercase text-[10px] ml-1">Image Converter</span>
                        </a>
                        <a href="../image-resizer/index.php" class="flex items-center gap-3 p-3 hover:bg-orange-50 rounded-xl transition group text-slate-700">
                             <i class="fas fa-images ml-2 text-[#8B4513]"></i>
                            <span class="font-bold uppercase text-[10px] ml-1">Image Resizer</span>
                        </a>
                    </div>
                </div>
            </div>
            <a href="../../index.php#como-usar" class="hover-text-theme transition">Guía</a>
            <a href="../../index.php#nosotros" class="hover-text-theme transition">Tecnología</a>
        </div>
        
        <div class="md:hidden">
            <button @click="open = !open" class="text-black text-xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>
    
    <div x-show="open" x-cloak class="md:hidden fixed inset-0 z-40 bg-white pt-24 px-6" x-transition>
        <div class="flex flex-col gap-6 text-center">
            <a href="../tiktok/index.php" class="text-xl font-bold text-black">TikTok</a>
            <a href="../image-converter/index.php" class="text-xl font-bold text-emerald-500">Converter</a>
            <a href="../image-resizer/index.php" class="text-xl font-bold text-[#8B4513]">Resizer</a>
            <button @click="open = false" class="mt-8 text-gray-400 uppercase text-xs font-black">Cerrar</button>
        </div>
    </div>