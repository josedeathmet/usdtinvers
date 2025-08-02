<br>
<div class="bg-[#1E1E1E] text-white rounded-xl p-5 shadow max-w-md mx-auto mt-6 justify-center text-center">

<h3>Ganancias por referidos: <br><?= $user->referral_earnings ?> USDT</h3>
</div>


<br>
<div class="bg-[#1e1e1e] text-white rounded-xl p-4 shadow-md max-w-md mx-auto mt-4">
  <p class="mb-2 text-sm">Tu enlace de referido:</p>

  <div class="flex items-center gap-2">
    <input
      id="enlaceReferido"
      type="text"
      readonly
      value="https://usdtinvers-production.up.railway.app/users/register?ref=<?= h($user->ref_code) ?>"
      class="flex-1 bg-[#2a2a2a] text-white border border-[#333] rounded-lg p-2 text-sm"
    />

    <button onclick="copiarEnlace()" class="p-2 rounded-lg hover:bg-[#333] transition">
      <i data-lucide="clipboard-check" class="w-5 h-5 text-green-400"></i>
    </button>
    
  </div>

  <p id="mensajeCopiado" class="text-green-400 text-sm mt-2 hidden">¡Enlace copiado al portapapeles!</p>
</div>

<script>
function copiarEnlace() {
  const input = document.getElementById("enlaceReferido");
  input.select();
  input.setSelectionRange(0, 99999); // Para móviles
  document.execCommand("copy");

  const mensaje = document.getElementById("mensajeCopiado");
  mensaje.classList.remove("hidden");
  setTimeout(() => mensaje.classList.add("hidden"), 2000);
}
</script>

<script>
  lucide.createIcons(); // Asegúrate de que esto esté presente una vez en tu layout
</script>

