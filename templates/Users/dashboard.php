<header class="bg-[#1A1A1A] p-3 flex justify-between items-center text-white">
  <span class="font-semibold"><?= $user->email ?></span>

  <div class="relative">
   <?= $this->Html->link(
  '<i data-lucide="bell" class="w-6 h-6"></i>',
  ['controller' => 'Transactions', 'action' => 'history'],
  ['escape' => false, 'class' => 'relative']
) ?>


    <!-- Badge de notificaciones -->
    <?php if (!empty($user->unread_notifications_count)) : ?>
      <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
        <?= $user->unread_notifications_count ?>
      </span>
    <?php endif; ?>
  </div>

 
</header>


<main class="flex-1 px-3 py-4 overflow-y-auto">

  <div class="space-y-3 text-white text-center max-w-md mx-auto">

    <div>
      <p class="text-gray-400 text-xs">Disponible</p>
      <p class="text-xl font-semibold"><?= h($user->balance) ?> USDT</p>
    </div>

    <div>
      <p class="text-gray-400 text-xs">Invertido</p>
      <p class="text-sm font-semibold"><?= h($user->investment_fund) ?> USDT</p>
    </div>

    <div>
      <p class="text-gray-400 text-xs">Ganancia</p>
      <p class="text-sm font-semibold text-green-400">+<?= h($user->daily_profit) ?> USDT</p>
    </div>

  </div>

  <div class="grid grid-cols-4 gap-2 mt-4 text-white max-w-xl mx-auto">

    <!-- Botones -->
    <?= $this->Html->link(
      '<div class="flex flex-col items-center justify-center py-3">
          <i data-lucide="banknote-arrow-up" class="w-5 h-5 mb-1"></i>
          <span class="text-xs font-medium">Deposit</span>
       </div>',
      ['controller' => 'Transactions', 'action' => 'deposit'],
      ['escape' => false, 'class' => 'bg-[#1E1E1E] rounded-xl text-center hover:bg-[#2a2a2a] transition']
    ) ?>

    <?= $this->Html->link(
      '<div class="flex flex-col items-center justify-center py-3">
          <i data-lucide="wallet" class="w-5 h-5 mb-1"></i>
          <span class="text-xs font-medium">Retirar</span>
       </div>',
      ['controller' => 'Transactions', 'action' => 'withdraw'],
      ['escape' => false, 'class' => 'bg-[#1E1E1E] rounded-xl text-center hover:bg-[#2a2a2a] transition']
    ) ?>

    <?= $this->Html->link(
      '<div class="flex flex-col items-center justify-center py-3">
          <i data-lucide="users" class="w-5 h-5 mb-1"></i>
          <span class="text-xs font-medium">Red</span>
       </div>',
      ['controller' => 'Users', 'action' => 'referidoslist'],
      ['escape' => false, 'class' => 'bg-[#1E1E1E] rounded-xl text-center hover:bg-[#2a2a2a] transition']
    ) ?>

    <?= $this->Html->link(
      '<div class="flex flex-col items-center justify-center py-3">
          <i data-lucide="gem" class="w-5 h-5 mb-1"></i>
          <span class="text-xs font-medium">VIP</span>
       </div>',
      ['controller' => 'Users', 'action' => 'nivelesInversion'],
      ['escape' => false, 'class' => 'bg-[#1E1E1E] rounded-xl text-center hover:bg-[#2a2a2a] transition']
    ) ?>

  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>

   <title>Cripto Precios</title>
  <style>
    body { font-family: Arial, sans-serif; background: #0f0f0f; color: #fff; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; text-align: center; border-bottom: 1px solid #444; }
    th { background-color: #1f1f1f; }
    .up { color: #00ff99; }
    .down { color: #ff4d4d; }
  </style>
</head>
<body>

 
  <table>
    <thead>
      <tr>
        <th>Par</th>
        <th>Precio (USDT)</th>
        <th>Cambio</th>
      </tr>
    </thead>
    <tbody id="quotes">
      <tr><td>BTC/USDT</td><td>117322.8</td><td class="down">-0.59%</td></tr>
      <tr><td>ETH/USDT</td><td>3757.1546</td><td class="down">-0.87%</td></tr>
      <tr><td>BNB/USDT</td><td>801.7</td><td class="down">-2.93%</td></tr>
      <tr><td>XRP/USDT</td><td>3.108923</td><td class="down">-1.19%</td></tr>
      <tr><td>ADA/USDT</td><td>0.77307</td><td class="down">-2.62%</td></tr>
      <tr><td>SOL/USDT</td><td>179.7688</td><td class="down">-2.54%</td></tr>
      <tr><td>DOGE/USDT</td><td>0.220889</td><td class="down">-3.16%</td></tr>
      <tr><td>DOT/USDT</td><td>3.844</td><td class="down">-3.37%</td></tr>
      <tr><td>LTC/USDT</td><td>107.9596</td><td class="down">-1.45%</td></tr>
      <tr><td>TRX/USDT</td><td>0.33468</td><td class="up">+4.12%</td></tr>
      <tr><td>SHIB/USDT</td><td>0.00001294</td><td class="down">-3.29%</td></tr>
      <tr><td>AVAX/USDT</td><td>24.034</td><td class="down">-2.00%</td></tr>
    </tbody>
  </table>
 

  

  

</main>
