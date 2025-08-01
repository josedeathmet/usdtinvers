<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Lucide Icons -->
    
<script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->fetch('title') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #0F0F0F;
            font-size: 15px;
        }
    </style>
</head>
<body class="">
<?= $this->Flash->render() ?>
  <!-- Contenido centrado -->
  <main class="flex-1 w-full max-w-xl mx-auto px-4 pb-20">
     
    <?= $this->fetch('content') ?>
  </main>

  <!-- 📌 Menú inferior fijo tipo app -->
<footer class="fixed bottom-0 left-0 right-0 bg-[#1A1A1A] border-t border-[#333] text-xs max-w-xl mx-auto flex justify-around py-3 select-none">

  <a href="/" class="flex flex-col items-center inactive" data-path="/">
    <i data-lucide="home" class="w-6 h-6 mb-1 icon"></i>
    <span>Inicio</span>
  </a>

  <a href="/transactions/cuantificar" class="flex flex-col items-center inactive" data-path="/transactions/cuantificar">
    <i data-lucide="trending-up" class="w-6 h-6 mb-1 icon"></i>
    <span>quantify</span>
  </a>

  <a href="/users/referidos" class="flex flex-col items-center inactive" data-path="/users/referidos">
    <i data-lucide="handshake" class="w-6 h-6 mb-1 icon"></i>
    <span>invitar</span>
  </a>

  <a href="/users/perfil" class="flex flex-col items-center inactive" data-path="/users/perfil">
    <i data-lucide="user" class="w-6 h-6 mb-1 icon"></i>
    <span>Perfil</span>
  </a>

</footer>

<style>
  a.inactive {
    color: #9ca3af; /* gris claro */
  }
  a.active {
    color: #22c55e; /* verde brillante */
    pointer-events: none;
  }
  a .icon svg {
    stroke: currentColor !important;
  }
  a:hover, a:focus {
    color: inherit !important;
  }
</style>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();

  // Detectar el path actual y activar el enlace correspondiente
  const currentPath = window.location.pathname;
  const menuLinks = document.querySelectorAll('footer a');

  menuLinks.forEach(link => {
    if (link.dataset.path === currentPath) {
      link.classList.remove('inactive');
      link.classList.add('active');
    } else {
      link.classList.remove('active');
      link.classList.add('inactive');
    }
  });
</script>




<!-- Asegúrate que este script esté cargado -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>




<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script>
  lucide.createIcons();
</script>
<a href="https://t.me/usdtinversions_bot" target="_blank" class="telegram-float" title="Chatea con nosotros en Telegram">
  <img src="https://telegram.org/img/t_logo.png" alt="Telegram" width="40">
</a>
<style>
.telegram-float {
  position: fixed;
  bottom: 80px; /* para que no choque con tu footer */
  right: 20px;
  background-color: #0088cc;
  border-radius: 50%;
  padding: 10px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.3);
  z-index: 9999;
  animation: float 2s ease-in-out infinite;
  transition: transform 0.3s ease;
}
.telegram-float:hover {
  transform: scale(1.15);
}
@keyframes float {
  0%   { transform: translateY(0px); }
  50%  { transform: translateY(-8px); }
  100% { transform: translateY(0px); }
}
</style>


</body>

</html>
