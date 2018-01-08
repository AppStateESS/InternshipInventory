import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';

// Components for adding departments to an Affiliation Agreement
// on the Edit Affiliation interface.


class DepartmentItem extends React.Component {
    constructor(props){
        super(props);

        this.remove = this.remove.bind(this);
    }
    remove() {
        this.props.onRemoveClick(this.props.dept);
    }
    render() {
        return (
            <li className="list-group-item">
                {this.props.dept.name}
                <button onClick={this.remove} className="close">&times;</button>
            </li>
        );
    }
  }


class DepartmentList extends React.Component {
    constructor(props){
        super(props);

        this.removeClick = this.removeClick.bind(this);
    }
    removeClick(deptToRemove) {
        this.props.removeClick(deptToRemove);
    }
    render() {
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
}


class DepartmentDropdown extends React.Component {
    constructor(props){
        super(props);

        this.add = this.add.bind(this);
    }
    add() {
        var deptToAdd = this.refs.deptChoices.value;
        this.props.onAdd(deptToAdd);
    }
    render() {
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
}


class DepartmentBox extends React.Component {
    constructor(props) {
      super(props);

      this.state = {depts: null, usedDepts: null};

      this.addDept = this.addDept.bind(this);
      this.removeClick = this.removeClick.bind(this);
      this.getData = this.getData.bind(this);
      this.postData = this.postData.bind(this);
      this.deleteData = this.deleteData.bind(this);
    }
    addDept(nameToAdd) {
        this.postData(nameToAdd);
    }
    removeClick(dept) {
        this.deleteData(dept);
    }
    componentWillMount() {
        // Get the department data on initial load
        this.getData();
    }
    getData() {
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
    }
    postData(department) {
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
    }
    deleteData(department) {
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
    }
    render() {

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
}

ReactDOM.render(
    <DepartmentBox affiliationId={window.aaId}/>,
    document.getElementById('departments')
);
