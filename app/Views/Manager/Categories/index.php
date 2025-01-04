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
                        <button  id="createCategoryBtn" type="button" class="btn btn-success btn-sm float-end" >Criar Categoria</button>
                    </div>
                    <div class="card-body">
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

    <!-- Modal  TUDO CERTO AQUI TAMBÉM-->
    <div class="modal fade" id="categoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Criar Categoria</h5>
                    <!-- Botão de Fechar -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <?php echo form_open(route_to('categories.create'), ['id' => 'categories-form'], ['id' => '']); ?>

                <div class="modal-body">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="name" name="name">
                    <span class="text-danger error-text name"></span>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Categoria Pai</label>
                        <!-- boxParent será preenchido pelo JS -->
                        <div id="boxParents"></div>
                        <span class="text-danter error-text parent_id"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<!-- Envio para o template principal scripts dessa view -->

<?= $this->section('scripts') ?>
    <!-- Bootstrap 5 JS  TUDO CERTO AQUI TAMBÉM TODO O SCRIPT ATÉ OS DE BAIXO-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.1.8/r-3.0.3/datatables.min.js"></script>
    <?php echo $this->include('Manager/Categories/Scripts/_datatable_all'); ?>
    <?php echo $this->include('Manager/Categories/Scripts/_get_category_info'); ?>
    <?php echo $this->include('Manager/Categories/Scripts/_submit_modal_create_update'); ?>
    <?php echo $this->include('Manager/Categories/Scripts/_show_modal_to_create'); ?>
    <?php echo $this->include('Manager/Categories/Scripts/_archive_category'); ?>

    <script>
        function refreshCSRFToken(token) {

            $('[name="<?php echo csrf_token(); ?>"]').val(token);
            $('meta[name="<?php echo csrf_token(); ?>"]').attr('content', token);

        };
    </script>
    
<?= $this->endSection() ?>