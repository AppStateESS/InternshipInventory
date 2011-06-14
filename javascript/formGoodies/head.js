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

        /*
         * If domestic is selected show the state and zip code inputs for agency.
         */
        var domesticClick = function(){
            // Make sure state and zip are required.
            $("#internship_agency_zip,#internship_agency_state,"+
               "#internship_agency_sup_zip,#internship_agency_sup_state").addClass('input-required');

            // Show zip and state.
            $("#internship_agency_zip,#internship_agency_state,"+
               "#internship_agency_sup_zip,#internship_agency_sup_state").show();
            $("#internship_agency_zip,#internship_agency_state,"+
              "#internship_agency_sup_zip,#internship_agency_sup_state").parent().siblings().show();

            // Remove requirement class from country (agency and supervisor)
            $("#internship_agency_country,#internship_agency_sup_country").removeClass('input-required');
            // Hide countrys
            $("#internship_agency_country,#internship_agency_sup_country").hide();
            // Hide labels too.
            $("#internship_agency_country,#internship_agency_sup_country").parent().siblings().hide();
        };

        /**
         * If internat is selected: show country, hide state and zip. Add required flag to country.
         */
        var internatClick = function(){
            // Remove required class from zip/state.
            $("#internship_agency_zip,#internship_agency_state,"+
               "#internship_agency_sup_zip,#internship_agency_sup_state").removeClass('input-required');

            // Hide zip and state.
            $("#internship_agency_zip,#internship_agency_state,"+
               "#internship_agency_sup_zip,#internship_agency_sup_state").hide();
            $("#internship_agency_zip,#internship_agency_state,"+
               "#internship_agency_sup_zip,#internship_agency_sup_state").parent().siblings().hide();

            // Add requirement class from country (agency and supervisor)
            $("#internship_agency_country,#internship_agency_sup_country").addClass('input-required');
            // Show countrys
            $("#internship_agency_country,#internship_agency_sup_country").show();
            // Show labels too.
            $("#internship_agency_country,#internship_agency_sup_country").parent().siblings().show();
        };

        /* Attach above function to click event */
        $("input:radio[name=location][value=domestic]").click(function(){ domesticClick(); });

        /* Attach above function to click event */
        $("input:radio[name=location][value=internat]").click(function(){ internatClick(); });
        
        // If domestic is checked initially then do setup...
        if($("input:radio[name=location][value=domestic]").attr('checked')){
            domesticClick();
        }

        // If internat is checked initially then do setup...
        if($("input:radio[name=location][value=internat]").attr('checked')){
            internatClick();
        }

    });
</script>