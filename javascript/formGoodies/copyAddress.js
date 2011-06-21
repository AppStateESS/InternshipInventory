function copyAddress(){
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
            $("#internship_agency_sup_state").val($("#internship_agency_state").val());
            $("input:text[name='agency_sup_zip']").val($("input:text[name='agency_zip']").val());
            $("input:text[name='agency_sup_country']").val($("input:text[name='agency_country']").val());
            /* Setup handlers */
            $("input:text[name='agency_address']").keyup(function(){
                $("input:text[name='agency_sup_address']").val($("input:text[name='agency_address']").val());
            });
            $("input:text[name='agency_city']").keyup(function(){
                $("input:text[name='agency_sup_city']").val($("input:text[name='agency_city']").val());
            });
            /* State might be a select or text box (for provinces) so we need to 
             * listen for change events and keyup events 
             */
            $("#internship_agency_state").change(function(){
                $("#internship_agency_sup_state").val($("#internship_agency_state").val()).change();
            });
            $("#internship_agency_state").keyup(function(){
                $("#internship_agency_sup_state").val($("#internship_agency_state").val());
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
            $("#internship_agency_state").unbind('change');
            $("#internship_agency_state").unbind('keyup');
        }
    }


    $("input:checkbox[name='copy_address']").click(function(){
        doIt(this);
    });
    /* If checkbox is set already then go ahead and copy the address to make sure. */
    if($("input:checkbox[name='copy_address']").attr('checked')){
        doIt("input:checkbox[name='copy_address']");
    }
};