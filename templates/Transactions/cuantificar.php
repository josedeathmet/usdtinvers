<?php
use Cake\I18n\FrozenDate;

$yaCuantificado = $user->last_quantified &&
    $user->last_quantified->format('Y-m-d') === FrozenDate::now()->format('Y-m-d');
?>

<div class="flex flex-col justify-center items-center min-h-screen px-4 bg-[#121212]">

  <!-- Mensajes Flash -->



  <div class="bg-[#1A1A1A] text-white p-6 rounded-xl max-w-md w-full text-center shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Ganancia Diaria</h2>

    <!-- Fecha última cuantificación -->
    <p class="mb-2 text-gray-300">
      Última cuantificación:
      <strong><?= $user->last_quantified ? $user->last_quantified->i18nFormat('dd-MM-yyyy') : 'Nunca' ?></strong>
    </p>

    <p class="mb-2 text-gray-300">
      Días restantes:
      <strong><?= $user->investment_days_left ?></strong>
    </p>

    <!-- Cuenta regresiva -->
    <?php if ($yaCuantificado): ?>
      <p class="mb-4 text-yellow-400 font-semibold">
        Ya cuantificaste hoy. Vuelve en:
        <span id="cuentaRegresiva" class="font-mono text-lg block mt-2">--:--:--</span>
      </p>
    <?php else: ?>
      <p class="mb-4 text-green-400 font-semibold">
        Puedes cuantificar ahora.
      </p>
    <?php endif; ?>

    <!-- Botón -->
    <?php if ($yaCuantificado): ?>
      <button class="bg-green-400 cursor-not-allowed px-4 py-2 rounded font-semibold text-white" disabled>
        Ya cuantificaste hoy
      </button>
    <?php else: ?>
      <?= $this->Form->create(null, ['url' => ['action' => 'cuantificar'], 'method' => 'post']) ?>
        <?= $this->Form->button('Cuantificar ahora', [
            'class' => 'bg-green-400 hover:bg-green-500 text-white px-4 py-2 rounded',
            'id' => 'botonCuantificar'
        ]) ?>
      <?= $this->Form->end() ?>
    <?php endif; ?>
  </div>
</div>

<?php if ($yaCuantificado): ?>
<script>
// Calcular diferencia hasta la medianoche
function actualizarCuentaRegresiva() {
    const ahora = new Date();
    const medianoche = new Date();
    medianoche.setHours(24, 0, 0, 0);

    const diferencia = medianoche - ahora;

    const horas = String(Math.floor(diferencia / (1000 * 60 * 60))).padStart(2, '0');
    const minutos = String(Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
    const segundos = String(Math.floor((diferencia % (1000 * 60)) / 1000)).padStart(2, '0');

    document.getElementById('cuentaRegresiva').textContent = `${horas}:${minutos}:${segundos}`;
}

// Actualizar cada segundo
setInterval(actualizarCuentaRegresiva, 1000);
actualizarCuentaRegresiva();
</script>
<?php endif; ?>

<?php if ($user->investment_days_left <= 0): ?>
  <div class="flex justify-center mt-6">
    <form method="post" action="<?= $this->Url->build(['action' => 'reinvertir']) ?>">
      <?= $this->Form->button('🔄 Reinvertir', ['class' => 'btn btn-primary bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded']) ?>
    </form>
  </div>
<?php endif; ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const flashSuccess = document.querySelector(".message.success");
    const flashError = document.querySelector(".message.error");

    let mensaje = null;
    let tipo = null;

    if (flashSuccess && flashSuccess.innerText.trim() !== "") {
        mensaje = flashSuccess.innerText.trim();
        tipo = "success";
    } else if (flashError && flashError.innerText.trim() !== "") {
        mensaje = flashError.innerText.trim();
        tipo = "error";
    }

    if (mensaje && tipo) {
        const overlay = document.createElement("div");
        overlay.style.position = "fixed";
        overlay.style.top = "0";
        overlay.style.left = "0";
        overlay.style.width = "100%";
        overlay.style.height = "100%";
        overlay.style.backgroundColor = "rgba(0, 0, 0, 0.95)";
        overlay.style.display = "flex";
        overlay.style.justifyContent = "center";
        overlay.style.alignItems = "center";
        overlay.style.zIndex = "9999";

        const modal = document.createElement("div");
        modal.style.background = "#111";
        modal.style.color = "#fff";
        modal.style.padding = "30px";
        modal.style.borderRadius = "12px";
        modal.style.boxShadow = "0 0 25px rgba(0,0,0,0.8)";
        modal.style.textAlign = "center";
        modal.style.maxWidth = "90%";
        modal.style.fontFamily = "Arial, sans-serif";

        const title = tipo === "success" ? "¡Ganancia Cuantificada!" : "⚠️ Aviso Importante";
        const buttonColor = tipo === "success" ? "#28a745" : "#dc3545";

        modal.innerHTML = `
            <h2 style="margin-bottom: 15px;">${title}</h2>
            <p style="font-size: 18px;">${mensaje}</p>
            <button style="margin-top: 25px; padding: 10px 25px; background: ${buttonColor}; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Aceptar</button>
        `;

        modal.querySelector("button").addEventListener("click", function () {
            document.body.removeChild(overlay);
        });

        overlay.appendChild(modal);
        document.body.appendChild(overlay);
    }
});
</script>

