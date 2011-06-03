<script type="text/javascript" src="javascript/modules/intern/editMajor/Row.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        /* Act like a link */
        $(".edit-major-prog").hover(function(){ $(this).css('text-decoration', 'underline'); },
                                    function() { $(this).css('text-decoration', 'none'); });
        
        /* Create new Row object for each row! */
        $(".edit-major-prog").each(function(){
            /* Below returns { ID , "edit-major-prog" } */
            var classes = $(this).attr('class').split(/\s/);
            var id = classes[0];
            var nameSelect = ".major-prog#"+id;

            new Row(nameSelect, id, "{EDIT_ACTION}");
        });
    });
</script>