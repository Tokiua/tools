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
<link rel="icon" type="image/png" href="../../assets/img/nexosyne.ico">

<style>
:root { --theme-color: <?php echo $themeHex; ?>; }

body {
font-family: 'Plus Jakarta Sans', sans-serif;
background:#fcfcfc;
overflow-x:hidden;
}

.text-theme { color: var(--theme-color); }
.hover-text-theme:hover { color: var(--theme-color); }

.glass-nav {
backdrop-filter: blur(12px);
background: rgba(255,255,255,0.95);
border-bottom:2px solid rgba(0,0,0,0.05);
}

[x-cloak] { display:none !important; }

/* 🔥 SPLASH */
#splash{
position:fixed;
inset:0;
background:<?php echo $themeHex; ?>;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
z-index:9999;
transition:opacity .6s ease;
}

.logo-animate{
width:200px;
height:200px;
object-fit:contain;
animation:floatLogo 3s ease-in-out infinite;
}

@keyframes floatLogo{
0%{ transform:translateY(0px); }
50%{ transform:translateY(-12px); }
100%{ transform:translateY(0px); }
}

.loader-ring{
margin-top:30px;
width:45px;
height:45px;
border:4px solid rgba(255,255,255,.3);
border-top:4px solid white;
border-radius:50%;
animation:spin 1s linear infinite;
}

@keyframes spin{
to{ transform:rotate(360deg); }
}

/* 🔥 INSTALL BOX */
.install-box{
position:fixed;
bottom:30px;
left:50%;
transform:translateX(-50%);
background:white;
padding:30px 35px;
border-radius:28px;
box-shadow:0 30px 80px rgba(0,0,0,.18);
z-index:9998;
width:95%;
max-width:500px;
animation:fadeIn .4s ease;
}

@keyframes fadeIn{
from{opacity:0; transform:translate(-50%,20px);}
to{opacity:1; transform:translate(-50%,0);}
}

.install-close{
position:absolute;
top:15px;
right:20px;
font-size:20px;
cursor:pointer;
color:#999;
}

.install-btn{
margin-top:20px;
background:<?php echo $themeHex; ?>;
color:white;
padding:13px 30px;
border-radius:18px;
font-weight:800;
cursor:pointer;
border:none;
font-size:14px;
transition:.3s;
}

.install-btn:hover{
transform:translateY(-2px);
box-shadow:0 10px 25px rgba(0,0,0,.2);
}
</style>
</head>

<body class="antialiased text-slate-900" x-data="nexosyneCore()">

<!-- 🔥 SPLASH -->
<div id="splash">
<img src="../../assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" class="logo-animate">

<h1 style="color:white;margin-top:25px;font-size:26px;font-weight:800;">
Nexosyne Tools
</h1>

<p style="color:rgba(255,255,255,.85);margin-top:10px;font-size:15px;">
Cargando herramienta cómoda...
</p>

<div class="loader-ring"></div>
</div>

<script>
window.addEventListener("load",function(){
setTimeout(()=>{
document.getElementById("splash").style.opacity="0";
setTimeout(()=>{
document.getElementById("splash").remove();
},600);
},1400);
});
</script>

<!-- 🔥 INSTALL PROMPT -->
<script>
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
e.preventDefault();
deferredPrompt = e;

if(localStorage.getItem("installDismissed")) return;

const installBox = document.createElement("div");
installBox.className="install-box";
installBox.innerHTML = `
<div class="install-close" id="closeInstall">&times;</div>

<div style="display:flex;align-items:center;gap:15px;">
<i class="fas fa-rocket" style="font-size:28px;color:<?php echo $themeHex; ?>;"></i>
<div>
<p style="font-weight:800;font-size:20px;">Nexosyne Tools</p>
<p style="font-size:13px;color:#666;margin-top:4px;">
Varias herramientas gratis sin anuncios
</p>
</div>
</div>

<button id="installApp" class="install-btn">
Instalar Aplicación
</button>
`;

document.body.appendChild(installBox);

document.getElementById("installApp").addEventListener("click", () => {
installBox.remove();
deferredPrompt.prompt();
});

document.getElementById("closeInstall").addEventListener("click", () => {
localStorage.setItem("installDismissed", "true");
installBox.remove();
});
});
</script>

<!-- 🔵 TU ZONA DE NAVEGACIÓN ORIGINAL (NO MODIFICADA) -->
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
                HERRAMIENTAS <i class="fas fa-chevron-down text-[10px]"></i>
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