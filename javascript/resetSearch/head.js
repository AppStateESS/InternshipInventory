<script type="text/javascript">
    $(document).ready(function(){
        $("#reset-search").click(function(){
            /* Clear all search fields */
            $("input[name='name']").val("");
            $("select[name='term_select[]']").val("");
            $("select[name='dept[]']").val("");
        });
    });
</script>