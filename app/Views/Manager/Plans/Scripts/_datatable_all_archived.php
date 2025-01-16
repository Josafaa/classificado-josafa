<script>

    //Tudo certo para a exibição da tabela, até então
    $(document).ready(function() {
        // Inicialize o DataTable com AJAX e outras configurações
        $('#datatable').DataTable({
            // Concentra o processamento nos mais recentes na tabela
            "order": [],
            "deferRender": true,
            "processing": true,
            "responsive": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>',
            }, // Adicione a vírgula aqui
            ajax: '<?php echo route_to('plans.get.all.archived'); ?>',  // Aqui você especifica a URL do arquivo ou endpoint que fornece os dados
            columns: [
                { data: 'code' },
                { data: 'name' },       // Mantenha 'nome' conforme está no banco de dados
                { data: 'is_highlighted' },
                { data: 'details' },
                { data: 'actions' },
            ],
        });
    });
</script>