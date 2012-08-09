<script type="text/javascript">
    $(document).ready(function(){
        /**
         * Autocomplete for phpWebsite usernames.
         */
        var box = $("#add_admin_username");
        $(box).autocomplete({ source: 'index.php?module=intern&action=edit_admins&user_complete=1' });

    });
</script>