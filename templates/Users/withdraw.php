<h3>Historial de Retiros</h3>
<table>
    <thead>
        <tr>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Tx</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($withdrawals as $withdrawal): ?>
            <tr>
                <td><?= $withdrawal->amount ?> USDT</td>
                <td><?= $withdrawal->created->format('d/m/Y H:i') ?></td>
                <td><?= ucfirst($withdrawal->status) ?></td>
                <td>
                    <?php if ($withdrawal->tx_hash): ?>
                        <a href="https://bscscan.com/tx/<?= h($withdrawal->tx_hash) ?>" target="_blank">
                            Ver Tx
                        </a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
