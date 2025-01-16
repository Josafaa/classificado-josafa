<script>
    ////////////////////////////////////////////////////////////////
    //TUDO CERTO AQUI NÃO PRECISA DE ALTERAÇÃO SÓ FUTURAS AO CASO!//
    ////////////////////////////////////////////////////////////////

    //ISSO AQUI ATIVA O MODAL
    $(document).on('click', '#deletePlanBtn', function() {

        var id = $(this).data('id');

        var url = '<?php echo route_to('plans.delete'); ?>';
        
        // Faz a requisição POST com spoofing de DELETE
        $.post(url,  {
            _method: 'DELETE', // Spoofing do request
            id: id
        }, function(response) {

            toastr.success(response.message);

            // Atualiza a tabela
            $('#dataTable').DataTable().ajax.reload(null, false);

        }, 'json').fail(function(){
            toastr.error('backend-delete-script');
        });
    });
</script>
