import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';


var StateList = React.createClass({
	// Disables/Enables the state in the dropdown
    render: function() {
        var optionSelect = null;
  	    if (this.props.active === 1) {
  	        optionSelect = <option value={this.props.sAbbr} disabled>{this.props.stateName}</option>
  	    } else {
            optionSelect = <option value={this.props.sAbbr}>{this.props.stateName}</option>
  	    }

        return (optionSelect);
    }
});


var TableStates = React.createClass({
	handleClick: function(){
		this.props.onStateDelete(this.props.sAbbr);
	},
	// If the state is active rendering the html elements otherwise do nothing.
	render: function() {
		if (this.props.active === 1)
		{
			var row1 = <td>{this.props.stateName}</td>

			var row2 =	<td><button type="button" className="close" data-dismiss="alert" aria-label="Close" onClick={this.handleClick}><span aria-hidden="true">&times;</span></button></td>
		}
		return(
			<tr>
				{row1}{row2}
			</tr>
		);
	}
});


var States = React.createClass({
	getInitialState: function() {
		return {
			mainData: null,
			dropData: null
		};
	},
	componentWillMount: function(){
		this.getData();
	},
	getData: function(){
		$.ajax({
			url: 'index.php?module=intern&action=stateRest',
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				// Adds Select a State to the data array.
				data.unshift({full_name: "Select a State", abbr: "AA"});
				this.setState({mainData: data, dropData: data});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Sorry, there was a problem fetching data from the server.");
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	},
	handleDrop: function(e){
		//Event handler for the dropdown box.
		if (e.target.value !== 'AA')
		{
			// Determines the text value (not abbr) of the selected state.
			// var options = e.target.options;
            // var val = null;
			// for (var i = 0; i < options.length; i++)
			// {
			// 	if (options[i].selected){
			// 		val = options[i].text;
			// 	}
			// }

			// Activating the selected state
			for (var j = 0, k = this.state.dropData.length; j < k; j++)
			{
				if (this.state.dropData[j].abbr === e.target.value)
				{
                    var newDropData = this.state.dropData;
					newDropData[j].active = this.state.dropData[j].active + 1;
                    this.setState({dropData: newDropData});
				}
			}

			// updating the new state for optimization (snappy response on the client)
			var newVal = this.state.dropData;
			this.setState({dropData: newVal});


			$.ajax({
			url: 'index.php?module=intern&action=stateRest&abbr='+e.target.value,
			type: 'PUT',
			success: function(data) {
				this.getData();
			}.bind(this),
			error: function(xhr, status, err) {
				alert("failed to PUT")
				console.error(this.props.url, status, err.toString());
			}.bind(this)
			});
		}
	},
	onStateDelete: function(abbr){
		// No longer makes the state active
		for (var j = 0, k = this.state.dropData.length; j < k; j++)
		{
			if (this.state.dropData[j].abbr === abbr)
			{
                var newDropData = this.state.dropData;
                newDropData[j].active = this.state.dropData[j].active - 1;
                this.setState({dropData: newDropData});
			}
		}

		// updating the new state for optimization (snappy response on the client)
		var newVal = this.state.dropData;
		this.setState({dropData: newVal});

		$.ajax({
			url: 'index.php?module=intern&action=stateRest&abbr='+abbr+'&remove=1',
			type: 'PUT',
			success: function(data) {
				this.getData();
			}.bind(this),
			error: function(xhr, status, err) {
				alert("failed to PUT")
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	},
	render: function() {
        var states = null;

		if (this.state.dropData != null) {
			states = this.state.dropData.map(function (data) {
			return (
					<StateList key={data.abbr}
							   sAbbr={data.abbr}
							   stateName={data.full_name}
							   active={data.active} />
				);
			});
		} else {
			states = '';
		}

        var row = null;
		if (this.state.dropData != null) {
			var onStateDelete = this.onStateDelete;
			row = this.state.dropData.map(function (data) {
			return (
					<TableStates key={data.abbr}
							   sAbbr={data.abbr}
							   stateName={data.full_name}
							   active={data.active}
							   onStateDelete={onStateDelete} />
				);
			});
		} else {
			row = <tr><td></td></tr>;
		}
		return (
			<div className="State List">
				<div className="col-md-5 col-md-offset-1">
					<div className="row">
						<div className="col-md-6">
							<label>States:</label>
							<select className="form-control" onChange={this.handleDrop}>
								{states}
							</select>
							<br />
							<div className="panel panel-default">
								<div className="panel-body">
									<table className="table table-condensed table-striped">
										<thead>
											<tr>
												<th>Allowed States:</th>
												<th />
											</tr>
										</thead>
										<tbody>
											{row}
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		);
	}
});


ReactDOM.render(
	<States />,
	document.getElementById('content')
);
