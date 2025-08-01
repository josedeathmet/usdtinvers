

<!-- Card oscura centrada -->
<div class="bg-[#1E1E1E] text-white rounded-xl p-5 shadow max-w-md mx-auto mt-6">

  <h2 class="text-xl font-bold mb-4 text-center">Transferir al fondo de inversión</h2>

  <?= $this->Flash->render() ?>

  <!-- Formulario -->
  <div class="withdraw-form">
    <?= $this->Form->create(null, ['url' => ['action' => 'perfil'], 'type' => 'post']) ?>

      <!-- Campo de cantidad -->
      <div>
        <?= $this->Form->label('amount', 'Cantidad a retirar (USDT)', ['class' => 'block text-sm font-medium text-gray-300']) ?>
        <?= $this->Form->control('amount', ['label' => false, 'type' => 'number', 'step' => '0.01',
        
            
            'class' => 'w-full mt-1 px-3 py-2 border border-green-400 bg-[#121212] text-white rounded-md focus:outline-none focus:ring-2 focus:ring-[#07e63b]'
        ]) ?>
      </div>
      <br>

      <!-- Botón -->
      <?= $this->Form->button('Transferir al fondo de inversión', ['class' => 'w-full bg-[#46b05f] text-white py-2 rounded-lg font-semibold hover:bg-green-400']) ?>

    <?= $this->Form->end() ?>
  </div>

  <!-- Info de saldo -->
  <hr class="my-4 border-[#333]">

   <div class="text-sm text-gray-400 text-center space-y-1">
    <p><strong>Saldo actual:</strong> <?= h($user->balance) ?> USDT</p>
  
  </div>

</div>
<div class="flex justify-center mt-6">
    <?= $this->Html->link(
      'Cerrar sesión',
      ['controller' => 'Users', 'action' => 'logout'],
      ['class' => 'text-gray-300 hover:text-white', 'escape' => false, 'confirm' => '¿Estás seguro de que deseas cerrar sesión?']
    ) ?>
  </div>