<?php
$pageTitle = isset($pageTitle) ? $pageTitle : 'Nexosyne Tools';
$themeHex = isset($themeHex) ? $themeHex : '#7c3aed'; 

// DETECCIÓN AUTOMÁTICA DE RUTAS (Funciona en raíz y subcarpetas)
$currentDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$isSubtool = (strpos($currentDir, '/tools/') !== false);
$basePath = $isSubtool ? '../../' : './';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> | Nexosyne Tools</title>

    <meta name="theme-color" content="<?php echo $themeHex; ?>">
    <link rel="manifest" href="<?php echo $basePath; ?>manifest.php?color=<?php echo urlencode($themeHex); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="icon" href="<?php echo $basePath; ?>assets/img/nexosyne.ico" type="image/x-icon">

    <style>
        :root { --theme-color: <?php echo $themeHex; ?>; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fcfcfc;
            overflow-x: hidden;
        }

        .text-theme { color: var(--theme-color); }
        .hover-text-theme:hover { color: var(--theme-color); }
        .bg-theme { background-color: var(--theme-color); }

        .glass-nav {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, .95);
            border-bottom: 2px solid rgba(0, 0, 0, .05);
        }

        [x-cloak] { display: none !important; }

        /* SPLASH CON LOGOS TRANSPARENTES */
        #splash {
            position: fixed; inset: 0; background: <?php echo $themeHex; ?>;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            z-index: 9999; transition: opacity .6s ease;
        }

        .logo-animate {
            width: 200px; height: 200px; object-fit: contain;
            background: transparent !important; /* Transparencia total */
            border: none;
            animation: floatLogo 3s ease-in-out infinite;
        }

        /* Logos en Nav y Menú */
        .nav-logo {
            background: transparent !important;
            mix-blend-mode: normal;
        }

        @keyframes floatLogo {
            0% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0); }
        }

        .loader-ring {
            margin-top: 30px; width: 45px; height: 45px;
            border: 4px solid rgba(255, 255, 255, .3); border-top: 4px solid white;
            border-radius: 50%; animation: spin 1s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
        .mobile-menu-scroll { max-height: calc(100vh - 100px); overflow-y: auto; }
    </style>
</head>

<body class="antialiased text-slate-900" x-data="{ mobileMenu: false, ...nexosyneCore('<?php echo $basePath; ?>') }">

<div id="splash">
    <img src="<?php echo $basePath; ?>assets/img/carga.png" class="logo-animate" alt="Cargando">
    <h1 style="color:white;margin-top:25px;font-size:26px;font-weight:800;">Nexosyne Tools</h1>
    <p style="color:rgba(255,255,255,.85);margin-top:10px;font-size:15px;">Cargando herramienta...</p>
    <div class="loader-ring"></div>
</div>

<nav class="glass-nav sticky top-0 z-[100] py-4 px-6 md:px-12 flex justify-between items-center">
    <div class="flex items-center gap-3">
        <a href="<?php echo $basePath; ?>index.php" class="flex items-center gap-3">
            <img src="<?php echo $basePath; ?>assets/img/logo.png" alt="Logo" class="h-10 nav-logo">
            <span class="text-xl font-extrabold tracking-tighter text-black">
                Nexosyne<span class="text-theme">Tools</span>
            </span>
        </a>
    </div>

    <div class="hidden md:flex gap-8 text-xs font-black uppercase tracking-widest text-gray-500 items-center">
        <a href="<?php echo $basePath; ?>index.php" class="hover-text-theme transition text-black uppercase">INICIO</a>

        <div class="relative" x-data="{ dropdown: false }" @mouseleave="dropdown = false">
            <button @mouseover="dropdown = true" class="flex items-center gap-2 text-black hover-text-theme transition py-2">
                HERRAMIENTAS <i class="fas fa-chevron-down text-[10px]"></i>
            </button>
            <div x-show="dropdown" x-cloak class="absolute left-0 mt-0 w-64 bg-white border-2 border-gray-100 shadow-2xl rounded-2xl overflow-hidden">
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

        <a href="<?php echo $basePath; ?>index.php#nosotros" class="hover-text-theme transition text-black">Tecnología</a>
    </div>

    <div class="md:hidden flex items-center">
        <button @click="mobileMenu = true" class="text-black text-2xl w-10 h-10 flex items-center justify-center focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<div x-show="mobileMenu" x-cloak class="fixed inset-0 z-[110] md:hidden">
    <div x-show="mobileMenu" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="mobileMenu = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div x-show="mobileMenu" x-transition:enter="transition transform duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" class="fixed inset-y-0 right-0 z-[111] w-full max-w-xs bg-white shadow-xl flex flex-col">
        <div class="flex items-center justify-between px-6 py-6 border-b border-gray-100">
            <span class="font-black text-lg tracking-tighter uppercase">Menú</span>
            <button @click="mobileMenu = false" class="text-gray-500 text-2xl w-10 h-10 flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="flex-1 mobile-menu-scroll px-4 py-4">
            <div class="flex flex-col gap-2">
                <a href="<?php echo $basePath; ?>index.php" class="p-4 font-black text-xs uppercase tracking-widest text-slate-800 bg-gray-50 rounded-2xl flex items-center">
                    <i class="fas fa-home mr-3 text-theme"></i> INICIO
                </a>
                
                <div class="mt-6 px-4 font-black text-[10px] text-gray-400 uppercase tracking-[0.2em] mb-2 text-center">Nuestras Herramientas</div>
                
                <template x-for="item in menuItems" :key="item.id">
                    <a @click="go(item.id); mobileMenu = false" class="flex items-center gap-4 p-4 hover:bg-gray-50 rounded-2xl transition group cursor-pointer border border-transparent hover:border-gray-100">
                        <div :class="`w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-sm ${item.bgCol}`">
                            <i :class="item.icon + ' text-lg'"></i>
                        </div>
                        <span class="font-bold text-slate-700 uppercase text-xs" x-text="item.name"></span>
                    </a>
                </template>

                <a href="<?php echo $basePath; ?>index.php#nosotros" @click="mobileMenu = false" class="mt-4 p-4 font-black text-xs uppercase tracking-widest text-slate-800 bg-gray-50 rounded-2xl flex items-center">
                    <i class="fas fa-microchip mr-3 text-theme"></i> Tecnología
                </a>
            </div>
        </div>
        <div class="p-6 bg-gray-50 text-center">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Nexosyne Tools v3.0</p>
        </div>
    </div>
</div>

<script>
window.addEventListener("load",function(){
    setTimeout(()=>{
        const splash = document.getElementById("splash");
        if(splash){
            splash.style.opacity="0";
            setTimeout(()=>{ splash.remove(); },600);
        }
    },1200);
});

if (typeof nexosyneCore !== 'function') {
    window.nexosyneCore = function(base) {
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
}
</script>