var CourseSelector = React.createClass({
	getInitialState: function() {
		return {
			subjectData: null,
		};
	},
	componentWillMount: function(){
		this.getCourseData();
	},
	getCourseData: function(){
		$.ajax({
			url: 'index.php?module=intern&action=NormalCoursesRest',
			type: 'GET',
			dataType: 'json',
			success: function(data) {					
				this.setState({subjectData: data});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to grab subject data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	saveCourse: function(subjectId, course_num){
		$.ajax({
			url: 'index.php?module=intern&action=NormalCoursesRest&subjectId='+subjectId+'&cnum='+course_num,
			type: 'POST',
			success: function() {					
				this.getCourseData();
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to post subject data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	deleteCourse: function(id){
		$.ajax({
			url: 'index.php?module=intern&action=NormalCoursesRest&courseId='+id,
			type: 'DELETE',
			success: function() {					
				this.getCourseData();
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to delete course data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)				
		});
	},
	render: function() {
		return (
			<div>
				<div className="row">
					<div className="col-lg-5">
						<h1> Courses </h1>
						<br />
					</div>
				</div>

				<div className="row">
					<div className="col-lg-5">
						<CourseList	subjectData  = {this.state.subjectData}
									deleteCourse = {this.deleteCourse} />											
					</div>
					
					<div className="col-lg-5 col-lg-offset-1">
						<CreateCourse saveCourse = {this.saveCourse} />
					</div>
				</div>
			</div>
		);
	}
});

var CourseList = React.createClass({
	render: function() {  
		var deleteCourse = this.props.deleteCourse;

		if (this.props.subjectData != null){
			var cRow = this.props.subjectData.map(function(sub) {
				return (
					<CourseRow key  = {sub.id}
							   id  = {sub.id}
							   abbr = {sub.abbreviation}
							   name = {sub.description} 
							   cnum = {sub.course_num}
							   deleteCourse = {deleteCourse} />
				);
			});
			
		}
		else{
			var cRow = "";
		}
		return (
			<table className="table table-condensed table-striped">
				<thead>
					<tr>
						<th>Course</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
					{cRow}
				</tbody>
			</table>	
		);
	}
});

var CourseRow = React.createClass({
	handleChange: function(e) {
		this.props.deleteCourse(this.props.id);
	},
	render: function() {
		return (
			<tr>
				<td> {this.props.abbr} - {this.props.name} {this.props.cnum}</td>
				<td> <a onClick={this.handleChange}> <i className="fa fa-trash-o" /> </a> </td>
			</tr>	
		);
	}
});

var CreateCourse = React.createClass({
	getInitialState: function() {
		return {subject: "_-1"};
	},
	handleDrop: function(e) {
		this.setState({subject: e.target.value});
	},
	handleSubmit: function(){
		var courseNum = React.findDOMNode(this.refs.courseNum).value.trim();
		if (courseNum.length == 4 && /^\d+$/.test(courseNum) && this.state.subject != '_-1')
		{
			this.props.saveCourse(this.state.subject, courseNum);
		}
	},
	render: function() {  
		return (
			<div className="panel panel-default">
				<div className="panel-body">
					<div className="row">
						<div className="col-md-6">
							<label>Subjects:</label>
							<select className="form-control" onChange={this.handleDrop}>
								{Object.keys(subjects).map(function(key) {
                                return <option key={key} value={key}>{subjects[key]}</option>;
                            })}
							</select>
						</div>
						<div className="col-md-6">
							<label>Course Number:</label>
							<input type="text" className="form-control" placeholder="0000" ref="courseNum" />
						</div>
					</div>
					<div className="row">
						<br />
						<div className="col-md-3 col-md-offset-6">
							<button type="button" className="btn btn-default" onClick={this.handleSubmit}> Create Course </button>
						</div>
					</div>
				</div>
			</div>
		);
	}
});


React.render(
	<CourseSelector />,
	document.getElementById('edit_courses')
	);