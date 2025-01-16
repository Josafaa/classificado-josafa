<?= $this->extend('Manager/Layout/main.php') ?>

<?= $this->section('title') ?>
<!-- Envio de template principal os arquivos css e estilos dessa view -->
    <?php echo $title ?? ''; ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.1.8/r-3.0.3/datatables.min.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Envio de conteúdo para a view -->
    <div class="container-fluid" style="padding-top: 20px;">
        <div class="row">
            <div class="col-md-6">
                <div class="Card shadow-lg" style="padding: 10px">
                    <div class="card-header">
                        <h5><?php echo $title ?? ''; ?></h5>
                    </div>
                    <div class="card-body">
                        <a class="btn btn-primary btn-sm mt-2 mb-4" href="<?php echo route_to('categories'); ?>">Voltar</a>
                        <table class="table table-borderless" id="datatable">
                            <thead class="bg-dark" style="color: white">
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Ação</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?= $this->endSection() ?>

<!-- Envio para o template principal scripts dessa view -->

<?= $this->section('scripts') ?>
    <!-- Bootstrap 5 JS  TUDO CERTO AQUI TAMBÉM TODO O SCRIPT ATÉ OS DE BAIXO-->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-2.1.8/r-3.0.3/datatables.min.js"></script>
    <?php echo $this->include('Manager/Categories/Scripts/_datatable_all_archived'); ?>
    <?php echo $this->include('Manager/Categories/Scripts/_recover_category'); ?>
    <?php echo $this->include('Manager/Categories/Scripts/_delete_category'); ?>
    
<?= $this->endSection() ?>