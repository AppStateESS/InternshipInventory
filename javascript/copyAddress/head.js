<script type="text/javascript">
    $(document).ready(function(){
        /* When magic check-box is CHECKED copy the contents
         * of the agency's address info into the supervisor's
         * address info and update when changes are made.
         * When the box is UN-CHECKED clear info from supervisor's
         * address info.
         */
        $("input:checkbox[name='copy-address']").click(function(){
            if($(this).attr('checked')){
                /* Copy values from agency */
                $("input:text[name='agency_sup_address']").val($("input:text[name='agency_address']").val());
                $("input:text[name='agency_sup_city']").val($("input:text[name='agency_city']").val());
                $("select[name='agency_sup_state']").val($("select[name='agency_state']").val());
                $("input:text[name='agency_sup_zip']").val($("input:text[name='agency_zip']").val());
                /* Setup handlers */
                $("input:text[name='agency_address']").keyup(function(){
                    $("input:text[name='agency_sup_address']").val($("input:text[name='agency_address']").val());
                });
                $("input:text[name='agency_city']").keyup(function(){
                    $("input:text[name='agency_sup_city']").val($("input:text[name='agency_city']").val());
                });
                $("select[name='agency_state']").change(function(){
                    $("select[name='agency_sup_state']").val($("select[name='agency_state']").val());
                });
                $("input:text[name='agency_zip']").keyup(function(){
                    $("input:text[name='agency_sup_zip']").val($("input:text[name='agency_zip']").val());
                });

            }else{
                /* Clear fields */
                $("input:text[name='agency_sup_address']").val('');
                $("input:text[name='agency_sup_city']").val('');
                $("input:text[name='agency_sup_zip']").val('');
                $("select[name='agency_sup_state']").val('AL');// Reset to first in list.
                /* Remove handlers */
                $("input:text[name='agency_address']").unbind('keyup');
                $("input:text[name='agency_city']").unbind('keyup');
                $("input:text[name='agency_zip']").unbind('keyup');
                $("select[name='agency_state']").unbind('change');
            }
        });
    });
</script>