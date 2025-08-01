<!-- Card blanca centrada con icono y botón copiar -->
<br>
<br>
<h2 class="text-xl font-bold mb-4 text-center text-white">Envía tus fondos a esta dirección para comenzar a invertir:</h2>


  

<h3 class="text-sm font-semibold text-white text-center flex items-center justify-center gap-2 mb-2">
  <img src="https://assets.coingecko.com/coins/images/325/thumb/Tether.png" alt="USDT" class="w-5 h-5" />
  USDT 
  <span class="bg-[#4e524e] text-white text-xs font-medium px-2 py-1 rounded-full">
    BEP20
  </span>
</h3>

<div class="bg-white text-black rounded-xl p-3 shadow max-w-sm mx-auto text-center mt-4 text-sm">
  <!-- Contenido aquí -->



  

  <!-- Código QR -->
  <div id="qrcode" class="mx-auto my-4 w-fit"></div>

  <!-- Dirección -->
  <p id="walletAddressText" class="break-all text-sm font-mono text-black-600 mb-3"><?= h($user->deposit_wallet_address) ?></p>



</div>
<br>
<div class="flex flex-col items-center justify-center text-center">
  <!-- Botón Copiar -->
 
 <button onclick="copiarWallet()" class="p-2 rounded-lg hover:bg-[#333] transition">
      <i data-lucide="clipboard-check" class="w-5 h-5 text-green-400"></i>
    </button>
    
  <!-- Mensaje de éxito -->
  <p id="mensajeCopiado" class="text-green-500 text-sm mt-2 hidden">¡Dirección copiada!</p>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    const walletAddress = "<?= h($user->deposit_wallet_address) ?>";
    new QRCode(document.getElementById("qrcode"), {
        text: walletAddress,
        width: 220,
        height: 220,
    });

    function copiarWallet() {
        const text = walletAddress;
        navigator.clipboard.writeText(text).then(function() {
            document.getElementById('mensajeCopiado').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('mensajeCopiado').classList.add('hidden');
            }, 2000);
        });
    }
</script>
