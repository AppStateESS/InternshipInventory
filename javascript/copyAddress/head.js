<script type="text/javascript">
    $(document).ready(function(){
        /* When magic check-box is CHECKED copy the contents
         * of the agency's address info into the supervisor's
         * address info and update when changes are made.
         * When the box is UN-CHECKED remove handlers.
         */
        function doIt(item){
            if($(item).attr('checked')){
                /* Copy values from agency */
                $("input:text[name='agency_sup_address']").val($("input:text[name='agency_address']").val());
                $("input:text[name='agency_sup_city']").val($("input:text[name='agency_city']").val());
                $("select[name='agency_sup_state']").val($("select[name='agency_state']").val());
                $("input:text[name='agency_sup_zip']").val($("input:text[name='agency_zip']").val());
                $("input:text[name='agency_sup_country']").val($("input:text[name='agency_country']").val());
                /* Setup handlers */
                $("input:text[name='agency_address']").keyup(function(){
                    $("input:text[name='agency_sup_address']").val($("input:text[name='agency_address']").val());
                });
                $("input:text[name='agency_city']").keyup(function(){
                    $("input:text[name='agency_sup_city']").val($("input:text[name='agency_city']").val());
                });
                $("select[name='agency_state']").change(function(){
                    $("select[name='agency_sup_state']").val($("select[name='agency_state']").val()).change();
                });
                $("input:text[name='agency_zip']").keyup(function(){
                    $("input:text[name='agency_sup_zip']").val($("input:text[name='agency_zip']").val());
                });
                $("input:text[name='agency_country']").keyup(function(){
                    $("input:text[name='agency_sup_country']").val($("input:text[name='agency_country']").val());
                });

            }else{
                /* Remove handlers */
                $("input:text[name='agency_address']").unbind('keyup');
                $("input:text[name='agency_city']").unbind('keyup');
                $("input:text[name='agency_zip']").unbind('keyup');
                $("input:text[name='agency_country']").unbind('keyup');
                $("select[name='agency_state']").unbind('change');
            }
        }
        $("input:checkbox[name='copy_address']").click(function(){
            doIt(this);
        });
        /* If checkbox is set already then go ahead and copy the address to make sure. */
        if($("input:checkbox[name='copy_address']").attr('checked')){
            doIt("input:checkbox[name='copy_address']");
        }
    });
</script>