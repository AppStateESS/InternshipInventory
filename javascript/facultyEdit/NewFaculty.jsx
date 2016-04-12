
/**
 * Search form for looking up faculty by banner id.
 */
var BannerSearch = React.createClass({
    handleSearch: function(){
        var bannerId = this.refs.bannerId.value.trim();

        // TODO: use a regular exp to make sure it's nine digits, not just nine characters
        if(bannerId.length == 9) {
            this.props.handleSearch(bannerId);
        }else{
            // TODO: show an error alert for invalid format (banner ID must be nine digits)
            this.props.showNotification("Invalid banner ID format: ID must be nine digits");
        }
    },
    onKeyPress: function(event){
        // Capture the enter key and activate searching
        if(event.charCode == 13){
            this.handleSearch();
        }
    },
    render: function(){
        return (
            <div>
                <div className="form-group">
                    <label htmlFor="bannerId-search">Faculty Member's Banner ID</label>
                    <input type="text" id="bannerId-search" className="form-control" ref="bannerId" placeholder="Banner ID" onKeyPress={this.onKeyPress} />
                </div>
                <div className="form-group pull-right">
                    <ReactBootstrap.Button onClick={this.handleSearch}>Search</ReactBootstrap.Button>
                </div>
                <br /><br />
            </div>
        );
    }
});


/**
 * Form for editing faculty details
 */
var FacultyForm = React.createClass({
    // Event handler for Save button. Captures the data and passes
    // it as an object to the parent's handleSave() method.
    handleSave: function() {
        this.props.handleSave({id: this.props.facultyData.id,
                            username: this.props.facultyData.username,
                            first_name: this.refs.facultyEditFirstName.value,
                            last_name: this.refs.facultyEditLastName.value,
                            phone: this.refs.facultyEditPhone.value,
                            fax: this.refs.facultyEditFax.value,
                            street_address1: this.refs.facultyEditStreetAddress1.value,
                            street_address2: this.refs.facultyEditStreetAddress2.value,
                            city: this.refs.facultyEditCity.value,
                            state: this.refs.facultyEditState.value,
                            zip: this.refs.facultyEditZip.value
                        });
    },
    render: function(){
        return (
            <div className="row">
				<div className="col-md-offset-1 col-md-10">

					<div className="row">
			    		<div className="col-md-6">
			    			<div className="form-group">
		                        <label htmlFor="faculty-edit-id">Banner ID:&nbsp;</label>
		                        <span className="banner-id">{this.props.facultyData.id}</span>
		                    </div>
			    		</div>
						<div className="col-md-6">
							<div className="form-group">
		                        <label className="control-label" htmlFor="faculty-edit-username">Username:&nbsp;</label>
					            <span className="username">{this.props.facultyData.username}</span>
		                    </div>
			    		</div>
				    </div>

			    	<div className="row">
			    		<div className="col-md-6">
						    <label htmlFor="faculty-edit-first_name">First Name</label>
					        <input type="text" className="form-control" id="faculty-edit-first_name" ref="facultyEditFirstName" defaultValue={this.props.facultyData.first_name} />
				        </div>
				      	<div className="col-md-6">
						    <label htmlFor="faculty-edit-last_name">Last Name</label>
						    <input type="text" className="form-control" id="faculty-edit-last_name" ref="facultyEditLastName" defaultValue={this.props.facultyData.last_name} />
					    </div>
				    </div>

				    <div className="row">
			    		<div className="col-md-6">
						    <label htmlFor="faculty-edit-phone">Phone</label>
						    <input type="text" className="form-control" id="faculty-edit-phone" ref="facultyEditPhone" defaultValue={this.props.facultyData.phone} />
					    </div>
						<div className="col-md-6">
						    <label htmlFor="faculty-edit-fax">Fax</label>
						    <input type="text" className="form-control" id="faculty-edit-fax" ref="facultyEditFax" defaultValue={this.props.facultyData.fax} />
					    </div>
				    </div>


                    <div className="form-group">
				        <label htmlFor="faculty-edit-street_address1">Address</label>
				        <input  type="text" className="form-control" id="faculty-edit-street_address1" ref="facultyEditStreetAddress1" defaultValue={this.props.facultyData.street_address1} />
                    </div>

                    <div className="form-group">
                        <label htmlFor="faculty-edit-street_address2">Address Line 2</label>
                        <input type="text" className="form-control" id="faculty-edit-street_address2" ref="facultyEditStreetAddress2" defaultValue={this.props.facultyData.street_address2} />
                    </div>

                    <div className="form-group">
                        <label htmlFor="faculty-edit-city">City</label>
                        <input type="text" className="form-control" id="faculty-edit-city" ref="facultyEditCity" defaultValue={this.props.facultyData.city} />
                    </div>

                    <div className="form-group">
                        <label htmlFor="faculty-edit-state">State</label>
                        <input type="text" className="form-control" id="faculty-edit-state" ref="facultyEditState" defaultValue={this.props.facultyData.state} />
                    </div>

                    <div className="form-group">
                        <label htmlFor="faculty-edit-zip">Zip</label>
                        <input type="text" className="form-control" id="faculty-edit-zip" ref="facultyEditZip" defaultValue={this.props.facultyData.zip} />
                    </div>

      				<ReactBootstrap.Button bsStyle='primary' onClick={this.handleSave}>Save Changes</ReactBootstrap.Button>
				</div>
			</div>
        );
    }
});

