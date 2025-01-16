<script>
    ////////////////////////////////////////////////////////////////
    //TUDO CERTO AQUI NÃO PRECISA DE ALTERAÇÃO SÓ FURUTRAS AO CASO!//
    ////////////////////////////////////////////////////////////////

    //ISSO AQUI ATIVA O MODAL
    $(document).on('click', '#recoverCategoryBtn', function() {

        var id = $(this).data('id');

        var url = '<?php echo route_to('categories.recover'); ?>';
        

        $.post(url,  {
            '<?php echo csrf_token(); ?>': $('meta[name="<?php echo csrf_token(); ?>"]').attr('content'),
            _method: 'PUT', // Spoofing do request
            
           id: id
            
        }, function(response) {

            window.refreshCSRFToken(response.token);

            toastr.success(response.message);


            $('#dataTable').DataTable().ajax.reload(null, false);

        }, 'json').fail(function(){
            toastr.error('backend-archive-script');
        });
    });
</script>