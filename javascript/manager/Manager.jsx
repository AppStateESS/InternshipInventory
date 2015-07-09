
var Manager = React.createClass({
	getInitialState: function() {
		return {
			mainData: null,
			errorWarning: '',
			success: ''
		};
	},
	componentWillMount: function(){
		$.ajax({
			url: 'index.php?module=intern&action='+this.props.ajaxURL,
			type: 'GET',
			dataType: 'json',
			success: function(data) {		
				//alert("test passed")				
				this.setState({mainData: data});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("test failed")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	onHidden: function(val, id){
		if (val == 0)
		{
			var val = val + 1;
		}
		else
		{
			var val = val - 1;
		}

		$.ajax({
			url: 'index.php?module=intern&action='+this.props.ajaxURL+'&val='+val+'&id='+id,
			type: 'PUT',
			success: function(data) {		
				//alert("test passed")				
				$.ajax({
					url: 'index.php?module=intern&action='+this.props.ajaxURL,
					type: 'GET',
					dataType: 'json',
					success: function(data) {		
						//alert("test passed")				
						this.setState({mainData: data});
					}.bind(this),
					error: function(xhr, status, err) {
						alert("test failed")
						console.error(this.props.url, status, err.toString());
					}.bind(this)				
				});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("test failed")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	onSave: function(orgName, newName, id){
		$.ajax({
			url: 'index.php?module=intern&action='+this.props.ajaxURL+'&name='+newName+'&id='+id,
			type: 'PUT',
			success: function(data) {	
				if (orgName != newName)
				{	
					$("#success").show();
					var added = 'Updated '+orgName+ " to " +newName+'.';
					this.setState({success: added});
				}				
				$.ajax({
					url: 'index.php?module=intern&action='+this.props.ajaxURL,
					type: 'GET',
					dataType: 'json',
					success: function(data) {		
						//alert("test passed")				
						this.setState({mainData: data});
					}.bind(this),
					error: function(xhr, status, err) {
						alert("test failed")
						console.error(this.props.url, status, err.toString());
					}.bind(this)				
				});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("test failed")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	onCreate: function(name) {
		$.ajax({
			url: 'index.php?module=intern&action='+this.props.ajaxURL+'&create='+name,
			type: 'POST',		
			success: function(data) {	
				$("#success").show();
				var added = 'Added '+name+'.';
				this.setState({success: added});
				$.ajax({
					url: 'index.php?module=intern&action='+this.props.ajaxURL,
					type: 'GET',
					dataType: 'json',
					success: function(data) {		
						this.setState({mainData: data});
					}.bind(this),
					error: function(xhr, status, err) {
						alert("test failed")
						console.error(this.props.url, status, err.toString());
					}.bind(this)				
				});
			}.bind(this),
			error: function(http) {
				var errorMessage = http.responseText;
				this.setState({errorWarning: errorMessage});
				$("#warningError").show();
			}.bind(this)	
		});
	},
	render: function() {
		if (this.state.mainData != null)
		{
			var buttonTitle = this.props.buttonTitle;
			var panelTitle = this.props.panelTitle;
			var onHidden = this.onHidden;
			var onSave = this.onSave;
			var Data = this.state.mainData.map(function (data) {		    
			return (
				<DisplayData key = {data.id}
						   id = {data.id}
						   name = {data.name}
						   hidden = {data.hidden} 
						   onHidden = {onHidden}
						   onSave = {onSave} />
				);
			});	
		}	
		else
		{
			var Data = "";
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
									{Data}
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


var DisplayData = React.createClass({
	getInitialState: function() {
		return {
			editMode: false
		};
	},
	handleEdit: function() {

		this.setState({editMode: true});

	},
	handleHide: function() {		
		this.props.onHidden(this.props.hidden, this.props.id);
	},
	handleSave: function() {
		this.setState({editMode: false});

		var newName = React.findDOMNode(this.refs.savedData).value.trim();
		if (newName == '')
		{
			newName = this.props.name;
		}
		originalName=this.props.name;

		this.props.onSave(originalName, newName, this.props.id);
	},
	render: function() {  

		if (this.props.hidden == 0)
		{
			var name = this.props.name;
			var hButton = <button className="btn btn-default btn-xs" type="submit" onClick={this.handleHide}> Hide </button> 
		}
		else
		{
			var name = <span className="text-muted"><em> {this.props.name} </em></span>;
			var hButton = <button className="btn btn-default btn-xs" type="submit" onClick={this.handleHide}> Show </button> 
		}

		if (this.state.editMode)
		{
			var eName = 'Save';
			var text = <div id={this.props.id} >
		  				<input type="text" className="form-control" placeholder={this.props.name} ref="savedData" />
						</div> 

			var eButton = <button className="btn btn-default btn-xs" type="submit" onClick={this.handleSave}> Save </button> 		
		}
		else
		{
			var eName = 'Edit';
			var text = name;
			
			var eButton = <button className="btn btn-default btn-xs" type="submit" onClick={this.handleEdit}> Edit </button> 
		}
		return (	
			<tr>
				<td>
					
					{text}

				</td>
				<td> {eButton} </td>
				<td> {hButton} </td>
			</tr>	
			
		);
	
	}
});		

var AddData = React.createClass({
	handleClick: function() {
		var textName = React.findDOMNode(this.refs.addData).value.trim();
		this.props.onCreate(textName);
	},
	render: function() {  
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
						<br />
						<div className="row">
							<div className="col-md-6">

								<input type="text" className="form-control" ref="addData" />
							</div>
							
							<div className="col-md-1 col-md-offset-.5">
								<input className="btn btn-default btn-md" value={this.props.buttonTitle} onClick={this.handleClick} />
							</div>
						</div>	
					</div>
				</div>
			</div>	
		);
	
	}
});	

