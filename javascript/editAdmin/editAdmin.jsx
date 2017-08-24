import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group'

//var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;

class ErrorMessagesBlock extends React.Component {
    render() {
        if(this.props.errors === null){
            return '';
        }

        var errors = this.props.errors;

        return (
            <div className="row">
                <div className="col-sm-12 col-md-6 col-md-push-3">
                    <div className="alert alert-warning" role="alert">
                        <p><i className="fa fa-exclamation-circle fa-2x"></i> Warning: {errors}</p>

                    </div>
                </div>
            </div>
        );
    }
}

class DepartmentList extends React.Component {
  render() {
    return (
     	<option value={this.props.id}>{this.props.name}</option>
    )
  }
}

class DeleteAdmin extends React.Component {
    constructor(props){
        super(props);

        this.handleChange = this.handleChange.bind(this);
    }
	handleChange() {
		this.props.onAdminDelete(this.props.id, this.props.username, this.props.department);
	}
	render() {
		return (

			<tr>
				<td>{this.props.fullname}</td>
				<td>{this.props.username}</td>
				<td>{this.props.department}</td>
				<td> <a onClick={this.handleChange}> <i className="fa fa-trash-o" /> </a> </td>
			</tr>

		);
	}
}

// Main module that calls several component to build
// the search admin screen.
class SearchAdmin extends React.Component {
	constructor(props) {
        super(props);
        this.state = {
			mainData: null,
			displayData: null,
			deptData: null,
			errorWarning: null,
			messageType: null,
			searchPhrase: '',
			dropData: "",
			textData: ""
		};

        this.handleDrop = this.handleDrop.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.searchList = this.searchList.bind(this);
        this.getData = this.getData.bind(this);
        this.getDept = this.getDept.bind(this);
        this.onAdminDelete = this.onAdminDelete.bind(this);
        this.onAdminCreate = this.onAdminCreate.bind(this);
	}
	componentWillMount() {
		// Grabs the department data and admin data
		// at the start of execution
		this.getData();
		this.getDept();
	}
	getData() {
		// Sends an ajax request to adminRest to grab the
		// display data.
		$.ajax({
			url: 'index.php?module=intern&action=adminRest',
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				this.setState({mainData: data,
							   displayData: data});
				this.searchList();
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to grab displayed data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	}
	getDept() {
		// Sends an ajax request to deptRest to grab the
		// department data.
		$.ajax({
			url: 'index.php?module=intern&action=deptRest',
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
	}
	onAdminDelete(idNum, username, department) {
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
				var message = "Deleted admin "+username+ " from department " + department +".";
				this.setState({errorWarning: message, messageType: "success"});
				this.getData();
			}.bind(this)
		});
	}
	onAdminCreate(username, department)
	{
		//var displayName = '';
		var displayData = this.state.displayData;
		var dept = this.state.deptData;

        var errorMessage = null;

		// Catch whether the created admin is missing a department
		if(department === '' || department === -1){
			errorMessage = "Please choose a department.";
			this.setState({errorWarning: errorMessage});
			return;
		}

		// Catch whether the created admin is missing a username
		if(username === ''){
			errorMessage = "Please enter a valid username.";
			this.setState({errorWarning: errorMessage});

			return;
		}

		// Finds the index of the array if the department number matches
		// the id of the object.
        var departmentId = parseInt(department, 10);
		var deptIndex = dept.findIndex(function(element, index, arr){
            if(departmentId === element.id){
                return true;
            } else {
                return false;
            }
        });

		// Determines if the username has multiple entries within
		// the same department before creating the admin.
		for (var j = 0; j < displayData.length; j++) {
			if (displayData[j].username === username) {
				//displayName = displayData[j].display_name;
				if (displayData[j].name === dept[deptIndex].name) {
					errorMessage = "Multiple usernames in the same department.";
					this.setState({errorWarning: errorMessage});
					return;
				}
			}
		}

		// Updating the new state for optimization (snappy response on the client)
		var newVal = this.state.displayData;
		this.setState({displayData: newVal},this.searchList());

		$.ajax({
			url: 'index.php?module=intern&action=adminRest&user='+username+'&dept='+department,
			type: 'POST',
			success: function(data) {
				this.getData();
				var message = "Created admin "+username+" for department " + dept[deptIndex].name + ".";
				this.setState({errorWarning: message, messageType: "success"});
			}.bind(this),
			error: function(http) {
				var errorMessage = http.responseText;
				this.setState({errorWarning: errorMessage, messageType: "error"});
			}.bind(this)
		});
	}
	searchList(e)
	{
        var phrase = null;
		try {
			// Saves the phrase that the user is looking for.
			phrase = e.target.value.toLowerCase();
			this.setState({searchPhrase: phrase});
		}
		catch (err)
		{
			phrase = this.state.searchPhrase;
		}

		var filtered = [];

		// Looks for the phrase by filtering the mainData
		for (var i = 0; i < this.state.mainData.length; i++) {
			var item = this.state.mainData[i];

			// makes the item, username, displayName lowercase for easier searching
			if (item.name.toLowerCase().includes(phrase)
				|| item.username.toLowerCase().includes(phrase)
				|| item.display_name.toLowerCase().includes(phrase))
			{
				filtered.push(item);
			}
		}

		this.setState({displayData:filtered});
	}
	handleDrop(e) {
		this.setState({dropData: e.target.value});
	}
	handleSubmit() {
		var username = ReactDOM.findDOMNode(this.refs.username).value.trim();
		var deptNum = this.state.dropData;

		this.onAdminCreate(username, deptNum);
	}
	render() {
        var AdminsData = null;
		if (this.state.mainData != null) {
			var onAdminDelete = this.onAdminDelete;
			AdminsData = this.state.displayData.map(function (admin) {
			return (
				<DeleteAdmin key={admin.id}
						fullname={admin.display_name}
						username={admin.username}
					  	department={admin.name}
					  	id={admin.id}
					  	onAdminDelete={onAdminDelete} />
				);
			});
		} else {
			AdminsData = <tr><td></td></tr>; // Use an empty row here to avoid React warnings about whitespace inside <tbody>
		}

        var dData = null;
		if (this.state.deptData != null) {
			dData = this.state.deptData.map(function (dept) {
			return (
					<DepartmentList key={dept.id}
						name={dept.name}
						id={dept.id} />
				);
			});
		} else {
			dData = "";
		}

		var errors;
        if(this.state.errorWarning == null){
            errors = '';
        } else {
            errors = <ErrorMessagesBlock key="errorSet" errors={this.state.errorWarning} messageType={this.state.messageType} />
        }

		return (
			<div className="search">

				<ReactCSSTransitionGroup transitionName="example" transitionEnterTimeout={500} transitionLeaveTimeout={500}>
                    {errors}
                </ReactCSSTransitionGroup>
                <h1> Administrators </h1>
                    <div className="row" style={{marginTop: '2em'}}>
                        <div className="col-md-5 col-md-push-6">
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
                                            <div className="form-group" style={{marginTop: '1em'}}>
                                                <label>Username:</label>
                                                <input type="text" className="form-control" placeholder="Username" ref="username" />
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-md-3 col-md-offset-6">
                                            <div className="form-group">
                                                <button className="btn btn-default" onClick={this.handleSubmit}>Create Admin</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-5 col-md-pull-5">

                            <div className="form-group">
                                <input type="text" className="form-control" placeholder="Search for..." onChange={this.searchList} />
                            </div>

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
                    </div>
                </div>
		);
	}
}

ReactDOM.render(
	<SearchAdmin />,
	document.getElementById('content')
);
