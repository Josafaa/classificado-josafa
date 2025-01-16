

    <!-- Modal  TUDO CERTO AQUI TAMBÉM-->
    <div class="modal fade" id="modalPlan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Criar Plano</h5>
                    <!-- Botão de Fechar -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <?php echo form_open(route_to('plans.create'), ['id' => 'plans-form'], ['id' => '']); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3"> 
                            <label for="name" class="form-label">Nome do Plano</label>
                            <input type="text" class="form-control" id="name" name="name">
                            <span class="text-danger error-text name"></span>
                        </div>
                        <div class="mb-3">
                            <label for="recorrence" class="form-label">Recorrence</label>
                            <!-- boxParent será preenchido pelo JS -->
                            <div id="boxRecorrences"></div>
                            <span class="text-danger error-text recorrence"></span>
                        </div>
                        <div class="mb-3">
                            <label for="value" class="form-label">Valor</label>
                            <input type="text" class="money form-control" id="value" name="value">
                            <span class="text-danger error-text value"></span>
                        </div>
                        <div class="mb-3">
                            <label for="adverts" class="form-label">Adverts</label>
                            <input type="number" class="form-control" id="adverts" name="adverts">
                            <span class="text-danger error-text adverts"></span>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" name="description" rows="5" placeholder="Descrição"></textarea>
                            <span class="text-danger error-text description"></span>
                        </div>
                    </div>
                    <!-- Remover form_hidden e modificar checkbox -->
                    <!--<div class="form-check form-switch">
                        // echo form_hidden('is_highlighted', 0)
                        <input class="form-check-input" name="is_highlighted" type="checkbox" id="is_highlighted">
                        <label class="form-check-label" for="is_highlighted">IS HIGHLIGHTED</label>
                    </div>-->

                    <div class="form-check form-switch">
                        <?php echo form_hidden('is_highlighted', '0'); ?>
                        <input class="form-check-input" name="is_highlighted" type="checkbox" id="is_highlighted" value="1">
                        <label class="form-check-label" for="is_highlighted">IS HIGHLIGHTED</label>
                    </div>



                    
                    <div class="mb-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>