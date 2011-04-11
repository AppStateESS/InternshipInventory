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


var Hider = function(id, semaphore){
    this.id = id;
    this.semaphore = semaphore;
    this.detailHTML = null;
    var me = this;

    $("#"+this.id).mouseenter(function(){
        // Show gray background when mouse if over row
        $("#"+me.id).css('background', '#E3E3E3');
        $("#"+me.id).css('cursor', 'pointer');
    });

    $("#"+this.id).mouseleave(function(){
        // Show white background when mouse if over row
        $("#"+me.id).css('background', '#FFFFFF');
    });
    
    $("#"+this.id).click(function(){
        me.semaphore.steal(me);
        me.show();
    });

    // Show the details for this internship.
    this.show = function(){
        $("#"+this.id+"+div").slideDown('fast');
    }
    
    // Hide the details for this internship.
    this.hide = function(){
        $("#"+this.id+"+div").slideUp('fast');
    }

    // Add content div, load it up, and hide it.
    $("#"+this.id).after("<div colspan='5'></div>");
    $("#"+this.id+"+div").css('margin-bottom', '10px');
    this.hide();
    $.get('index.php', {'module' : 'intern', 'action' : 'internship_details', 'id' : me.id },
          function(data){
              if(!data){
                  console.log("Error fetching internship details for "+$("#"+me.id+">td:first-child").text().trim()+".");
              }else{
                  $("#"+me.id+"+div").html(data);
              }
          });

}
