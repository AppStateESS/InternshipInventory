import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';

class AddData extends React.Component {
    constructor(props){
        super(props);

        this.handleClick = this.handleClick.bind(this);
    }
	handleClick() {
		var textName = ReactDOM.findDOMNode(this.refs.addData).value.trim();
		this.props.onCreate(textName);
	}
	render() {
		return (
			<div className="col-md-5 col-md-offset-1">
				<br /><br /><br />
				<div className="panel panel-default">
					<div className="panel-body">
						<div className="row">
							<div className="col-md-10">
								<label>{this.props.panelTitle}</label>
							</div>
						</div>
						<div className="row">
							<div className="col-md-8">
                                <div className="form-group">
								    <input type="text" className="form-control" ref="addData" />
                                </div>
							</div>

							<div className="col-md-4">
                                <div className="form-group">
								    <button className="btn btn-default btn-md" onClick={this.handleClick}>{this.props.buttonTitle}</button>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		);

	}
}


class DisplayData extends React.Component {
	constructor(props) {
        super(props);
		this.state = {
			editMode: false
		};

        this.handleEdit = this.handleEdit.bind(this);
        this.handleHide = this.handleHide.bind(this);
        this.handleSave = this.handleSave.bind(this);
	}
	handleEdit() {
		this.setState({editMode: true});

	}
	handleHide() {
		this.props.onHidden(this.props.hidden, this.props.id);
	}
	handleSave() {
		this.setState({editMode: false});

		// Grabs the value in the textbox
		var newName = ReactDOM.findDOMNode(this.refs.savedData).value.trim();

		if (newName === '')
		{
			newName = this.props.name;
		}

		var originalName = this.props.name;

		this.props.onSave(originalName, newName, this.props.id);
	}
	render() {
        var name = null;
        var hButton = null;

		// Determines which element to show on the page (hide/show and Save/Edit)
		if (this.props.hidden === 0) {
			name = this.props.name;
			hButton = <button className="btn btn-default btn-xs" type="submit" onClick={this.handleHide}> Hide </button>
		} else {
			name = <span className="text-muted"><em> {this.props.name} </em></span>;
			hButton = <button className="btn btn-default btn-xs" type="submit" onClick={this.handleHide}> Show </button>
		}

        var text = null;
        var eButton = null;
		if (this.state.editMode) {
			//var eName = 'Save';
			text = <div id={this.props.id} >
		  				<input type="text" className="form-control" defaultValue={this.props.name} ref="savedData" />
						</div>

			eButton = <button className="btn btn-default btn-xs" type="submit" onClick={this.handleSave}> Save </button>
		} else {
			//var eName = 'Edit';
			text = name;

			eButton = <button className="btn btn-default btn-xs" type="submit" onClick={this.handleEdit}> Edit </button>
		}

		return (
			<tr>
				<td>{text}</td>
				<td>{eButton}</td>
				<td>{hButton}</td>
			</tr>

		);

	}
}


class Manager extends React.Component {
	constructor(props) {
        super(props);
		this.state = {
			mainData: null,
			errorWarning: '',
			success: ''
		};

        this.getData = this.getData.bind(this);
        this.onHidden = this.onHidden.bind(this);
        this.onSave = this.onSave.bind(this);
        this.onCreate = this.onCreate.bind(this);
	}
	componentWillMount(){
		this.getData();
	}
	getData(){
		$.ajax({
			url: 'index.php?module=intern&action='+this.props.ajaxURL,
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				this.setState({mainData: data});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Sorry, there was a problem fetching data from the server.");
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	}
	onHidden(val, id){
		// Hides the selected value
		if (val === 0) {
			val += 1;
		} else {
			val += -1;
		}

		$.ajax({
			url: 'index.php?module=intern&action='+this.props.ajaxURL+'&val='+val+'&id='+id,
			type: 'PUT',
			success: function() {
				this.getData();
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Sorry, there was a problem fetching data from the server.");
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	}
	onSave(orgName, newName, id){
		var cleanName = encodeURIComponent(newName)

		// Saves the value into the database
		$.ajax({
			url: 'index.php?module=intern&action='+this.props.ajaxURL+'&name='+cleanName+'&id='+id,
			type: 'PUT',
			success: function(data) {
				// Determines if the values have changed and if so, continues
				// with the changes.
				if (orgName !== newName)
				{
					$("#success").show();
					var added = 'Updated '+orgName+ " to " +newName+'.';
					this.setState({success: added});
				}
				this.getData();
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Sorry, there was a problem fetching data from the server.");
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	}
	onCreate(name) {
		// Creates a new value
		$.ajax({
			url: 'index.php?module=intern&action='+this.props.ajaxURL+'&create='+name,
			type: 'POST',
			success: function(data) {
				// Shows a success message for the new value being added.
				$("#success").show();
				var added = 'Added '+name+'.';
				this.setState({success: added});
				this.getData();
			}.bind(this),
			error: function(http) {
				var errorMessage = http.responseText;
				this.setState({errorWarning: errorMessage});
				$("#warningError").show();
			}.bind(this)
		});
	}
	render() {
        var data = null;

		if (this.state.mainData != null) {
			//var buttonTitle = this.props.buttonTitle;
			//var panelTitle = this.props.panelTitle;
			var onHidden = this.onHidden;
			var onSave = this.onSave;
			data = this.state.mainData.map(function (data) {
			return (
				<DisplayData key={data.id}
						   id={data.id}
						   name={data.name}
						   hidden={data.hidden}
						   onHidden={onHidden}
						   onSave={onSave} />
				);
			});

		} else {
			data = <tr><td></td></tr>;
		}

		return (
			<div className="data">

			<div id="success" className="alert alert-success alert-dismissible" role="alert" hidden>
				<button type="button"  className="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong>Success!</strong> {this.state.success}
			</div>

			<div id="warningError" className="alert alert-warning alert-dismissible" role="alert" hidden>
				<button type="button"  className="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong>Warning!</strong> {this.state.errorWarning}
			</div>

				<div className="row">
					<div className="col-md-5">
						<h1> {this.props.title} </h1>
							<table className="table table-condensed table-striped">
								<thead>
									<tr>
										<th>Name</th>
										<th>Options</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									{data}
								</tbody>
							</table>
					</div>
					<AddData onCreate={this.onCreate}
							   buttonTitle={this.props.buttonTitle}
						       panelTitle={this.props.panelTitle}  />

				</div>
			</div>
		);
	}
}


export default Manager;
