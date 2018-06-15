import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';

class AddData extends React.Component{
	constructor(props, context) {
		super(props, context);
		this.handleClick = this.handleClick.bind(this);
	}
	handleClick(){
		var textCode = ReactDOM.findDOMNode(this.refs.addNewCode).value.trim();
		var textDes = ReactDOM.findDOMNode(this.refs.addNewDesc).value.trim();
		var textLev = ReactDOM.findDOMNode(this.refs.addNewLevel).value.trim();
		this.props.onCreate(textCode, textDes, textLev);
	}
	render() {
		return (
			<div className="col-md-5 col-md-offset-1">
				<br /><br /><br />
				<div className="panel panel-default">
					<div className="panel-body">
						<div className="row">
							<div className="col-md-10">
								<label>Code:</label>
							</div>
						</div>
						<div className="row">
							<div className="col-md-8">
								<div className="form-group">
									<input id="codeName" type="text" className="form-control" ref="addNewCode" />
								</div>
							</div>
						</div>
						<div className="row">
							<div className="col-md-10">
								<label>Description:</label>
							</div>
						</div>
						<div className="row">
							<div className="col-md-8">
								<div className="form-group">
									<input id="desName" type="text" className="form-control" ref="addNewDesc" />
								</div>
							</div>
						</div>
						<div className="row">
							<div className="col-md-10">
								<label>Level:</label>
							</div>
						</div>
						<div className="row">
							<div className="col-md-8">
								<div className="forCodem-group">
									<input id="levName" type="text" className="form-control" ref="addNewLevel" />
								</div>
							</div>
							<div className="col-md-4">
								<div className="form-group">
									<button className="btn btn-default btn-md" onClick={this.handleClick}>Create Code</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		);
	}
}


class DisplayData extends React.Component{
	constructor(props, context) {
		super(props, context);
		this.state = {editMode: false};
		this.handleEdit = this.handleEdit.bind(this);
		this.handleSave = this.handleSave.bind(this);
	}
	handleEdit() {
		this.setState({editMode: true});

	}
	handleSave() {
		this.setState({editMode: false});

		// Grabs the value in the textbox
		var newCode = this.props.code;
		var newDes = ReactDOM.findDOMNode(this.refs.savedDesData).value.trim();
		var newLeve = ReactDOM.findDOMNode(this.refs.savedLevelData).value.trim();

		this.props.onSave(newCode, newDes, newLeve);
	}
	render() {
		var textDes = null;
		var textLev = null;
		var eButton = null;
		var textName = <span className="text-muted"> {this.props.code} </span>;
		var desName = <span className="text-muted"> {this.props.description} </span>;;
		var levelName = <span className="text-muted">{this.props.level} </span>;;
		if (this.state.editMode) {
			textDes = <div id={this.props.description} >
				<input type="text" className="form-control" defaultValue={this.props.description} ref="savedDesData" />
			</div>
			textLev = <div id={this.props.level} >
				<input type="text" className="form-control" defaultValue={this.props.level} ref="savedLevelData" />
			</div>
			eButton = <button className="btn btn-default btn-xs" type="submit" onClick={this.handleSave}> Save </button>
		} else {
			textDes = desName;
			textLev = levelName;
			eButton = <button className="btn btn-default btn-xs" type="submit" onClick={this.handleEdit}> Edit </button>
		}
		return (
			<tr>
				<td>{textName}</td>
				<td>{textDes}</td>
				<td>{textLev}</td>
				<td>{eButton}</td>
			</tr>
		);
	}
}

class Manager extends React.Component{
	constructor(props, context) {
		super(props, context);
		this.state = {mainData: null,
			errorWarning: '',
			success: ''};
		this.componentWillMount = this.componentWillMount.bind(this);
		this.getData = this.getData.bind(this);
		this.onSave = this.onSave.bind(this);
		this.onCreate = this.onCreate.bind(this);
	}
	componentWillMount(){
		this.getData();
	}
	getData(){
		$.ajax({
			url: 'index.php?module=intern&action=levelRest',
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				this.setState({mainData: data});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("There was a problem fetching data from the server.");
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	}
	onSave(newCode, newDes, newLev){
		// Saves the value into the database
		$.ajax({
			url: 'index.php?module=intern&action=levelRest&code='+newCode+'&descr='+newDes+'&level='+newLev,
			type: 'PUT',
			success: function(data) {
				$("#success").show();
				var added = 'Updated '+newCode+'.';
				this.setState({success: added});
				this.getData();
				$("#warningError").hide();
			}.bind(this),
			error: function(http) {
				var errorMessage = http.responseText;
				this.setState({errorWarning: errorMessage});
				$("#warningError").show();
				$("#success").hide();
			}.bind(this)
		});
	}
	onCreate(code, descrip, level) {
		// Creates a new value
		$.ajax({
			url: 'index.php?module=intern&action=levelRest&code='+code+'&descr='+descrip+'&level='+level,
			type: 'POST',
			success: function(data) {
				// Shows a success message for the new value being added.
				$("#success").show();
				var added = 'Added '+code+'.';
				this.setState({success: added});
				this.getData();
				$("#warningError").hide();
			}.bind(this),
			error: function(http) {
				var errorMessage = http.responseText;
				this.setState({errorWarning: errorMessage});
				$("#warningError").show();
				$("#success").hide();
			}.bind(this)
		});
	}
	render() {
		var data = null;
		if (this.state.mainData != null) {
			var onSave = this.onSave;
			data = this.state.mainData.map(function (data) {
				return (
					<DisplayData key={data.code}
						code={data.code}
						description={data.description}
						level={data.level}
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
						<h1> Student Levels</h1>
						<table className="table table-condensed table-striped">
							<thead>
								<tr>
									<th>Code</th>
									<th>Description</th>
									<th>Level</th>
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

ReactDOM.render(<Manager />, document.getElementById('level'));
