<script type="text/javascript">
    $(document).ready(function(){
        /* On mouseover: underline text. Adds affordance for clicking. */
        $(".intern-button").hover(function(){ $(this).css('text-decoration', 'underline'); },
                                  function(){ $(this).css('text-decoration', 'none');      });

        /* Make button look like it's been pressed. */
        $(".intern-button").mousedown(function() {
            $(this).css('border', '2px solid #A0A0A0');
            $(this).css('border-top', '2px solid black');
            $(this).css('border-left', '2px solid black');
        });

        /* "Release" button */
        $(".intern-button").mouseup(function() {
            $(this).css('border', '2px solid black');
            $(this).css('border-top', '2px solid #A0A0A0');
            $(this).css('border-left', '2px solid #A0A0A0');
        });
        $(".intern-button").mouseleave(function() {
            $(this).css('border', '2px solid black');
            $(this).css('border-top', '2px solid #A0A0A0');
            $(this).css('border-left', '2px solid #A0A0A0');
        });

        /* Redirect user to appropriate page. */
        $(".intern-button#search").click(function(){
            window.location = "index.php?module=intern&action=search";
        });
        $(".intern-button#add").click(function(){
            window.location = "index.php?module=intern&action=edit_internship";
        });

    });
</script>