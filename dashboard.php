<header class="bg-[#1A1A1A] p-3 flex justify-between items-center text-white">
  <span class="font-semibold"><?= $user->email ?></span>
  <span class="text-green-400 font-bold"><?= h($user->balance) ?> USDT</span>
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

  
 

  <div id="crypto-quotes" class="bg-[#121212] rounded-lg p-4 max-w-md mx-auto text-white font-sans shadow-lg mt-8">
    <h3 class="text-lg font-bold mb-4">Cotizaciones en tiempo real</h3>
    <div id="quotes-list" class="space-y-3"></div>
  </div>

  <script>
    const coins = [
      { id: 'bitcoin', symbol: 'BTC', name: 'Bitcoin' },
      { id: 'ethereum', symbol: 'ETH', name: 'Ethereum' },
      { id: 'binancecoin', symbol: 'BNB', name: 'Binance Coin' },
      { id: 'solana', symbol: 'SOL', name: 'Solana' },
      { id: 'ripple', symbol: 'XRP', name: 'XRP' }
    ];

    async function fetchPrices() {
      try {
        const ids = coins.map(c => c.id).join(',');
        const res = await fetch(`https://api.coingecko.com/api/v3/simple/price?ids=${ids}&vs_currencies=usd&include_24hr_change=true`);
        const data = await res.json();

        const container = document.getElementById('quotes-list');
        container.innerHTML = '';

        const logoIdMap = {
          bitcoin: '1',
          ethereum: '279',
          binancecoin: '825',
          solana: '4128',
          ripple: '44'
        };

        coins.forEach(coin => {
          const price = data[coin.id]?.usd?.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) ?? 'N/A';
          const change = data[coin.id]?.usd_24h_change ?? 0;
          const changeColor = change >= 0 ? 'text-green-400' : 'text-red-500';
          const changeSign = change >= 0 ? '+' : '';

          const logoUrl = `https://assets.coingecko.com/coins/images/${logoIdMap[coin.id]}/thumb.png`;

          const item = document.createElement('div');
          item.className = 'flex items-center justify-between';

          item.innerHTML = `
            <div class="flex items-center space-x-3">
              <img src="${logoUrl}" alt="${coin.symbol}" class="w-8 h-8 rounded-full" />
              <div>
                <p class="font-semibold">${coin.symbol}/USDT</p>
                <p class="text-gray-400 text-xs">${coin.name}</p>
              </div>
            </div>
            <div class="text-right">
              <p class="font-semibold">$${price}</p>
              <p class="${changeColor} text-sm font-medium">${changeSign}${change.toFixed(2)}%</p>
            </div>
          `;
          container.appendChild(item);
        });
      } catch (error) {
        console.error('Error fetching prices:', error);
      }
    }

    fetchPrices();
    setInterval(fetchPrices, 60000);
  </script>

  

</main>
