<!-- Card oscura centrada -->
<div class="bg-[#1E1E1E] text-white rounded-xl p-5 shadow max-w-md mx-auto mt-6">

  <h2 class="text-xl font-bold mb-4 text-center">Solicitar Retiro</h2>

  <?= $this->Flash->render() ?>

  <!-- Formulario -->
  <div class="withdraw-form">
    <?= $this->Form->create(null, ['class' => 'space-y-4']) ?>

      <!-- Campo de cantidad -->
      <div>
        <?= $this->Form->label('amount', 'Cantidad a retirar (USDT)', ['class' => 'block text-sm font-medium text-gray-300']) ?>
        <?= $this->Form->control('amount', [
            'label' => false,
            'type' => 'number',
            'min' => '0.1',
            'step' => '0.01',
            'required' => true,
            'class' => 'w-full mt-1 px-3 py-2 border border-green-400 bg-[#121212] text-white rounded-md focus:outline-none focus:ring-2 focus:ring-[#07e63b]'
        ]) ?>
      </div>

      <!-- Botón -->
      <?= $this->Form->button('Retirar', ['class' => 'w-full bg-[#46b05f] text-white py-2 rounded-lg font-semibold hover:bg-green-400']) ?>

    <?= $this->Form->end() ?>
  </div>

  <!-- Info de saldo -->
  <hr class="my-4 border-[#333]">

  <div class="text-sm text-gray-400 text-center space-y-1">
    <p><strong>Saldo actual:</strong> <?= h($user->balance) ?> USDT</p>
    <p><strong>Ganancia diaria disponible:</strong> <?= h($user->daily_profit) ?> USDT</p>
  </div>

</div>
