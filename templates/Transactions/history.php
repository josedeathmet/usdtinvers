<h2 class="mb-4 text-white text-center">Historial de Retiros Exitosos</h2>

<?php if (empty($transactions)): ?>
    <div class="alert alert-info">No hay retiros exitosos aún.</div>
<?php else: ?>
    <div class="row">
        <?php foreach ($transactions as $tx): ?>
<div class="bg-[#1E1E1E] text-white rounded-xl p-5 shadow max-w-md mx-auto mt-6 justify-center">                        <h5 class="card-title flex items-center gap-2">
                            <i data-lucide="banknote-arrow-up" class="w-5 h-5"></i> Retiro Exitoso
                        </h5>
                        <p><strong>Monto:</strong> <?= $tx->amount ?> USDT</p>
                        <p>
                            <strong>Fecha:</strong> 
                            <?= $tx->created ? $tx->created->format('d/m/Y H:i') : 'No disponible' ?>
                        </p>
                        
                        <span class="badge bg-success">Completado</span>
                    </div>
              
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Asegúrate que Lucide esté cargado -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>




