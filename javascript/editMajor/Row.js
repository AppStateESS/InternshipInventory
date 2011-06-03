function Row(nameSelect, id, editAction){
    this.editAction = editAction;
    this.select = nameSelect;
    this.editMode = false;
    this.id = id;
    this.edit = $("<input type='text'/>");
    this.saveLink = $("<input type='button' value='Save'/>");
    this.editLink = $("#edit-"+this.id);
    var me = this;

    this.save = function(){
        /**
         * Save was clicked. Send new name to server.
         * Set <span>'s text to new name;
         */
        $.get('index.php', {'module':'intern', 'action':this.editAction,
                            'rename':$(this.edit).val(), 'id':this.id, 'ajax':true},
              function(data){
                  /* Reload page so NQ is shown and pager is re-ordered. */
                  window.location = 'index.php?module=intern&action='+me.editAction;
              });
        $(this.saveLink).slideUp('fast');
        $(this.edit).slideUp('fast', function(){
            $(me.select).slideDown('fast');
            $(me.select).text($(this).val());
        });
    };

    this.toggleEdit = function(){
        /* If editing and the Edit link is clicked again. Hide the text box 
         * and do not save the new name.
         */
        if(!this.editMode){
            this.editMode = true;
            /**
             * Hide the <span> containing the Major/Program.
             * Put a text-box there with the current name of Major/Program
             * so it can be edited. Set up event handlers on text-box.
             */
            $(this.select).slideUp('fast', function(){
                $(me.edit).val($(me.select).text());
                $(me.edit).slideDown('fast');
                $(me.saveLink).slideDown('fast');
            });

            /* Select all text when text-box is clicked */
            $(this.edit).click(function(){
                $(this).focus();
                $(this).select();
            });

            /* If ENTER is pressed submit the new name */
            $(this.edit).keypress(function(event){
                /* 13 == ENTER */
                if(event.charCode == 13){
                    me.save();
                }
            });
        }else{
            this.editMode = false;
            $(this.saveLink).slideUp('fast');
            $(this.edit).slideUp('fast', function(){
                $(me.select).slideDown('fast');
                $(me.select).text($(this).val());
            });
        }
    };

    /* Add the text-box and hide it initially. Append save link to text-box. */
    $(this.select).after(this.edit);
    $(this.edit).hide();
    $(this.saveLink).hide();
    $(this.edit).after(this.saveLink);

    /* Set up click handler */
    $(this.editLink).click(function(){
        me.toggleEdit();
    });

    $(this.saveLink).click(function(){
        me.save();
    });
}