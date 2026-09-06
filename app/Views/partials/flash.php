<?php
?>
<?php if (!empty($_SESSION['sucesso'])): ?>
    <div class="flash flash-success">
        <span><?= htmlspecialchars($_SESSION['sucesso']) ?></span>
        <button class="flash-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php unset($_SESSION['sucesso']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['erro'])): ?>
    <div class="flash flash-error">
        <span><?= htmlspecialchars($_SESSION['erro']) ?></span>
        <button class="flash-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php unset($_SESSION['erro']); ?>
<?php endif; ?>
