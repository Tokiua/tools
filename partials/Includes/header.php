<?php
$pageTitle = isset($pageTitle) ? $pageTitle : 'Nexosyne Tools';
$themeHex = isset($themeHex) ? $themeHex : '#7c3aed'; 
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
        :root { --theme-color: <?php echo $themeHex; ?>; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; overflow-x: hidden; }
        .text-theme { color: var(--theme-color); }
        .hover-text-theme:hover { color: var(--theme-color); }
        .glass-nav { backdrop-filter: blur(12px); background: rgba(255, 255, 255, 0.95); border-bottom: 2px solid rgba(0,0,0,0.05); }
        .card-unified { 
            transition: all 0.3s ease; 
            border: 2px solid #f3f4f6; 
            background: white; 
            border-radius: 2.5rem;
        }
        .card-unified:hover { border-color: var(--theme-color); transform: translateY(-2px); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-slate-900" x-data="nexosyneCore()">

    <nav class="glass-nav sticky top-0 z-[100] py-4 px-6 md:px-12 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="../../index.php" class="flex items-center gap-3">
                <img src="../../assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" alt="Logo" class="h-10">
                <span class="text-xl font-extrabold tracking-tighter text-black">
                    Nexosyne<span class="text-theme">Tools</span>
                </span>
            </a>
        </div>

        <div class="hidden md:flex gap-8 text-xs font-black uppercase tracking-widest text-gray-400 items-center">
            <div class="relative" x-data="{ dropdown: false }" @mouseleave="dropdown = false">
                <button @mouseover="dropdown = true" class="flex items-center gap-2 text-black hover-text-theme transition py-2">
                    Herramientas <i class="fas fa-chevron-down text-[10px]"></i>
                </button>
                <div x-show="dropdown" x-cloak class="absolute left-0 mt-0 w-64 bg-white border-2 border-gray-100 shadow-2xl rounded-2xl overflow-hidden">
                    <div class="p-2 flex flex-col">
                        <template x-for="item in menuItems">
                            <a @click="go(item.id)" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-xl transition group cursor-pointer">
                                <i :class="item.icon + ' ml-2 ' + item.textCol"></i>
                                <span class="font-bold text-slate-700 text-[10px]" x-text="item.name"></span>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
            <a href="../../index.php#nosotros" class="hover-text-theme transition text-black">Tecnología</a>
        </div>
        
        <div class="md:hidden">
            <button @click="mobileMenu = true" class="text-black text-2xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>
    
    <div x-show="mobileMenu" x-cloak 
         class="fixed inset-0 z-[200] bg-white flex flex-col p-8"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0">
        
        <div class="flex justify-between items-center mb-10">
            <span class="font-black text-xl tracking-tighter italic">NEXOSYNE <span class="text-theme">MENU</span></span>
            <button @click="mobileMenu = false" class="text-3xl">&times;</button>
        </div>

        <div class="flex flex-col gap-3">
            <template x-for="item in menuItems">
                <a @click="go(item.id)" class="flex items-center gap-4 p-5 rounded-2xl border-2 border-gray-50 bg-gray-50/50 font-extrabold uppercase text-xs shadow-sm">
                    <div :class="'w-10 h-10 rounded-lg flex items-center justify-center text-white ' + item.bgCol">
                        <i :class="item.icon"></i>
                    </div>
                    <span x-text="item.name"></span>
                </a>
            </template>
        </div>
    </div>