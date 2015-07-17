// First attempt at a react based page for adding an affiliation agreement

//
//

var DepartmentBox = React.createClass({
  getInitialState: function() {
    return {depts: [], usedDepts: []};
  },
  addDept: function(dept)
  {
    used = this.state.usedDepts;

    for(i = 0; i < used.length; i++)
    {
      if(used[i].dept == dept.dept)
      {
        return;
      }
    }

    used.push(dept);
    this.setState({usedDepts: used});
  },
  removeDept: function(dept)
  {
    var i = array.indexOf(dept);
    if(i != -1)
    {
      this.state.usedDepts.splice(i,1);
    }
  },
  componentDidMount: function() {
    $.ajax({
      url: this.props.url,
      dataType: 'json',
      cache: false,
      success: function(data){
        this.setState({depts: data});
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  render: function() {
    return (
    <div className ="departmentBox">
      <DepartmentDropdown onAdd={this.addDept} data={this.state.depts}/>
      <DepartmentList data={this.state.usedDepts}/>
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
   var selectOptions = this.props.data.map(function(node){
     return (<option>{node.dept}</option>);
   });
   return (
    <div className="departmentDropdown">
       <select className="departmentSelect" ref="deptChoices">
         {selectOptions}
       </select>
       <button onClick={this.add}>Add</button>
    </div>
    );
  }
});



var DepartmentList = React.createClass({
  render: function() {
    console.log(this.props.data);
    var listNodes = this.props.data.map(function(panel){
      return (
        <DepartmentPanel dept={panel.dept}/>
      );
    });
    return (
      <div className="departmentList">
       {listNodes}
      </div>
    );
  }
});

var DepartmentPanel = React.createClass({
  render: function() {
    return (
      <div className="departmentPanel">
         <panel>
           <Department dept={this.props.dept}></Department>
         </panel>
      </div>
    );
  }
});

var Department = React.createClass({
  render: function() {
    return (
      <div className="department">
       {this.props.dept}
      </div>
    );
  }
});


React.render(
  <DepartmentBox url="departments.json"/>,
  document.getElementById('departments')
);
