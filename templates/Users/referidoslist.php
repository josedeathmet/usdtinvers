<?php
$this->assign('title', 'referidoslist');
?>



<div class="bg-[#1E1E1E] text-white rounded-xl p-5 shadow max-w-md mx-auto mt-6 justify-center text-center">

    <h3>Total ganado por referidos:</h3>
    <p style="font-size: 1.5rem;">$<?= number_format($user->referral_earnings, 2) ?> USDT</p>
</div>


<?php if (empty($referidos)): ?>
    <p>No tienes referidos aún.</p>
<?php else: ?>
    <?php foreach ($referidos as $r): ?>
        <div class="bg-[#1E1E1E] text-white rounded-xl p-5 shadow max-w-md mx-auto mt-6 justify-center text-center">
            <strong>Correo:</strong> <?= h($r->email) ?><br>
            <strong>Fecha de registro:</strong> <?= $r->created->i18nFormat('dd-MM-yyyy') ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


