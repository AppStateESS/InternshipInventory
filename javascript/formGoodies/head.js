<script type="text/javascript">
    $(document).ready(function(){
        /* PAYMENT 
         * If Paid is selected then make stipend selectable.
         */
        $("input:radio[value='paid']").click(function(){
            $("input:checkbox[name='stipend']").attr('disabled', false);
        });
        /* If unpaid is selected uncheck stipend and make it unselectable */
        $("input:radio[value='unpaid']").click(function(){
            $("input:checkbox[name='stipend']").attr('disabled', true);
            $("input:checkbox[name='stipend']").attr('checked', false);
        });
        
        /* Check whether to set stipend as disabled */
        if($("input:radio[value='paid']").attr('checked')){
            $("input:checkbox[name='stipend']").attr('disabled', false);
        }else{
            $("input:checkbox[name='stipend']").attr('disabled', true);
        }
            
        /* 'OTHER' INTERNSHIP TYPE 
         * If checkbox beside the 'Other type' text-box is selected then
         * enable the text-box 
         */
        $("input:checkbox[name='check_other_type']").click(function(){
            if($(this).attr('checked')){
                $("input:text[name='other_type']").attr('disabled', false);
            }else{
                $("input:text[name='other_type']").attr('disabled', true);
            }
        });
        /* Should the text box be initialized to disabled? */
        if($("input:checkbox[name='check_other_type']").attr('checked')){
            $("input:text[name='other_type']").attr('disabled', false);
        }else{
            $("input:text[name='other_type']").attr('disabled', true);
        }
    });
</script>