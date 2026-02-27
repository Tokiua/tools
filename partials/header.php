<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexosyne Tools - Herramientas Multimedia</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nexoblack: '#0a0a0a',
                        nexopurple: '#7c3aed',
                        nexowhite: '#ffffff',
                        nexogray: '#f3f4f6'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #f3f4f6; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }
    </style>
</head>
<body class="text-nexoblack flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-nexoblack text-white hidden md:flex flex-col justify-between p-6 shadow-2xl z-20">
        <div>
            <div class="text-2xl font-bold tracking-tighter mb-10 text-nexopurple">
                NEXOSYNE<span class="text-white">TOOLS</span>
            </div>
            <nav class="space-y-4">
                <a href="/" class="block py-2 px-4 rounded hover:bg-nexopurple transition duration-300">🏠 Inicio</a>
                <div class="text-xs text-gray-500 uppercase mt-4 font-semibold">Video</div>
                <a href="/tools/tiktok" class="block py-2 px-4 rounded hover:bg-gray-800 transition">🎵 TikTok Downloader</a>
                <a href="/tools/youtube" class="block py-2 px-4 rounded hover:bg-gray-800 transition">📺 YouTube Converter</a>
                <div class="text-xs text-gray-500 uppercase mt-4 font-semibold">Imagen</div>
                <a href="/tools/image-resizer" class="block py-2 px-4 rounded hover:bg-gray-800 transition">🖼️ Resizer</a>
            </nav>
        </div>
        <div class="text-xs text-gray-500">
            v2.0.0 | Nexosyne
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-y-auto relative">
        <!-- Top Header -->
        <header class="bg-white h-16 shadow-sm flex items-center justify-between px-8 sticky top-0 z-10">
            <h1 class="font-bold text-lg">Panel de Herramientas</h1>
            <!-- Ad Placeholder -->
            <div class="hidden lg:block w-96 h-10 bg-gray-200 rounded animate-pulse flex items-center justify-center text-xs text-gray-400">
                Espacio Publicitario (Header)
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-8 flex-1">
<!-- Header Placeholder -->