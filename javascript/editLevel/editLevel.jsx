import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group'

var ErrorMessagesBlock = React.createClass({
    render: function() {
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
});

var DisplayLevel = React.createClass({
  render: function() {
    return (
     	<option value={this.props.id}>{this.props.name}</option>
    )
  }
});

// Main module that calls several component to build
// the search admin screen.
var SearchAdmin = React.createClass({
	getInitialState: function() {
		return {
			mainData: null,
			displayData: null,
			errorWarning: null,
			messageType: null,
			textData: ""
		};
	},
	componentWillMount: function(){
		// Grabs the level data at the start of execution
		this.getLevelData();
	},
	getLevelData: function(){
		// Sends an ajax request to levelRest to grab the display data.
		$.ajax({
			url: 'index.php?module=intern&action=levelRest',
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
	onAdminCreate: function(code, description, level)
	{
		var displayData = this.state.displayData;
    var errorMessage = null;

		// Catch whether the user entered a level
		if(level === ''){
			errorMessage = "Please enter a level.";
			this.setState({errorWarning: errorMessage});
			return;
		}

		// Catch whether the user entered a code
		if(code === ''){
			errorMessage = "Please enter a code.";
			this.setState({errorWarning: errorMessage});

			return;
		}

		// Determines if the code has multiple entries within
		// the table before creating the code.
		for (var j = 0; j < displayData.length; j++) {
			if (displayData[j].code === code) {
				errorMessage = "Multiple codes used.";
				this.setState({errorWarning: errorMessage});
				return;
			}
		}

		// Updating the new state for optimization (snappy response on the client)
		var newVal = this.state.displayData;
		this.setState({displayData: newVal});

		$.ajax({
			url: 'index.php?module=intern&action=levelRest&code='+code+'&desc='+description+'&level='+level,
			type: 'POST',
			success: function(data) {
				this.getData();
				var message = "Created code "+code+" with level "+level+".";
				this.setState({errorWarning: message, messageType: "success"});
			}.bind(this),
			error: function(http) {
				var errorMessage = http.responseText;
				this.setState({errorWarning: errorMessage, messageType: "error"});
			}.bind(this)
		});
	},
	handleDrop: function(e) {
		this.setState({dropData: e.target.value});
	},
	handleSubmit: function() {
		var code = ReactDOM.findDOMNode(this.refs.cod).value.trim();
		var level = ReactDOM.findDOMNode(this.refs.lev).value.trim();
		var description = ReactDOM.findDOMNode(this.refs.desc).value.trim();;

		this.onAdminCreate(code, description, level);
	},
	render: function() {
		var LevelData = null;
		if (this.state.mainData != null) {
			LevelData = this.state.displayData.map(function (levels) {
				return (
					<DisplayLevel key={levels.code}
						description={levels.description}
						level={levels.level} />
				);
			});
		} else {
			LevelData = <tr><td></td></tr>; // Use an empty row here to avoid React warnings about whitespace inside <tbody>
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
                <h1> Student Levels </h1>
                    <div className="row" style={{marginTop: '2em'}}>
                        <div className="col-md-5 col-md-push-6">
                            <div className="panel panel-default">
                                <div className="panel-body">
                                    <div className="row">
                                        <div className="col-md-6">
                                            <div className="form-group" style={{marginTop: '1em'}}>
                                                <label>Code:</label>
                                                <input type="text" className="form-control" placeholder="Code" ref="cod" />
                                            </div>
                                        </div>
																				<div className="col-md-6">
                                            <div className="form-group" style={{marginTop: '1em'}}>
                                                <label>Description:</label>
                                                <input type="text" className="form-control" placeholder="Description" ref="desc" />
                                            </div>
                                        </div>
																				<div className="col-md-6">
																					<div className="form-group" style={{marginTop: '1em'}}>
																							<label>Level:</label>
																							<input type="text" className="form-control" placeholder="Level" ref="lev" />
																					</div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-md-3 col-md-offset-6">
                                            <div className="form-group">
                                                <button className="btn btn-default" onClick={this.handleSubmit}>Create Code</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-5 col-md-pull-5">
                            <table className="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Level</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {LevelData}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
		);
	}
});

ReactDOM.render(
	<SearchAdmin />,
	document.getElementById('content')
);
