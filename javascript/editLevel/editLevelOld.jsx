import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import {Button, Modal} from 'react-bootstrap';

// Form for editing code details
var LevelForm = React.createClass({
  handleSave: function(){
    this.propls.handleSave({code: this.props.levelData.code,
                          description: this.props.levelData.description,
                          level: this.props.levelData.level});
  },
  render: function(){
    return (
      <div className="row">
        <div className="col-md-offset-1 col-md-10">
          <div className="row">
            <div className="col-md-6">
              <label>Code:</label>
              <input type="text" className="form-control" id="edit-code" placeholder="Code" ref="code" />
            </div>
            <div className="col-md-6">
              <label>Description:</label>
              <input type="text" className="form-control" id="edit-descr" placeholder="Description" ref="description" />
            </div>
          </div>
          <div className="row">
            <div className="col-md-6">
              <label>Level:</label>
              <input type="text" className="form-control" id="edit-level" placeholder="Level" ref="level" />
            </div>
            <div className="col-md-3 col-md-offset-6">
              <button className="btn btn-default" onClick={this.handleSave}>Save Code</button>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

var LevelModal = React.createClass({
  getInitialState: function() {
		return {
			errorWarning: '',
			showModalNotification: false,
			showModalForm: false,
		};
	},
	componentWillMount: function() {
		// Used for editing a user (see edit handler).
		// Disables/enables modal form and then grabs and displays the data.
		if (this.props.edit === true)
		{
			this.setState({showModalForm: true})
			this.props.getLevelDetail(this.props.code);
		}
	},
  clearStateAndHide: function() {
    this.setState(this.getInitialState());
    this.props.hide();
  },
  addLevel: function(data){
    var code = data.code;
    var description = data.description;
    var level = data.level;

    $.ajax({
			url: 'index.php?module=intern&action=levelRest&code='+code+'&description='+description+'&level='+level,
			type: 'POST',
			success: function() {
				this.setState({levelData: data});//TODO
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Sorry, looks like something went wrong. We couldn't save your changes.");
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
  },
  handleSave: function(levelData) {
		// Saves the level data.
		$.ajax({
			url: 'index.php?module=intern&action=restLevel',
			type: 'PUT',
			processData: false,
            dataType: 'json',
			data: JSON.stringify(levelData),
			success: function(data) {
        // Calls addFacultyToDept() and then closes the Modal Form.
        if(!this.props.edit){
				    this.addLevel(data);
        }
        if(this.props.edit){
          var departNum = this.props.code;
					this.props.getLevel();//TODO
          this.props.hide();
        } else {
          this.clearStateAndHide();
        }
		  }.bind(this),
			error: function(xhr, status, err) {
        alert("Sorry, looks like something went wrong. We couldn't save your changes.");
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	},
  showNotification: function(msg){
		this.setState({errorWarning:msg});
	},
  render: function(){
    var modalForm = <LevelForm levelData={this.props.levelData} handleSave={this.handleSave}/>;
    var title = null;
    var onHideMethod = null;
    if(this.props.edit){
      title = 'Edit Code Details';
      onHideMethod = this.props.hide;
    } else {
      title = 'Add a Code';
      onHideMethod = this.clearStateAndHide;
    }
    return (
          <Modal show={this.props.show} onHide={onHideMethod} animation={true} backdrop='static'>
            <Modal.Header closeButton>
                <Modal.Title>{title}</Modal.Title>
            </Modal.Header>
            <Modal.Body>
              {this.props.showModalForm ? modalForm: null}
            </Modal.Body>
            <Modal.Footer>
                <Button onClick={this.clearStateAndHide}>Close</Button>
            </Modal.Footer>
          </Modal>
        );
    }
});

var LevelTableRow = React.createClass({
  getInitialState: function(){
    return{showModal: false,
    userData: null,
    showModalForm: true,
    errorWarning: ''};
  },
  getLevelDetails: function(){
    $.ajax({
			url: 'index.php?module=intern&action=restLevel',
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				this.setState({userData: data});
			}.bind(this),
			error: function(xhr, status, err) {
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
  },
  handleEdit: function(){
    if (this.state.userData == null){
			this.getLevelDetails(this.props.code);
		}
		this.setState({showModal: true});
  },
  hideModal: function() {
    this.setState({showModal: false});
  },render: function() {
		// Creates each row for the name, banner ID, a button with a trigger for modal to edit, and a delete button.
		return (
			<tr>
				<td>{this.props.code}</td>
        <td>{this.props.description}</td>
				<td>{this.props.level}</td>
				<td><a onClick={this.handleEdit}><i className="fa fa-pencil-square-o" /></a></td>
        <td>
          <LevelModal  show={this.state.showModal}
                       hide={this.hideModal}
                       edit={true}
        				       code={this.props.code}
                       description={this.props.code}
                       evel={this.props.code}
        				       levelData={this.state.userData}
        						   showModalForm={this.state.showModalForm}
        						   getLevelDetail={this.getLevelDetails}
        						   errorWarning={this.state.errorWarning} />
        </td>
			</tr>
		);
	}
});

var LevelTable = React.createClass({
	render: function() {
		var codeData = null;
		if (this.state.tableData != null) {
      if (this.props.tableData.length > 0) {
        var getLevel = this.props.getLevel;
        codeData = this.state.tableData.map(function (LevelData) {
  				return (
  					<LevelTableRow key={LevelData.code}
              code={LevelData.code}
  						description={LevelData.description}
  						level={LevelData.level}
              getLevel={getLevel} />
  				);
  		});
  		} else {
        codeData = <tr>
  							<td colSpan="4"><span className="text-muted"><em>No code data exists</em></span></td>
  						  </tr>
  		}
    }else {
			codeData = '';
		}

		return (
      <div className="col-md-5 col-md-pull-5">
        <table className="table table-condensed table-striped">
          <thead>
            <tr>
              <th>Code</th>
              <th>Description</th>
              <th>Level</th>
              <th>Edit</th>
            </tr>
          </thead>
          <tbody>
            {codeData}
          </tbody>
        </table>
     </div>
		);
	}
});

var EditLevel = React.createClass({
	getInitialState: function() {
		return {
			levelData: null,
			showTable: false,
      showModalForm: false,
			errorWarning: '',
			showPopup: false
		};
	},
  getLevelDetails: function(){
		$.ajax({
			url: 'index.php?module=intern&action=levelRest',
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				var warning = '';
				this.setState({levelData: data, showModalForm: true, errorWarning:warning});
			}.bind(this),
			error: function(xhr, status, err) {
        var warning = null;
				warning = "Sorry, we couldn't load the details for that faculty member.";
				this.setState({errorWarning:warning})
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	},
  showModal: function(){
    this.setState({showPopup: true, showModalForm: false});
  },
  hideModal: function() {
    this.setState({showPopup: false});
  },
  render: function(){
    var levelTable = null;
    var addLevel = null;
    if(this.state.levelData != null){
      levelTable=
          <LevelTable
						tableData={this.state.levelData}
            getLevel={this.getLevel}
           />
      addLevel = <button className="btn btn-success" onClick={this.showModal}><i className="fa fa-user-plus"></i> Add Code</button>
    } else {
      levelTable = "";
      addLevel = "";
    }
    return (
        <div className="search">
          <div id="warningError" className="alert alert-warning alert-dismissible" role="alert" hidden>
            <button type="button"  className="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					  <strong>Warning!</strong> {this.state.errorWarning}
				  </div>
          <div className="row">
            <div className="col-md-5">
              <h1> Student Levels </h1>
              <div className="row">
                <div className="col-md-10">
                  {this.state.showTable ? levelTable : null}
                </div>
              </div>
            </div>
            <br /><br /><br /><br />
            {this.state.showTable ? addLevel: null}
          </div>
          <LevelModal show={this.state.showPopup} hide={this.hideModal} getLevel={this.getLevel}
                     showModalForm={this.state.showModalForm} levelData={this.state.levelData}
                     getLevelDetails={this.getLevelDetails} errorWarning={this.state.errorWarning}/>
        </div>
    );
  }
});

ReactDOM.render(
	<EditLevel />,
	document.getElementById('content')
);
