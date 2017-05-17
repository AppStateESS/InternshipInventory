import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';

// Components for adding departments to an Affiliation Agreement
// on the Edit Affiliation interface.


var DepartmentItem = React.createClass({
    remove: function() {
        this.props.onRemoveClick(this.props.dept);
    },
    render: function() {
        return (
            <li className="list-group-item">
                {this.props.dept.name}
                <button onClick={this.remove} className="close">&times;</button>
            </li>
        );
    }
});


var DepartmentList = React.createClass({
    removeClick: function(deptToRemove) {
        this.props.removeClick(deptToRemove);
    },
    render: function() {
        var listNodes = this.props.departments.map(function(department){
            return (
                <DepartmentItem key={department.id} onRemoveClick={this.removeClick} dept={department}/>
            );
        }.bind(this));

        return (
            <ul className="list-group">
                {listNodes}
            </ul>
        );
    }
});


var DepartmentDropdown = React.createClass({
    add: function() {
        var deptToAdd = this.refs.deptChoices.value;
        this.props.onAdd(deptToAdd);
    },
    render: function() {
        var options = this.props.departments;

        var selectOptions = options.map(function(department){

            // Check if this department is in the set of used departments
            var usedIndex = this.props.usedDepartments.findIndex(function(element, index, arr){
                if(department.id === element.id){
                    return true;
                } else {
                    return false;
                }
            });

            // If the department has been used (findIndex returns non-negative), then disable the department in the dropdown list
            if(usedIndex > -1){
                return <option key={department.id} value={department.id} disabled>{department.name}</option>
            }

            // Otherwise, return an enabled option
            return (<option key={department.id} value={department.id}>{department.name}</option>);
        }.bind(this));

        return (
            <div>
                <div className="form-group">
                    <select className="form-control" ref="deptChoices">
                        <option value="-1">Select a Department</option>
                        {selectOptions}
                    </select>
                </div>
                <div className="form-group">
                    <button onClick={this.add} className="btn btn-md btn-success">Add</button>
                </div>
            </div>
        );
    }
});


var DepartmentBox = React.createClass({
    getInitialState: function() {
        return {depts: null, usedDepts: null};
    },
    addDept: function(nameToAdd) {
        this.postData(nameToAdd);
    },
    removeClick: function(dept) {
        this.deleteData(dept);
    },
    componentWillMount: function() {
        // Get the department data on initial load
        this.getData();
    },
    getData: function() {
        // Fetch the full list of departments
        $.ajax({
            url: 'index.php?module=intern&action=deptRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({depts: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab department data.")
                console.error(status, err.toString());
            }
        });
        // Fetch the list of departments for this internship
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateDeptRest&affiliation_agreement_id='+this.props.affiliationId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({usedDepts: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab added department data. "+ err.toString())
                console.error(status, err.toString());
            }
        });
    },
    postData: function(department) {
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateDeptRest&department='+department+'&affiliation_agreement_id='+this.props.affiliationId,
            type: 'POST',
            success: function(data){
                this.getData();
            }.bind(this),
            error: function(xhr, status, err){
                alert("Failed to add department to database properly. "+ err.toString())
                console.error(status, err.toString());
            }
        });
    },
    deleteData: function(department) {
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateDeptRest&department='+department.id+'&affiliation_agreement_id='+this.props.affiliationId,
            type: 'DELETE',
            success: function(data) {
                this.getData();
            }.bind(this),
            error: function(xhr, status, err){
                alert("Failed to remove department from database properly. "+ err.toString())
                console.error(status, err.toString());
            }
        });
    },
    render: function() {

        if(this.state.depts == null || this.state.usedDepts == null){
            return (<div></div>);
        }

        return (
            <div className="form-group">
                <DepartmentDropdown onAdd={this.addDept} departments={this.state.depts} usedDepartments={this.state.usedDepts}/>
                <DepartmentList removeClick={this.removeClick} departments={this.state.usedDepts}/>
            </div>
        );
    }
});

ReactDOM.render(
    <DepartmentBox affiliationId={window.aaId}/>,
    document.getElementById('departments')
);
