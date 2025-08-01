<div class="flex justify-center items-center min-h-screen bg-[#0d0d0d] px-4">
  <div class="bg-[#1e1e1e] rounded-2xl shadow-md p-6 w-full max-w-md text-white">
    <h2 class="text-xl font-bold mb-4 text-center">Crear cuenta</h2>

    <?= $this->Form->create($user) ?>
      <div class="mb-4">
        <?= $this->Form->control('email', [
          'label' => 'Correo electrónico',
          'class' => 'w-full bg-[#2a2a2a] text-white border border-[#333] rounded-lg p-2 focus:ring-2 focus:ring-[#07e63b]'
        ]) ?>
      </div>

   
<div class="mb-6 relative">
  <?= $this->Form->control('password', [
    'label' => 'Contraseña',
    'class' => 'w-full bg-[#2a2a2a] text-white border border-[#333] rounded-lg p-2 pr-10 focus:ring-2 focus:ring-[#07e63b]',
    'type' => 'password',
    'id' => 'password-field'
  ]) ?>

  <!-- Botón con icono -->
  <button type="button" onclick="togglePassword()" class="absolute top-9 right-3 text-white" id="toggle-password" aria-label="Mostrar/Ocultar contraseña">
    <!-- Ojo abierto (hidden por defecto) -->
    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <circle cx="12" cy="12" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>

    <!-- Ojo cerrado (por defecto visible) -->
    <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M3 3l18 18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M10.584 10.585A3 3 0 0012 15a3 3 0 002.121-.879" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M9.878 5.007A10.058 10.058 0 0112 5c7 0 11 7 11 7a15.56 15.56 0 01-2.303 3.38M6.12 6.12A15.55 15.55 0 001 12s4 7 11 7a10.05 10.05 0 004.121-.879" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </button>
</div>

<script>
function togglePassword() {
  const passwordInput = document.getElementById('password-field');
  const eye = document.getElementById('eye-icon');
  const eyeOff = document.getElementById('eye-off-icon');

  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    eye.classList.remove('hidden');
    eyeOff.classList.add('hidden');
  } else {
    passwordInput.type = 'password';
    eye.classList.add('hidden');
    eyeOff.classList.remove('hidden');
  }
}
</script>

      <div class="mb-4">
        <?= $this->Form->control('withdraw_wallet_address', [
          'label' => 'Billetera de retiro (USDT BEP20)',
          'class' => 'w-full bg-[#2a2a2a] text-white border border-[#333] rounded-lg p-2 focus:ring-2 focus:ring-[#07e63b]'
        ]) ?>
      </div>

      <?= $this->Form->button('Registrarse', ['class' => 'w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg']) ?>
    <?= $this->Form->end() ?>
    <div class="mt-4 text-center">
  <?= $this->Html->link(
    '¿Ya tienes cuenta? Iniciar sesión',
    ['controller' => 'Users', 'action' => 'login'],
    ['class' => 'text-green-400 hover:text-green-300 font-medium']
  ) ?>
</div>

  </div>
</div>
