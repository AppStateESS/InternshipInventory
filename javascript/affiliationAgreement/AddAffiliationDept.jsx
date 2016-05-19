// Adds the components involved in the adding of different departments
// to the Add Affiliation form.


var DepartmentBox = React.createClass({
    getInitialState: function() {
        return {depts: [], usedDepts: []};
    },
    addDept: function(nameToAdd)
    {
        this.postData(nameToAdd);
    },
    removeClick: function(dept)
    {
        this.deleteData(dept);
    },
    componentWillMount: function(){
        // Grabs the department data
        this.getData();
    },
    getData: function(){
        $.ajax({
            url: 'index.php?module=intern&action=deptRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({depts: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab department data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateDeptRest&affiliation_agreement_id='+aaId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({usedDepts: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab added department data. "+ err.toString())
                console.error(this.props.url, stats, err.toString());
            }.bind(this)
        });
    },
    postData: function(department){
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateDeptRest&department='+department+'&affiliation_agreement_id='+aaId,
            type: 'POST',
            success: function(data){
                this.getData();
            }.bind(this),
            error: function(xhr, status, err){
                alert("Failed to add department to database properly. "+ err.toString())
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    deleteData: function(department){
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateDeptRest&department='+department+'&affiliation_agreement_id='+aaId,
            type: 'DELETE',
            success: function(data) {
                this.getData();
            }.bind(this),
            error: function(xhr, status, err){
                alert("Failed to remove department from database properly. "+ err.toString())
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    render: function() {
        return (
            <div className="form-group">
                <DepartmentDropdown onAdd={this.addDept} data={this.state.depts} used={this.state.usedDepts}/>
                <DepartmentList removeClick={this.removeClick} data={this.state.usedDepts}/>
            </div>
        );
    }
});

var DepartmentDropdown = React.createClass({
    add: function() {
        var deptToAdd = this.refs.deptChoices.getDOMNode().value;
        this.props.onAdd(deptToAdd);
    },
    render: function() {
        var options = Array({id:0, name: "Select a Department"});
        var data = this.props.data;
        var used = this.props.used;
        for(i = 0; i < data.length; i++)
        {
            options.push(data[i]);
        }
        var selectOptions = options.map(function(node){
            if(used.indexOf(node.name) > 0){
                return <option key={node.id} value={node.id} disabled>{node.name}</option>
            }
            return (<option key={node.id} value={node.id}>{node.name}</option>);
        });
        return (
            <div>
                <div className="form-group">
                    <select className="form-control" ref="deptChoices">
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


var DepartmentList = React.createClass({
    removeClick: function(deptToRemove) {
        this.props.removeClick(deptToRemove);
    },
    render: function() {
        var removeMethod = this.removeClick;
        var listNodes = this.props.data.map(function(panel){
            return (
                <DepartmentPanel key={panel} onRemoveClick={removeMethod} dept={panel}/>
            );
        });
        return (
            <ul className="list-group">
                {listNodes}
            </ul>
        );
    }
});

var DepartmentPanel = React.createClass({
    remove: function() {
        this.props.onRemoveClick(this.props.dept);
    },
    render: function() {
        return (
            <li className="list-group-item">
                {this.props.dept}
                <button onClick={this.remove} className="close">
                &times;
                </button>
            </li>
        );
    }
});


React.render(
    <DepartmentBox url="index.php?module=intern&action=get_dept"/>,
document.getElementById('departments')
);
