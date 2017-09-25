import React from 'react';
import ReactDOM from 'react-dom';

import $ from 'jquery';

/**
 * Notification component used for adding or delete courses.
 **/
class Notifications extends React.Component {
    render(){
        var notification;

        // Determine if the screen should render a notification.
        if (this.props.msg !== '') {
            if (this.props.msgType === 'success')
            {
                notification = <div className="alert alert-success" role="alert">
                                <i className="fa fa-check fa-2x pull-left"></i> {this.props.msg}
                            </div>
            }
            else if (this.props.msgType === 'error')
            {
                notification = <div className="alert alert-danger" role="alert">
                                	<i className="fa fa-times fa-2x pull-left"></i> {this.props.msg}
                               </div>
            }
        } else {
            notification = '';
        }

        return (
            <div>{notification}</div>
        );
    }
}

// Component creates a row for the courses
class CourseRow extends React.Component {
    constructor(props){
        super(props);
        this.handleChange = this.handleChange.bind(this);
    }
	handleChange(e) {
		var name = this.props.abbr + " - " + this.props.name;
		this.props.deleteCourse(this.props.id, name, this.props.cnum);
	}
	render() {
		return (
			<tr>
				<td> {this.props.abbr} - {this.props.name} {this.props.cnum}</td>
				<td> <a onClick={this.handleChange}> <i className="fa fa-trash-o" /> </a> </td>
			</tr>
		);
	}
}

// Component helps create the table
class CourseList extends React.Component {
	render() {
		var deleteCourse = this.props.deleteCourse;
		// Determines if it needs to create a row.
        var cRow = null;
		if (this.props.subjectData != null){
			cRow = this.props.subjectData.map(function(sub) {
				return (
					<CourseRow key={sub.id}
							   id={sub.id}
							   abbr={sub.abbreviation}
							   name={sub.description}
							   cnum={sub.course_num}
							   deleteCourse={deleteCourse} />
				);
			});
		} else{
			cRow = null;
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
}

// Component used to create a course
class CreateCourse extends React.Component {
	constructor(props) {
        super(props);

        this.state = {subject: "_-1"};

        this.handleDrop = this.handleDrop.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
	}
	handleDrop(e) {
		this.setState({subject: e.target.value});
	}
	handleSubmit(){
		// Trims the value and then determines if its length is = 4 and if it's all numbers.
		var courseNum = ReactDOM.findDOMNode(this.refs.courseNum).value.trim();
		if (courseNum.length === 4 && /^\d+$/.test(courseNum) && this.state.subject !== '_-1')
		{
			this.props.saveCourse(this.state.subject, courseNum);
		}
	}
	render() {
		return (
			<div className="panel panel-default">
				<div className="panel-body">
					<div className="row">
						<div className="col-md-6">
							<label>Subjects:</label>
							<select className="form-control" onChange={this.handleDrop}>
								{Object.keys(this.props.subjects).map(function(key) {
                                    return <option key={key} value={key}>{this.props.subjects[key]}</option>;
                                }.bind(this))}
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
}

class CourseSelector extends React.Component {
	constructor(props) {
        super(props);
		this.state = {
			subjectData: null,
			msgNotification: '',
            msgType: ''
		};

        this.getCourseData = this.getCourseData.bind(this);
        this.saveCourse = this.saveCourse.bind(this);
        this.deleteCourse = this.deleteCourse.bind(this);
	}
	componentWillMount(){
		this.getCourseData();
	}
	getCourseData(){
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
	}
	saveCourse(subjectId, course_num){
		$.ajax({
			url: 'index.php?module=intern&action=NormalCoursesRest&subjectId=' + subjectId + '&cnum=' + course_num,
			type: 'POST',
			success: function() {
				this.getCourseData();

				// Create success message.
				var msg = 'Successfully added ' + this.props.subjects[subjectId] + ' ' + course_num;
				this.setState({msgNotification:msg, msgType:'success'})
			}.bind(this),
			error: function(http) {
				// Create error message.
				var msg = 'Could not add ' + this.props.subjects[subjectId] + " " + course_num + " because ";
				this.setState({msgNotification:msg + http.responseText, msgType:'error'})
			}.bind(this)
		});
	}
	deleteCourse(id, name, course_num){
		$.ajax({
			url: 'index.php?module=intern&action=NormalCoursesRest&courseId='+id,
			type: 'DELETE',
			success: function() {
				this.getCourseData();

				// Create success message.
				var msg = 'Successfully deleted '+ name + " " + course_num;
				this.setState({msgNotification:msg, msgType:'success'})
			}.bind(this),
			error: function(http) {

				// Create error message.
				var msg = 'Could not delete ' + name + " " + course_num + " because ";
				this.setState({msgNotification:msg + http.responseText, msgType:'error'})
			}.bind(this)
		});
	}
	render() {
		return (
			<div>
				<Notifications msg={this.state.msgNotification} msgType={this.state.msgType} />

				<div className="row">
					<div className="col-lg-5">
						<CourseList	subjectData={this.state.subjectData} deleteCourse={this.deleteCourse} />
					</div>

					<div className="col-lg-5 col-lg-offset-1">
						<CreateCourse subjects={this.props.subjects} saveCourse={this.saveCourse} />
					</div>
				</div>
			</div>
		);
	}
}


ReactDOM.render(
	<CourseSelector subjects={window.subjects}/>,
	document.getElementById('edit_courses')
);
