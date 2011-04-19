/**
 * A Hider is created for each row in the search results. 
 * If the row is clicked then show the details of the
 * internship.  Details are loaded when the Hider is constucted.
 */
var Hider = function(id){
    this.id = id;
    this.select = "#"+this.id;
    this.dSelect = this.select+"-details";
    this.detailHTML = null;
    this.open = false;
    var me = this;

    // Show the details for this internship
    // and keep the row highlighted.
    this.show = function(){
        $(this.dSelect).slideDown('fast');
        $(this.select).css('background', '#E2E2E2');
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
        $(me.select).css('background', '#E2E2E2');
        $(me.select).css('cursor', 'pointer');
    });

    $(this.select).mouseleave(function(){
        // If this hider is opened then keep it highlighted.
        if(!me.open){
            // Show white background when mouse if over row
            $(me.select).css('background', '#FFFFFF');
        }
    });
    
    // Show details if row is clicked...ignore if the 'Edit' link is clicked.
    $(this.select).click(function(){
        // If row is open and is clicked then close it.
        if(me.open){
            me.hide();
        }else{
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
