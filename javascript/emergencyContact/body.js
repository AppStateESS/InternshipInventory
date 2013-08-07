<script type="text/javascript">


$(function(){
    $(document).ready(function(){
        console.log($("#emergency-contact-list"));
        //$("#emergency-contact-list").EmgContactList();
        $("#emergency-contact-list").EmgContactList('init',{existing_contacts_json}); 
    });
});

</script>