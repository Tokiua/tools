<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina Stream - PDF Master | Nexosyne Tools</title>
    <meta name="theme-color" content="#ef4444">
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="../../assets/img/nexosyne.ico" type="image/x-icon">
    <style>
        :root { --theme: #ef4444; }
        body{ font-family:'Plus Jakarta Sans',sans-serif; background:#fcfcfc; overflow-x:hidden; }
        .text-theme{ color:var(--theme); }
        .bg-theme{ background-color: var(--theme); }
        .glass-nav{ backdrop-filter:blur(12px); background:rgba(255,255,255,.95); border-bottom:2px solid rgba(0,0,0,.05); }
        [x-cloak]{ display:none !important; }
        .card-unified { background: #ffffff; border-radius: 2.5rem; border: 2px solid #f3f4f6; overflow: hidden; }
        .viewer-wrapper {
            position: relative; width: 100%; height: 75vh;
            background: #1a1a1a; border-radius: 2rem;
            overflow: hidden; border: 3px solid #000;
            transition: all 0.3s ease;
        }
        .viewer-wrapper.is-fullscreen {
            position: fixed !important; top: 0; left: 0;
            width: 100vw !important; height: 100vh !important;
            z-index: 9999; border-radius: 0; border: none;
        }
        iframe { width: 100%; height: 100%; border: none; display: block; }
        .btn-exit-fs {
            position: absolute; top: 90px; right: 25px;
            z-index: 10000; background: var(--theme);
            color: white; width: 45px; height: 45px;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            border: 3px solid #000; box-shadow: 0 10px 20px rgba(0,0,0,0.4);
            cursor: pointer;
        }
        /* SPLASH */
        #splash{ position:fixed; inset:0; background:#ef4444; display:flex; flex-direction:column; align-items:center; justify-content:center; z-index:9999; transition:opacity .6s ease; }
        .logo-animate{ width:200px; height:200px; object-fit:contain; animation:floatLogo 3s ease-in-out infinite; }
        @keyframes floatLogo{ 0%, 100%{transform:translateY(0);} 50%{transform:translateY(-12px);} }
        .loader-ring{ margin-top:30px; width:45px; height:45px; border:4px solid rgba(255,255,255,.3); border-top:4px solid white; border-radius:50%; animation:spin 1s linear infinite; }
        @keyframes spin{ to{transform:rotate(360deg);} }
    </style>
</head>

<body class="antialiased text-slate-900" x-data="luminaCore()">

<div id="splash">
    <img src="../../assets/img/carga.png" class="logo-animate">
    <h1 style="color:white;margin-top:25px;font-size:26px;font-weight:800;">Nexosyne Tools</h1>
    <p style="color:rgba(255,255,255,.85);margin-top:10px;font-size:15px;">Cargando herramienta...</p>
    <div class="loader-ring"></div>
</div>

<nav class="glass-nav sticky top-0 z-[100] py-4 px-6 md:px-12 flex justify-between items-center">
    <div class="flex items-center gap-3">
        <a href="../../index.php" class="flex items-center gap-3">
            <img src="../../assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" alt="Logo" class="h-10">
            <span class="text-xl font-extrabold tracking-tighter text-black">Nexosyne<span class="text-theme">Tools</span></span>
        </a>
    </div>

    <div class="hidden md:flex gap-8 text-xs font-black uppercase tracking-widest text-gray-500 items-center">
        <a href="../../index.php" class="hover:text-theme transition text-black uppercase">INICIO</a>
        <div class="relative" x-data="{ dropdown: false }" @mouseleave="dropdown = false">
            <button @mouseover="dropdown = true" class="flex items-center gap-2 text-black hover-text-theme transition py-2 uppercase">HERRAMIENTAS <i class="fas fa-chevron-down text-[10px]"></i></button>
            <div x-show="dropdown" x-cloak class="absolute left-0 mt-0 w-64 bg-white border-2 border-gray-100 shadow-2xl rounded-2xl overflow-hidden">
                <div class="p-2 flex flex-col">
                    <template x-for="item in menuItems" :key="item.id">
                        <a @click="go(item.id)" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-xl transition group cursor-pointer">
                            <div :class="`w-8 h-8 rounded-lg flex items-center justify-center text-white text-[10px] ${item.bgCol}`"><i :class="item.icon"></i></div>
                            <span class="font-bold text-slate-700 text-[11px]" x-text="item.name"></span>
                        </a>
                    </template>
                </div>
            </div>
        </div>
        <a href="../../index.php#nosotros" class="hover-text-theme transition text-black uppercase">Tecnología</a>
    </div>
    
    <div class="md:hidden">
        <button @click="mobileMenu = true" class="text-black text-2xl w-10 h-10 flex items-center justify-center"><i class="fas fa-bars"></i></button>
    </div>
</nav>

<div x-show="mobileMenu" x-cloak class="fixed inset-0 z-[110] md:hidden">
    <div @click="mobileMenu = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="fixed inset-y-0 right-0 z-[111] w-full max-w-xs bg-white shadow-xl flex flex-col">
        <div class="flex items-center justify-between px-6 py-6 border-b border-gray-100">
            <span class="font-black text-lg tracking-tighter uppercase italic">Menú</span>
            <button @click="mobileMenu = false" class="text-gray-500 text-2xl w-10 h-10 flex items-center justify-center"><i class="fas fa-times"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto px-4 py-4">
            <div class="flex flex-col gap-2">
                <template x-for="item in menuItems" :key="item.id">
                    <a @click="go(item.id); mobileMenu = false" class="flex items-center gap-4 p-4 hover:bg-gray-50 rounded-2xl transition cursor-pointer">
                        <div :class="`w-10 h-10 rounded-xl flex items-center justify-center text-white ${item.bgCol}`"><i :class="item.icon"></i></div>
                        <span class="font-bold text-slate-700 uppercase text-xs" x-text="item.name"></span>
                    </a>
                </template>
            </div>
        </div>
    </div>
</div>

<main class="max-w-5xl mx-auto px-4 py-10">
    <div class="text-center mb-10" x-show="!fullScreen">
        <h1 class="text-4xl md:text-6xl font-black mb-2 text-black uppercase italic">Lumina <span class="text-theme">Stream</span></h1>
        <p class="text-gray-400 font-bold uppercase text-[9px] tracking-[0.3em]">Memoria Volátil • Nexosyne 2026</p>
    </div>

    <div class="card-unified p-4 md:p-6 shadow-2xl" :class="fullScreen ? 'p-0 border-none' : ''">
        <div x-show="!isLoaded" class="py-16 flex flex-col items-center justify-center">
            <div @click="$refs.fileInput.click()" class="w-full max-w-md p-10 border-4 border-dashed border-gray-100 rounded-[3rem] cursor-pointer hover:border-theme bg-gray-50/50 transition-all group text-center">
                <i class="fas fa-file-pdf text-5xl text-gray-300 group-hover:text-theme mb-6 transition-colors"></i>
                <h3 class="text-xl font-black uppercase italic">Abrir Documento</h3>
                <p class="text-gray-400 font-bold text-[10px] uppercase italic">Compatible con XAMPP y Hostinger</p>
            </div>
        </div>

        <div x-show="isLoaded" x-cloak class="flex flex-col w-full">
            <div x-show="!fullScreen" class="flex justify-between items-center mb-4 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                <span class="font-black text-black text-[10px] uppercase italic truncate max-w-[200px]" x-text="shortName"></span>
                <div class="flex gap-2">
                    <button @click="toggleFS()" class="bg-black text-white px-4 py-2 rounded-xl font-black text-[9px] uppercase hover:bg-theme transition-colors">PANTALLA COMPLETA</button>
                    <button @click="reset()" class="bg-gray-200 text-black px-4 py-2 rounded-xl font-black text-[9px] uppercase hover:bg-red-500 hover:text-white transition-colors">BORRAR</button>
                </div>
            </div>

            <div class="viewer-wrapper" :class="fullScreen ? 'is-fullscreen' : ''">
                <button x-show="fullScreen" @click="toggleFS()" class="btn-exit-fs"><i class="fas fa-times"></i></button>
                <iframe x-ref="pdfIframe" src=""></iframe>
            </div>
        </div>
    </div>
    <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept=".pdf">
</main>

<script>
    // CARGA DE SPLASH
    window.addEventListener("load", () => {
        setTimeout(() => {
            const s = document.getElementById("splash");
            if(s) { s.style.opacity = "0"; setTimeout(() => s.remove(), 600); }
        }, 1200);
    });

    function luminaCore() {
        return {
            mobileMenu: false,
            isLoaded: false,
            fullScreen: false,
            shortName: '',
            currentBlobUrl: null,
            menuItems: [
                { id: 'tiktok', name: 'TikTok Downloader', icon: 'fab fa-tiktok', bgCol: 'bg-black' },
                { id: 'lector_doc', name: 'Lumina Stream', icon: 'fas fa-file-pdf', bgCol: 'bg-red-600' },
                { id: 'image-converter', name: 'Image Converter', icon: 'fas fa-sync-alt', bgCol: 'bg-emerald-500' },
                { id: 'image-resizer', name: 'Image Optimizer', icon: 'fas fa-compress-arrows-alt', bgCol: 'bg-purple-600' },
                { id: 'image-compress', name: 'Image Compress', icon: 'fas fa-file-image', bgCol: 'bg-emerald-600' }
            ],

            go(id) { window.location.href = `../../tools/${id}/index.php`; },

            getViewerPath() {
                const host = window.location.hostname;
                const path = window.location.pathname;
                // Ajuste de ruta inteligente
                if (host === 'localhost' || path.includes('/herramienta/')) {
                    return '/herramienta/assets/pdfjs/web/viewer.html';
                }
                return '/assets/pdfjs/web/viewer.html';
            },

            handleFile(e) {
                const file = e.target.files[0];
                if (!file || file.type !== 'application/pdf') return;

                if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
                this.shortName = file.name;
                this.currentBlobUrl = URL.createObjectURL(file);
                this.isLoaded = true;

                this.$nextTick(() => {
                    const path = this.getViewerPath();
                    // Usamos FitH para que el zoom sea automático al ancho
                    this.$refs.pdfIframe.src = `${path}?file=${encodeURIComponent(this.currentBlobUrl)}#view=FitH`;
                });
            },

            toggleFS() {
                this.fullScreen = !this.fullScreen;
                document.body.style.overflow = this.fullScreen ? 'hidden' : 'auto';
            },

            reset() {
                if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
                this.isLoaded = false;
                this.fullScreen = false;
                document.body.style.overflow = 'auto';
                this.$refs.pdfIframe.src = '';
                this.$refs.fileInput.value = '';
            }
        }
    }
</script>

<footer class="bg-black text-white pt-20 pb-10 px-6 mt-20">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-20 mb-16">
        <div>
            <img src="../../assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" alt="Nexosyne" class="h-12 mb-8" style="filter: drop-shadow(2px 0 0 white) drop-shadow(-2px 0 0 white) drop-shadow(0 2px 0 white) drop-shadow(0 -2px 0 white);">
            <p class="text-gray-400 font-bold text-sm leading-relaxed max-w-sm">
                Herramientas de alto rendimiento. Privacidad total: Procesamiento en memoria volátil sin almacenamiento en servidor.
            </p>
        </div>
        <div class="flex gap-16 md:justify-end">
            <div class="flex flex-col gap-4">
                <span class="text-theme font-black text-xs uppercase tracking-widest italic">Herramientas</span>
                <template x-for="item in menuItems">
                    <a @click="go(item.id)" class="font-bold text-sm hover:text-red-400 transition cursor-pointer" x-text="item.name"></a>
                </template>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto pt-8 border-t border-gray-900 text-center text-gray-500 text-[10px] font-black uppercase tracking-[0.3em]">
        <span>© 2026 Nexosyne Multimedia</span>
    </div>
</footer>
</body>
</html>