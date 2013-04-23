<script type="text/template" id="faculty-template">
    <li>
        <%= first_name %> <%= last_name %> - <%= banner_id %>
    </li>
</script>

<script type="text/javascript" src="{source_http}mod/intern/javascript/facultyEdit/faculty.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#facultyEdit_department_drop").bind('change', handleDepartmentChange);
        
        // Setup the new faculty member dialog
        $("#new-faculty-dialog").dialog({
        	title: 'Add a Faculty Member',
        	autoOpen: false,
        	modal: true,
        	buttons: [
        	          {text: "Add",
        	        	  click: function(){
        	        		  alert('ohh hai');
        	        	  }
        	          },
        	          {text: "Cancel",
        	        	  click: function(){
        	        		  $(this).dialog('close');
        	        	  }
        	          }
        	          ]
        });
        
        $("#add-button").bind('click', function(){
        	$("#new-faculty-dialog").dialog('open');
        });
    });
    
    var crap = new FacultyListView();
    
    function handleDepartmentChange(event)
    {	
    	crap.el = $("#facultyList");
    	crap.collection.department = $("#facultyEdit_department_drop").val();
    	crap.collection.fetch();
    }
</script>