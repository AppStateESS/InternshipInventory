<script type="text/javascript">
    $(document).ready(function(){
        /* If the checkbox to grant an admin 
         * access to all departments disable
         * dept. drop down
         */
        $("#all-departments-check>input").click(function(){
            if($(this).attr('checked')){
                /* Disabled drop down */
                $("select[name=department_id]").attr('disabled', true).val(-1);
            }else{
                /* Enable drop down */
                $("select[name=department_id]").attr('disabled', false);
            }
        });

        /**
         * Autocomplete for phpWebsite usernames.
         */
        var box = $("#add_admin_username");
        $(box).autocomplete({ source: 'index.php?module=intern&action=edit_admins&user_complete=1' });

    });
</script>