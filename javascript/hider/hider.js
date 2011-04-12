/*
 * Semaphore
 *
 *   Control access to a resource.  Only allow a limited number of
 * users to acquire a resource at a given time.
 *
 * @author Daniel West <dwest at tux dot appstate dot edu>
 * @package hms
 * @subpackage javascript
 */
var Semaphore = function(count){
    this.count = count;
    this.owner;

    this.acquire = function(owner){
        if(this.count > 0){
            this.count--;
            this.owner = owner;
            return true;
        }
        return false;
    }

    this.steal = function(theif){
        // Just try to steal it, not from anyone
        // in particular
        result = this.acquire(theif);
        if(!result){
            this.owner.hide()
            this.release();
            this.acquire(theif);
            return true;
        }
    }

    this.release = function(){
        this.count++;
        this.owner = null;
        return true;
    }
}

/**
 * A Hider is created for each row in the search results. 
 * If the row is clicked then show the details of the
 * internship.  Details are loaded when the Hider is constucted.
 */
var Hider = function(id, semaphore){
    this.id = id;
    this.select = "#"+this.id;
    this.dSelect = this.select+"-details";
    this.semaphore = semaphore;
    this.detailHTML = null;
    this.open = false;
    var me = this;

    // Show the details for this internship
    // and keep the row highlighted.
    this.show = function(){
        $(this.dSelect).slideDown('fast');
        $(this.select).css('background', '#F2F2F2');
        this.open = true;
    }
    
    // Hide the details for this internship
    // and remove the highlighting.
    this.hide = function(){
        $(this.select).css('background', '#FFFFFF');
        $(this.dSelect).slideUp('fast');
        this.open = false;
    }

    $(this.select).mouseenter(function(){
        // Show gray background when mouse if over row
        $(me.select).css('background', '#F2F2F2');
        $(me.select).css('cursor', 'pointer');
    });

    $(this.select).mouseleave(function(){
        // If this hider is opened then keep it highlighted.
        if(!me.open){
            // Show white background when mouse if over row
            $(me.select).css('background', '#FFFFFF');
        }
    });
    
    $(this.select).click(function(){
        // If this row is the open one and is clicked 
        // again then close it and release the semaphore.
        if(me.semaphore.owner == me){
            me.hide();
            me.semaphore.release();
            return;
        }else{
            // Someone else is open...
            me.semaphore.steal(me);
            me.show();
        }
    });

    // Hide content div and load it with data.
    this.hide();
    $.get('index.php', {'module' : 'intern', 'action' : 'internship_details', 'id' : me.id },
          function(data){
              if(!data){
                  console.log("Error fetching internship details for "+$("#"+me.id+">td:first-child").text().trim()+".");
              }else{
                  $(me.dSelect).html(data);
              }
          });
}
