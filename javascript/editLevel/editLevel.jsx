import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';

var AddData = React.createClass({
	handleClick: function() {
		var textCode = ReactDOM.findDOMNode(this.refs.addNewCode).value.trim();
		var textDes = ReactDOM.findDOMNode(this.refs.addNewDesc).value.trim();
		var textLev = ReactDOM.findDOMNode(this.refs.addNewLevel).value.trim();
		this.props.onCreate(textCode, textDes, textLev);
	},
	render: function() {
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
								    <input type="text" className="form-control" ref="addNewCode" />
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
								    <input type="text" className="form-control" ref="addNewDesc" />
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
								    <input type="text" className="form-control" ref="addNewLevel" />
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
});


var DisplayData = React.createClass({
	getInitialState: function() {
		return {
			editMode: false
		};
	},
	handleEdit: function() {
		this.setState({editMode: true});

	},
	handleSave: function() {
		this.setState({editMode: false});

		// Grabs the value in the textbox
		var newCode = ReactDOM.findDOMNode(this.refs.savedCodeData).value.trim();
		var newDes = ReactDOM.findDOMNode(this.refs.savedDesData).value.trim();
		var newLeve = ReactDOM.findDOMNode(this.refs.savedLevelData).value.trim();

		if (newCode === ''){
			newCode = this.props.code;
		}
		if(newLeve === ''){
			newLeve = this.props.level;
		}

		var originalName = this.props.code;
		var originalLeve = this.props.level;

		this.props.onSave(originalName, newCode, originalLeve, newLeve, newDes);
	},
	render: function() {
    var textName = null;
		var textDes = null;
		var textLev = null;
    var eButton = null;
    var codeName = <span className="text-muted"> {this.props.code} </span>;
		var desName = <span className="text-muted"> {this.props.description} </span>;;
		var levelName = <span className="text-muted">{this.props.level} </span>;;
		if (this.state.editMode) {
			textName = <div id={this.props.code} >
							     <input type="text" className="form-control" defaultValue={this.props.code} ref="savedCodeData" />
						     </div>
			textDes = <div id={this.props.description} >
					 				 <input type="text" className="form-control" defaultValue={this.props.description} ref="savedDesData" />
					 			 </div>
			textLev = <div id={this.props.level} >
									 <input type="text" className="form-control" defaultValue={this.props.level} ref="savedLevelData" />
								 </div>
			eButton = <button className="btn btn-default btn-xs" type="submit" onClick={this.handleSave}> Save </button>
		} else {
      textName = codeName;
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
});


var Manager = React.createClass({
	getInitialState: function() {
		return {
			mainData: null,
			errorWarning: '',
			success: ''
		};
	},
	componentWillMount: function(){
		this.getData();
	},
	getData: function(){
		$.ajax({
			url: 'index.php?module=intern&action=levelRest',
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				this.setState({mainData: data});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("getData, there was a problem fetching data from the server.");
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	},
	onSave: function(orgCode, code, orgLeve, leve, newDes){
		var cleanCode = encodeURIComponent(code)
		var cleanLevel = encodeURIComponent(leve)

		// Saves the value into the database
		$.ajax({
			url: 'index.php?module=intern&action=levelRest&code='+cleanCode+'&descr='+newDes+'&level='+cleanLevel,
			type: 'PUT',
			success: function(data) {
				// Determines if the values have changed and if so, continues with the changes.
				if (orgCode !== code && orgLeve !== leve)
				{
					$("#success").show();
					var added = 'Updated '+orgCode+ " to " +code+'.';
					this.setState({success: added});
				}
				this.getData();
			}.bind(this),
			error: function(xhr, status, err) {
				alert("onSave, there was a problem fetching data from the server.");
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	},
	onCreate: function(code, descrip, level) {
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
			}.bind(this),
			error: function(http) {
				var errorMessage = http.responseText;
				this.setState({errorWarning: errorMessage});
				$("#warningError").show();
			}.bind(this)
		});
	},
	render: function() {
    var data = null;
		if (this.state.mainData != null) {
			var onSave = this.onSave;
			data = this.state.mainData.map(function (data) {
			return (
				<DisplayData key={data.code}
						   code={data.code}
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
});

ReactDOM.render(
	<Manager />,
	document.getElementById('content')
);
