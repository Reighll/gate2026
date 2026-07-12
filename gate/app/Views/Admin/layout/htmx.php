<title><?= $this->renderSection('title') ?? 'GATE System' ?></title>

<div class="container-fluid" id="app-content">
    <?= $this->renderSection('content') ?>
</div>

<?= $this->renderSection('scripts') ?>