// Modal Pop-up for adding/editing faculty members
// !This uses ReactBoostrap!
var FacultyModal = React.createClass({
	getInitialState: function() {
		return {
			errorWarning: '',
			showModalNotification: false,
			showModalForm: false,
			showModalSearch: true,
		};
	},
	componentWillMount: function() {
		// Used for editing a user (see edit handler).
		// Disables/enables modal form and then grabs and displays the data.
		if (this.props.edit == true)
		{
			this.setState({showModalForm: true, showModalSearch: false})
			this.props.getFacultyDetail(this.props.id);
		}
	},
    clearStateAndHide: function() {
        this.setState(this.getInitialState());
        this.props.hide();
    },
	addFacultyToDept: function(facultyData) {
        // Connects the faculty member and the department together.

		var idNum = facultyData.id;
		var departNum = this.props.deptNum;

		$.ajax({
			url: 'index.php?module=intern&action=facultyDeptRest&faculty_id='+idNum+'&department_id='+departNum,
			type: 'POST',
			success: function() {
				this.props.getDeptFaculty(departNum);
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Sorry, looks like something went wrong. We couldn't save your changes.");
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	},
	handleSearch: function(bannerId) {
		this.setState({errorWarning:''});
		this.props.getFacultyDetails(bannerId);
	},
	handleSave: function(facultyData) {
		// Saves the faculty data.
		$.ajax({
			url: 'index.php?module=intern&action=restFacultyById',
			type: 'PUT',
			processData: false,
            dataType: 'json',
			data: JSON.stringify(facultyData),
			success: function(data) {
				// Calls addFacultyToDept() and then closes the Modal Form.

                // If we're not editing this faculty, then we're adding a new faculty
                // So we need to create the faculty -> department association
                if(!this.props.edit){
				    this.addFacultyToDept(data);
                }

                if(this.props.edit){
 					var departNum = this.props.deptNum;
					this.props.getDeptFaculty(departNum);
              
                    this.props.hide();
                } else {
                    this.clearStateAndHide();
                }

			}.bind(this),
			error: function(xhr, status, err) {
				alert("Sorry, looks like something went wrong. We couldn't save your changes.");
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	},
	showNotification: function(msg){
		this.setState({errorWarning:msg});
	},
    render: function() {
    	// Warning notification for invalid Banner size or Banner ID
    	var notification = <div className="alert alert-warning" role="alert">{this.state.errorWarning != '' ? this.state.errorWarning: this.props.errorWarning}</div>

        // Search bar for inputting the Banner ID
	    var searchBanner = <BannerSearch handleSearch={this.handleSearch} showNotification={this.showNotification}/>;

        // Modal Form used to show and update the data. This could look better...
        var modalForm = <FacultyForm facultyData={this.props.facultyData} handleSave={this.handleSave}/>

        if(this.props.edit){
            var title = 'Edit Faculty Details';
            var onHideMethod = this.props.hide;
        } else {
            var title = 'Add a Facutly Member';
            var onHideMethod = this.clearStateAndHide;
        }

        return (
          <ReactBootstrap.Modal show={this.props.show} onHide={onHideMethod} animation={true} backdrop='static'>
            <ReactBootstrap.Modal.Header closeButton>
                <ReactBootstrap.Modal.Title>{title}</ReactBootstrap.Modal.Title>
            </ReactBootstrap.Modal.Header>


            <ReactBootstrap.Modal.Body>
            	{this.state.errorWarning != '' || this.props.errorWarning != '' ? notification: null}
            	{this.props.showModalSearch ? searchBanner: null}
    			{this.props.showModalForm ? modalForm: null}
            </ReactBootstrap.Modal.Body>


            <ReactBootstrap.Modal.Footer>
                <ReactBootstrap.Button onClick={this.clearStateAndHide}>Close</ReactBootstrap.Button>
            </ReactBootstrap.Modal.Footer>
          </ReactBootstrap.Modal>
        );
    }
});



var FacultyTableRow = React.createClass({
    getInitialState: function() {
        return {showModal: false,
        		userData: 	   null,
        		showModalSearch: false,
        		showModalForm: true,
        		errorWarning: ''};
    },
    getFacultyDetails: function(idNum){
		// Grabs the facuitly data from restFacultyById and sets the
		// data to the state as well as setting the modal form to true and false.
		$.ajax({
			url: 'index.php?module=intern&action=restFacultyById&id=' + idNum,
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				this.setState({userData: data});
	
			}.bind(this),
			error: function(xhr, status, err) {
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	},
	handleRemove: function() {
		this.props.onFacultyRemove(this.props.id);
	},
	handleEdit: function() {
		if (this.state.userData == null)
		{
			this.getFacultyDetails(this.props.id);
		}
		this.setState({showModal: true});
	},
    hideModal: function() {
        this.setState({showModal: false});
    },
	render: function() {
		// Creates each row for the name, banner ID, a button with a trigger for modal to edit, and a delete button.
		return (
			<tr>
				<td>{this.props.fname} {this.props.lname}</td>
				<td>{this.props.id}</td>
				<td><a onClick={this.handleEdit}><i className="fa fa-pencil-square-o" /></a></td>
				<td><a onClick={this.handleRemove}><i className="fa fa-trash-o" /></a></td>
                <td>
                    <FacultyModal  show={this.state.showModal}
                                hide={this.hideModal}
                                edit={true}
                                deptNum={this.props.deptNum}
        				        id={this.props.id}
        				        facultyData={this.state.userData}
        				        showModalSearch={this.state.showModalSearch}
        						showModalForm={this.state.showModalForm}
        						getFacultyDetail={this.getFacultyDetails}
        						errorWarning={this.state.errorWarning} 
        						getDeptFaculty = {this.props.getDeptFaculty} />
                </td>
			</tr>
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
		if (this.props.tableData != null) {
			if (this.props.tableData.length > 0) {
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
			} else {
				// Sets the table with a message stating that there isn't data in the department.
				var faculty = <tr>
								  <td colSpan="4"><span className="text-muted"><em>No department data exists for this department</em></span></td>
							  </tr>
			}
		} else {
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




var EditFaculty = React.createClass({
	getInitialState: function() {
		return {
			dropData: null,
			deptData: null,
			facultyData: null,
			showTable: false,
			showModalForm: false,
			showModalSearch: true,
			errorWarning: '',
			deptNum: -1,
            showPopup: false
		};
	},
	componentWillMount: function(){
		// Setting the department data in the state
		// for the dropdown box.
		this.getData();
	},
	getData: function(){
		// Gets the list of departments which the current user has access to
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
				this.setState({deptData: data, deptNum: department_id});
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
	getFacultyDetails: function(idNum){
		// Grabs the facuitly data from restFacultyById and sets the
		// data to the state as well as setting the modal form to true and false.
		$.ajax({
			url: 'index.php?module=intern&action=restFacultyById&id=' + idNum,
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				var warning = '';
				this.setState({facultyData: data, showModalSearch: false, showModalForm: true, errorWarning:warning});
			}.bind(this),
			error: function(xhr, status, err) {
                if(xhr.status == 404){
                    // Handle the case where we couldn't find anyone with that banner ID
                    var warning = "We couldn't find anyone with that Banner ID.";
                    this.setState({errorWarning:warning})
                    return;
                }
				var warning = "Sorry, we couldn't load the details for that faculty member.";
				this.setState({errorWarning:warning})
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
    showModal: function(){
        this.setState({showPopup: true, showModalSearch: true, showModalForm: false});
    },
    hideModal: function() {
        this.setState({showPopup: false});
    },
	render: function() {
		if (this.state.dropData != null) {
			// Maps the dropdown department data and calls the DepartmentList class
			var dData = this.state.dropData.map(function (dept) {
			return (
					<DepartmentList key={dept.id}
						name={dept.name}
						id={dept.id} />
				);
			});
		} else {
			var dData = "";
		}

		if (this.state.deptData != null) {

			var facultyTable =
					<FacultyTable
						tableData={this.state.deptData}
						onFacultyRemove={this.onFacultyRemove}
						getDeptFaculty={this.getDeptFaculty}
						deptNum={this.state.deptNum} />

			// ReactBoostrap Modal Trigger using a button that calls the Modal class below.
			var addFaculty = <button className="btn btn-success" onClick={this.showModal}><i className="fa fa-user-plus"></i> Add Faculty Member</button>

		} else {
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

                <FacultyModal show={this.state.showPopup} hide={this.hideModal} deptNum={this.state.deptNum} getDeptFaculty={this.getDeptFaculty} 
                			   showModalSearch={this.state.showModalSearch} showModalForm={this.state.showModalForm} facultyData={this.state.facultyData} 
                			   getFacultyDetails={this.getFacultyDetails} errorWarning={this.state.errorWarning}/>
			</div>
		);
	}
});



ReactDOM.render(
	<EditFaculty />,
	document.getElementById('content')
);
