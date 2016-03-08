
var EditFaculty = React.createClass({
	getInitialState: function() {
		return {
			dropData: null,
			facultyData: null,
			showTable: false,
			showModalForm: false,
			showModalSearch: true,
			errorWarning: '',
			deptNum: -1
		};
	},
	componentWillMount: function(){
		// Setting the department data in the state 
		// for the dropdown box.
		this.getData();
	},
	getData: function(){
		// Gets the dropdown data from the database.
		$.ajax({
			url: 'index.php?module=intern&action=facultyDeptRest',
			type: 'GET',
			dataType: 'json',
			success: function(data) {					
				this.setState({dropData: data});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to grab data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	getDeptFaculty: function(department_id){
		// Gets the Faculty list for the department selected by the user.
		// Sets the state for the faculty data and department Id.
		$.ajax({
			url: 'index.php?module=intern&action=getFacultyListForDept&department='+department_id,
			type: 'GET',
			dataType: 'json',
			success: function(data) {					
				this.setState({facultyData: data, deptNum: department_id});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to grab data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	onFacultyRemove: function(idNum){
		var departNum = this.state.deptNum;

		// 'Deletes' the user from the association with the designated department.
		$.ajax({
			url: 'index.php?module=intern&action=facultyDeptRest&faculty_id='+idNum+'&department_id='+departNum,
			type: 'DELETE',
			success: function() {		
				this.getDeptFaculty(departNum);
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to DELETE data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});

	},
	handleDrop: function(e) {
		// Event handler for the dropdown box, shows the table if the department is not 'select a department'
		if (e.target.value == -1)
		{
			this.setState({showTable : false, facultyData : null});
		}
		else
		{
			var department_id = e.target.value;
			this.setState({showTable : true});
			this.getDeptFaculty(department_id);
		}
		
	},
	render: function() {
		if (this.state.dropData != null)
		{		
			// Maps the dropdown department data and calls the DepartmentList class
			var dData = this.state.dropData.map(function (dept) {		    
			return (
					<DepartmentList key={dept.id}
						name={dept.name}
						id={dept.id} />
				);
			});
		}
		else
		{
			var dData = "";
		}

		if (this.state.facultyData != null)
		{		

			var facultyTable = 
					<FacultyTable 
						tableData={this.state.facultyData} 
						onFacultyRemove={this.onFacultyRemove}
						getDeptFaculty={this.getDeptFaculty} 
						deptNum={this.state.deptNum} />
			
			// ReactBoostrap Modal Trigger using a button that calls the Modal class below.
			var addFaculty = 
					<ReactBootstrap.ModalTrigger modal={<ModalForm deptNum={this.state.deptNum} getDeptFaculty={this.getDeptFaculty} />}>
		   				<ReactBootstrap.Button bsStyle='success'><i className="fa fa-user-plus"></i> Add Faculty Member</ReactBootstrap.Button>
		  			</ReactBootstrap.ModalTrigger>
	  		
		}
		else
		{
			var facultyTable = "";
			var addFaculty = "";
		}
		return (
			<div className="search">

				<div id="warningError" className="alert alert-warning alert-dismissible" role="alert" hidden>
					<button type="button"  className="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Warning!</strong> {this.state.errorWarning}
				</div>

				<div className="row">
					<div className="col-md-5">
						<h2> Faculty Members </h2>
						<div className="row">
							<div className="col-md-8">
								<label>Departments:</label>
								<select className="form-control" onChange={this.handleDrop}>
									{dData}
								</select>
							</div>
						</div>	

						<br />
						<div className="row">
							<div className="col-md-10">
								{this.state.showTable ? facultyTable : null}
							</div>
						</div>	
					</div>
					<br /><br /><br /><br />
					{this.state.showTable ? addFaculty: null}
					
				</div>
			</div>
		);
	}
});


var DepartmentList = React.createClass({
	render: function() {  
		// Creates each department in the dropdown
	    return (   	
	     	<option value={this.props.id}>{this.props.name}</option>		
	    )
	}
});


var FacultyTable = React.createClass({
	render: function() {
		if (this.props.tableData != null)
		{
			if (this.props.tableData.length > 0)
			{
				var getDeptFaculty = this.props.getDeptFaculty;
				var onFacultyRemove = this.props.onFacultyRemove;
				var deptNum = this.props.deptNum;

				// Maps the table data so that it will create a row for each faculty member
				var faculty = this.props.tableData.map(function (faculty) {		    
					return (
							<FacultyTableRow key={faculty.id}
								fname={faculty.first_name}
								lname={faculty.last_name}
								id={faculty.id} 
								onFacultyRemove={onFacultyRemove}
								getDeptFaculty={getDeptFaculty}
								deptNum={deptNum} />
						);
					});
			}
			else
			{
				// Sets the table with a message stating that there isn't data in the department.
				var faculty = <tr>
								  <td><span className="text-muted"><em>No department data exists for this department</em></span></td>
								  <td></td>
								  <td></td>
								  <td></td>
							  </tr>
			}
		}
		else
		{
			var faculty = '';
		}
		return(
			<div>
				<table className="table table-condensed table-striped">
					<thead>
						<tr>
							<th>Fullname</th>
							<th>ID</th>
							<th>Edit</th>
							<th>Remove</th>
						</tr>
					</thead>
					<tbody>
						{faculty}
					</tbody>
				</table>
			</div>

		);
	}
});


var FacultyTableRow = React.createClass({
	handleRemove: function() {
		this.props.onFacultyRemove(this.props.id);
	},
	handleEdit: function() {
		//this.props.onFacultyRemove(this.props.id);
	},
	render: function() { 
		// Creates each row for the name, banner ID, a button with a trigger for modal to edit, and a delete button.
		return (
			<tr>
				<td>{this.props.fname} {this.props.lname}</td>
				<td>{this.props.id}</td>
				<td><ReactBootstrap.ModalTrigger modal={<ModalForm  edit={true} 
																 	id={this.props.id} 
																 	getDeptFaculty={this.props.getDeptFaculty} 
																 	deptNum={this.props.deptNum} />}>
					<a onClick={this.handleEdit}><i className="fa fa-pencil-square-o" /></a></ReactBootstrap.ModalTrigger></td>
				<td><a onClick={this.handleRemove}><i className="fa fa-trash-o" /></a></td>
			</tr>	
		);
	}
});		


// Modal Form used to display the information of the Faculty members.
// !This uses ReactBoostrap!
var ModalForm = React.createClass({
	getInitialState: function() {
		return {
			facultyData: null,
			showModalForm: false,
			showModalSearch: true,
			formData: {
				id: '',
				username: '',
				first_name: '',
				last_name: '',
				phone: '',
				fax: '',
				street_address1: '',
				street_address2: '',
				city: '',
				state: '',
				zip: '',
			}
		};
	},
	componentWillMount: function() {
		//Used for editing a user (see edit handler).
		//Disables/enables modal form and then grabs and displays the data.
		if (this.props.edit == true)
		{
			this.setState({showModalForm: true, showModalSearch: false})
			this.getData(this.props.id);
		}
	},
	shouldComponentUpdate: function(nextProps, nextState){
		return this.state.formData !== nextState.formData 
			|| this.state.showModalForm !== nextState.showModalForm 
			|| this.state.showModalSearch !== nextState.showModalSearch
			|| this.state.facultyData !== nextState.facultyData;
	},
	getData: function(idNum){
		// Grabs the facuitly data from restFacultyById and sets the 
		// data to the state as well as setting the modal form to true and false.
		$.ajax({
			url: 'index.php?module=intern&action=restFacultyById&id='+idNum,
			type: 'GET',
			dataType: 'json',
			success: function(data) {					
				this.setState({facultyData: data, showModalSearch: false, showModalForm: true});

				// This function sets the faculty information into the sub-object in the state.
				this.applyFaculty();
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to grab faculty data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	postData: function() {
		var idNum = this.state.formData.id;
		var departNum = this.props.deptNum;

		// Connects the faculty member and the department together.
		$.ajax({
			url: 'index.php?module=intern&action=facultyDeptRest&faculty_id='+idNum+'&department_id='+departNum,
			type: 'POST',
			success: function() {		
				this.props.getDeptFaculty(departNum);
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to POST data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	handleSearch: function() {
		var idNumber = React.findDOMNode(this.refs.bannerID).value.trim();

		// if the banner id is equal to 9, call getData which finds the user
		// in the database.
		// THIS NEEDS TO BE FIXED FOR THE BANNER ID SERVER
		if (idNumber.length == 9)
		{
			this.getData(idNumber);
		}
	},
	applyFaculty: function() {
		//Sets the user information to the sub-objects in the state.

		var update = React.addons.update;
		var data = update(this.state.formData, 
		{
			id: 			 {$set: this.state.facultyData.id},
			username: 		 {$set: this.state.facultyData.username},
			first_name: 	 {$set: this.state.facultyData.first_name},
			last_name: 		 {$set: this.state.facultyData.last_name},
			phone: 			 {$set: this.state.facultyData.phone},
			fax: 			 {$set: this.state.facultyData.fax},
			street_address1: {$set: this.state.facultyData.street_address1},
			street_address2: {$set: this.state.facultyData.street_address2},
			city: 			 {$set: this.state.facultyData.city},
			state: 			 {$set: this.state.facultyData.state},
			zip: 			 {$set: this.state.facultyData.zip}
		});
		
		this.setState({formData: data});
	},
	handleSave: function() {
		// Saves the faculty data.
		$.ajax({
			url: 'index.php?module=intern&action=restFacultyById',
			type: 'PUT',
			processData: false,
			data: JSON.stringify(this.state.formData),
			success: function() {
				// Calls the postData and then closes the Modal Form.
				this.postData();
				this.props.onRequestHide();
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to PUT data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	handleChangeForm: function(event) {
		// Determines which event handler was used and updates the state.
		var update = React.addons.update;
		
		switch(event.target.id) {
			case "faculty-edit-phone":
				var data = update(this.state.formData, {
					phone: {$set: event.target.value}
				});
				break;
			case "faculty-edit-fax":
				var data = update(this.state.formData, {
					fax: {$set: event.target.value}
				});
				break;
			case "faculty-edit-street_address1":
				var data = update(this.state.formData, {
					street_address1: {$set: event.target.value}
				});
				break;
			case "faculty-edit-street_address2":
				var data = update(this.state.formData, {
					street_address2: {$set: event.target.value}
				});
				break;	
			case "faculty-edit-city":
				var data = update(this.state.formData, {
					city: {$set: event.target.value}
				});
				break;
			case "faculty-edit-state":
				var data = update(this.state.formData, {
					state: {$set: event.target.value}
				});
				break;
			case "faculty-edit-zip":
				var data = update(this.state.formData, {
					zip: {$set: event.target.value}
				});
				break;
		};

		this.setState({formData: data});
	},
  	render: function() {
  		// Modal Form used to show and update the data. This could look better...
  		var modalForm = 
  			<div className="row">	
				<div className="col-md-offset-1 col-md-10">			
				    Banner ID: 
			        <input type="text" className="form-control" id="faculty-edit-id" value={this.state.formData.id} /> <br />

				      
				    Username:   
			        <input type="text" className="form-control"  id="faculty-edit-username" value={this.state.formData.username} /> <br />

			    	<div className="row">
			    		<div className="col-md-6">
						    First Name: 
					        <input type="text" className="form-control" id="faculty-edit-first_name" value={this.state.formData.first_name} /> 
				        </div>
				      	<div className="col-md-6">  
						    Last Name:  
						    <input type="text" className="form-control" id="faculty-edit-last_name" value={this.state.formData.last_name} />
					    </div> 
				    </div>
				       
				    <br />

				    <div className="row">
			    		<div className="col-md-6">
						    Phone:      
						    <input type="text" className="form-control" id="faculty-edit-phone" value={this.state.formData.phone} onChange={this.handleChangeForm} /> 
					    </div>
						<div className="col-md-6">       
						    Fax:        
						    <input type="text" className="form-control" id="faculty-edit-fax" value={this.state.formData.fax} onChange={this.handleChangeForm} /> 
					    </div>
				    </div>
				    <br />
				    Address:    
				    <input  type="text" className="form-control" id="faculty-edit-" value={this.state.formData.street_address1} onChange={this.handleChangeForm} /> <br />
			       	
			       	
			       	<input type="text" className="form-control" id="faculty-edit-street_address2" value={this.state.formData.street_address2} onChange={this.handleChangeForm} /> <br />
						
				       
				    City:       
				    <input type="text" className="form-control" id="faculty-edit-city" value={this.state.formData.city} onChange={this.handleChangeForm} /> <br />

				        
				    State:      
				    <input type="text" className="form-control" id="faculty-edit-state" value={this.state.formData.state} onChange={this.handleChangeForm} /> <br />

				       
				    Zip:        
				    <input type="text" className="form-control" id="faculty-edit-zip" value={this.state.formData.zip} onChange={this.handleChangeForm} /> <br />

      				<ReactBootstrap.Button bsStyle='primary' onClick={this.handleSave}>Save Changes</ReactBootstrap.Button>
				</div>
			</div>

		// Search bar for inputting the Banner ID
	    var searchBanner = 
	    	<div>
				<input type="text" className="form-control" ref="bannerID" onChange={this.handleSearch} /> 
				<br />
			   	<ReactBootstrap.Button onClick={this.handleSearch}>Submit</ReactBootstrap.Button>
		   	</div>
    return (
      <ReactBootstrap.Modal {...this.props} title='Add A Faculty Member' animation={true}>
        <div className='modal-body'>

        	{this.state.showModalSearch ? searchBanner: null}	
			{this.state.showModalForm ? modalForm: null}

        </div>
        <div className='modal-footer'>
          <ReactBootstrap.Button onClick={this.props.onRequestHide}>Close</ReactBootstrap.Button>
        </div>
      </ReactBootstrap.Modal>
    );
  }
});

React.render(
	<EditFaculty />,
	document.getElementById('content')
);
