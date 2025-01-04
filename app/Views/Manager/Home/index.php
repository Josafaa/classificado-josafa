<?= $this->extend('Manager/Layout/main.php') ?>

<?= $this->section('title') ?>
<!-- Envio de template principal os arquivos css e stylos dessa view -->
    <?php echo $title ?? ''; ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>



<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Envio de conteúdo para a view -->
    <div class="container-fluid">
        <h1 class="mt-4">Simple Sidebar</h1>
        <p>The starting state of the menu will appear collapsed on smaller screens, and will appear non-collapsed on larger screens. When toggled using the button below, the menu will change.</p>
        <p>
            Make sure to keep all page content within the
            <code>#page-content-wrapper</code>
            . The top navbar is optional, and just for demonstration. Just create an element with the
            <code>#sidebarToggle</code>
            ID which will toggle the menu when clicked.
        </p>
    </div>
<?= $this->endSection() ?>
<!-- Envio para o template principal scripts dessa view -->

<?= $this->section('scripts') ?>
<?= $this->endSection() ?>