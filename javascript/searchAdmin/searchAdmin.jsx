
var SearchAdmin = React.createClass({
	getInitialState: function() {
		return {
			mainData: null,
			displayData: null,
			deptData: null,
			errorWarning: '',
			searchPhrase: '',
			dropData: "",
			textData: ""
		};
	},
	componentWillMount: function(){
		// Grabs the department data and admin data
		this.getData();
		this.getDept();
	},
	getData: function(){
		$.ajax({
			url: 'index.php?module=intern&action=adminRest',
			type: 'GET',
			dataType: 'json',
			success: function(data) {					
				this.setState({mainData: data,
							   displayData: data});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to grab displayed data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	getDept: function(){
		$.ajax({
			url: 'index.php?module=intern&action=get_dept',
			action: 'GET',
			dataType: 'json',
			success: function(data) {			
				data.unshift({name: "Select a department", id: "-1"});
				this.setState({deptData: data});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to grab deptartment data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	onAdminDelete: function(idNum){
		// Updating the new state for optimization (snappy response on the client)
		// When a value is being deleted
		var newVal = this.state.displayData.filter(function(el){
			return el.id !== idNum;
		});
		this.setState({displayData: newVal});

		$.ajax({
			url: 'index.php?module=intern&action=adminRest&id='+idNum,
			type: 'DELETE',		
			success: function() {		
				this.getData();
			}.bind(this)
		});
	},
	onAdminCreate: function(username, department)
	{
		var isMult = false;
		var displayName = '';
		for (var j = 0, k = this.state.displayData.length; j < k; j++)
		{
			if (this.state.displayData[j].username == username)
			{	
				displayName = this.state.displayData[j].display_name;
				if (this.state.displayData[j].name == department)
				{
					isMult = true;
				}
			}
		}	

		var deptName = this.state.deptData[department].name;

		if (username != '')
		{
			if (displayName != '')
			{
				if (department != '')
				{
					if (!isMult)
					{
						this.state.displayData.unshift({username: username, id: -1, name: deptName, display_name: displayName});
					}
				}
			}
		}

		// Updating the new state for optimization (snappy response on the client)
		var newVal = this.state.displayData;
		this.setState({displayData: newVal});
		
		$.ajax({
			url: 'index.php?module=intern&action=adminRest&user='+username+'&dept='+department,
			type: 'POST',		
			success: function(data) {		
				this.getData();
			}.bind(this),
			error: function(http) {
				var errorMessage = http.responseText;
				this.setState({errorWarning: errorMessage});
				$("#warningError").show();
			}.bind(this)	
		});
	},
	searchList: function(e)
	{
		try {
			// Saves the phrase that the user is looking for.
			var phrase = e.target.value.toLowerCase();
			this.setState({searchPhrase: phrase});
		}
		catch (err)
		{
			var phrase = this.state.searchPhrase;
		}

		var filtered = [];

		// Looks for the phrase by filtering the mainData
		for (var i = 0; i < this.state.mainData.length; i++) {
			var item = this.state.mainData[i];

			if (item.name.toLowerCase().includes(phrase) 
				|| item.username.toLowerCase().includes(phrase) 
				|| item.display_name.toLowerCase().includes(phrase))
			{
				filtered.push(item);
			}
		}

		this.setState({displayData:filtered});
	},
	handleDrop: function(e) {
		this.setState({dropData: e.target.value});
	},
	handleSubmit: function() {
		var username = React.findDOMNode(this.refs.username).value.trim();
		var deptNum = this.state.dropData;

		this.onAdminCreate(username, deptNum);
	},
	render: function() {
		if (this.state.mainData != null)
		{
			var onAdminDelete = this.onAdminDelete;
			var AdminsData = this.state.displayData.map(function (admin) {		    
			return (
				<DeleteAdmin key={admin.id}
						fullname={admin.display_name}
						username={admin.username}
					  	department={admin.name} 
					  	id={admin.id}
					  	onAdminDelete={onAdminDelete} />
				);
			});	
		}	
		else
		{
			var AdminsData = "";
		}

		if (this.state.deptData != null)
		{		
			var dData = this.state.deptData.map(function (dept) {		    
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
		return (
			<div className="search">

				<div id="warningError" className="alert alert-warning alert-dismissible" role="alert" hidden>
					<button type="button"  className="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Warning!</strong> {this.state.errorWarning}
				</div>

				<div className="row">
					<div className="col-md-5">
						<h1> Administrators </h1>
							<br />
							<div className="input-group">
      							<input type="text" className="form-control" placeholder="Search for..." onChange={this.searchList} />
  							</div>
  							<br />
							<table className="table table-condensed table-striped">
								<thead>
									<tr>
										<th>Fullname</th>
										<th>Username</th>
										<th>Department</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									{AdminsData}
								</tbody>
							</table>												
					</div>
					<br /> <br /> <br />
					<div className="col-md-5 col-md-offset-1">
						<div className="panel panel-default">
							<div className="panel-body">
								<div className="row">
									<div className="col-md-6">
										<label>Department:</label>
										<select className="form-control" onChange={this.handleDrop}>
											{dData}
										</select>
									</div>
									<div className="col-md-6">
										<label>Username:</label>
										<input type="text" className="form-control" placeholder="Username" ref="username" />
									</div>
								</div>
								<div className="row">
									<br />
									<div className="col-md-3 col-md-offset-6">
										<input className="btn btn-default" onClick={this.handleSubmit} value="Create Admin" />
									</div>
								</div>	
							</div>
						</div>						
					</div>	
				</div>
			</div>
		);
	}
});


var DeleteAdmin = React.createClass({
	handleChange: function() {
		this.props.onAdminDelete(this.props.id);
	},
	render: function() {  
		return (
			
			<tr>
				<td>{this.props.fullname}</td>
				<td>{this.props.username}</td>
				<td>{this.props.department}</td>
				<td> <a onClick={this.handleChange}> <i className="fa fa-trash-o" /> </a> </td>
			</tr>	
			
		);
	
	}
});		


var DepartmentList = React.createClass({
  render: function() {  
    return (   	
     	<option value={this.props.id}>{this.props.name}</option>		
    )
  }
});

React.render(
	<SearchAdmin />,
	document.getElementById('content')
);
