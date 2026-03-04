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

<!-- PWA -->
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="<?php echo $themeHex; ?>">

<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="icon" type="image/png" href="/assets/img/192x192.png">

<style>
:root { --theme-color: <?php echo $themeHex; ?>; }

body{
font-family:'Plus Jakarta Sans',sans-serif;
background:#fcfcfc;
overflow-x:hidden;
}

.text-theme{ color:var(--theme-color); }
.hover-text-theme:hover{ color:var(--theme-color); }

.glass-nav{
backdrop-filter:blur(14px);
background:rgba(255,255,255,.95);
border-bottom:1px solid rgba(0,0,0,.05);
}

[x-cloak]{display:none!important}

/* SPLASH */
#splash{
position:fixed;
inset:0;
background:<?php echo $themeHex; ?>;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
z-index:9999;
transition:.6s;
}

.logo-animate{
width:180px;
animation:float 3s ease-in-out infinite;
}

@keyframes float{
0%{transform:translateY(0)}
50%{transform:translateY(-12px)}
100%{transform:translateY(0)}
}

.loader{
margin-top:25px;
width:40px;
height:40px;
border:4px solid rgba(255,255,255,.3);
border-top:4px solid white;
border-radius:50%;
animation:spin 1s linear infinite;
}

@keyframes spin{
to{transform:rotate(360deg)}
}

/* INSTALL BOX */
.install-box{
position:fixed;
bottom:25px;
left:50%;
transform:translateX(-50%);
background:white;
padding:25px 30px;
border-radius:25px;
box-shadow:0 25px 70px rgba(0,0,0,.15);
z-index:9998;
width:95%;
max-width:480px;
animation:fadeIn .4s ease;
}

@keyframes fadeIn{
from{opacity:0; transform:translate(-50%,20px);}
to{opacity:1; transform:translate(-50%,0);}
}

.install-btn{
margin-top:15px;
background:<?php echo $themeHex; ?>;
color:white;
padding:12px 25px;
border-radius:15px;
font-weight:700;
border:none;
cursor:pointer;
transition:.3s;
}

.install-btn:hover{
transform:translateY(-2px);
box-shadow:0 10px 25px rgba(0,0,0,.2);
}
</style>
</head>

<body x-data="nexosyneCore()" class="antialiased text-slate-900">

<!-- SPLASH -->
<div id="splash">
<img src="../../assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" class="logo-animate">
<h1 class="text-white text-2xl font-extrabold mt-6">Nexosyne Tools</h1>
<p class="text-white/80 text-sm mt-2">Iniciando aplicación...</p>
<div class="loader"></div>
</div>

<script>
window.addEventListener("load",()=>{
setTimeout(()=>{
document.getElementById("splash").style.opacity="0";
setTimeout(()=>{document.getElementById("splash").remove();},600);
},1200);
});
</script>

<!-- NAVBAR ORIGINAL -->
<nav class="glass-nav sticky top-0 z-[100] py-4 px-6 md:px-12 flex justify-between items-center">

<div class="flex items-center gap-3">
<a href="../../index.php" class="flex items-center gap-3">
<img src="../../assets/img/Gemini_Generated_Image_ko7frako7frako7f.png" class="h-10">
<span class="text-xl font-extrabold">
Nexosyne<span class="text-theme">Tools</span>
</span>
</a>
</div>

<div class="hidden md:flex gap-8 text-xs font-black uppercase tracking-widest items-center">
<button @click="installApp()" class="hover-text-theme">Instalar App</button>
<a href="../../index.php#nosotros" class="hover-text-theme">Tecnología</a>
</div>

<div class="md:hidden">
<button @click="mobileMenu = true" class="text-2xl">
<i class="fas fa-bars"></i>
</button>
</div>

</nav>

<!-- MOBILE MENU -->
<div x-show="mobileMenu"
x-cloak
class="fixed inset-0 bg-white z-[200] p-8 flex flex-col md:hidden"
x-transition>

<div class="flex justify-between items-center mb-10">
<span class="font-extrabold text-xl">Nexosyne Tools</span>
<button @click="mobileMenu=false" class="text-3xl">&times;</button>
</div>

<button @click="installApp(); mobileMenu=false" class="py-4 border-b text-left">
Instalar App
</button>

<a href="../../index.php#nosotros" class="py-4 border-b">
Tecnología
</a>

</div>

<script>
function nexosyneCore(){
return{
mobileMenu:false,
deferredPrompt:null,

init(){
window.addEventListener('beforeinstallprompt',(e)=>{
e.preventDefault();
this.deferredPrompt=e;

if(!localStorage.getItem("installShown")){
this.showInstallBox();
localStorage.setItem("installShown","true");
}
});
},

installApp(){
if(this.deferredPrompt){
this.deferredPrompt.prompt();
}
},

showInstallBox(){
const box=document.createElement("div");
box.className="install-box";
box.innerHTML=`
<p class="font-bold text-lg">Instalar Nexosyne Tools</p>
<p class="text-sm text-gray-600 mt-1">
Instala la aplicación para usarla como app real en tu dispositivo.
</p>
<button id="installNow" class="install-btn w-full">
Instalar Aplicación
</button>
`;
document.body.appendChild(box);

document.getElementById("installNow").addEventListener("click",()=>{
box.remove();
this.installApp();
});
}
}
}
</script>

<!-- SERVICE WORKER -->
<script>
if('serviceWorker' in navigator){
navigator.serviceWorker.register('/sw.js');
}
</script>