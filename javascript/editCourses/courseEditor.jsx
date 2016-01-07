var SearchAdmin = React.createClass({
	componentWillMount: function(){
		// Grabs the department data and admin data
		this.getData();
	},
	getData: function(){
		/*
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
*/
	},
	render: function() {
		return (
			<div>
			hello world
			</div>
		);
	}
});

React.render(
	<SearchAdmin />,
	document.getElementById('edit_courses')
);