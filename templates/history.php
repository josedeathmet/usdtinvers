<h3 class="text-light mb-4">Historial de Retiros</h3>

<table class="table table-dark table-bordered table-hover text-white">
    <thead>
        <tr>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($transactions) === 0): ?>
            <tr>
                <td colspan="3" class="text-center text-muted">No tienes retiros realizados</td>
            </tr>
        <?php else: ?>
            <?php foreach ($transactions as $tx): ?>
            <tr>
                <td><?= $tx->amount ?> USDT</td>
                <td><?= $tx->created->format('d/m/Y H:i') ?></td>
                <td>
                    <?php
                        $color = 'text-warning';
                        if ($tx->status === 'success') $color = 'text-success';
                        elseif ($tx->status === 'failed') $color = 'text-danger';
                    ?>
                    <span class="<?= $color ?>"><?= ucfirst($tx->status) ?></span>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
