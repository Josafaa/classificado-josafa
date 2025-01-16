<?= $this->extend('Manager/Layout/main.php') ?>

<?= $this->section('title') ?>
<!-- Envio de template principal os arquivos css e estilos dessa view -->
    <?php echo $title ?? ''; ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-2.1.8/r-3.0.3/datatables.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Envio de conteúdo para a view -->
    <div class="container-fluid" style="padding-top: 20px;">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-lg" style="padding: 10px">
                    <div class="card-header">
                        <?php echo $title ?? ''; ?>
                    </div>
                    <div class="card-body">
                        <a class="btn btn-info btn-sm mt-2 mb-4" href="<?php echo route_to('plans'); ?>">voltar para Plans</a>
                        <table class="table table-borderless" id="datatable">
                            <thead class="bg-dark" style="color: white">
                                <tr>
                                    <th scope="col">Code</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Highlighted</th>
                                    <th scope="col">Details</th>
                                    <th scope="col">Ação</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $this->include('Manager/Plans/_modal_plan'); ?>

    
<?= $this->endSection() ?>

<!-- Envio para o template principal scripts dessa view -->

<?= $this->section('scripts') ?>
    <!-- Bootstrap 5 JS  TUDO CERTO AQUI TAMBÉM TODO O SCRIPT ATÉ OS DE BAIXO-->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-2.1.8/r-3.0.3/datatables.min.js"></script>
    <script type="text/javascript">src="<?php echo site_url('manager_assets/mask/jquery.mask.min.js') ?>"</script>
    <script type="text/javascript">src="<?php echo site_url('manager_assets/mask/app.js') ?>"</script>

    <?php echo $this->include('Manager/Plans/Scripts/_datatable_all_archived'); ?>
    <?php echo $this->include('Manager/Plans/Scripts/_recover_plan'); ?>
    <?php echo $this->include('Manager/Plans/Scripts/_delete_plan'); ?>
    
<?= $this->endSection() ?>