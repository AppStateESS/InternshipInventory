import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
//import ReactCSSTransitionGroup from 'react-addons-css-transition-group'

//got from editAdmin.jsx

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

class TermRow extends React.Component {
    constructor(props) {
        super(props);
        //this.handleChange = this.handleChange.bind(this);
    }
    render () {
        return (
            <tr>
              <td> {this.props.code} {this.props.census} {this.props.descr}
                    {this.props.available} {this.props.start} {this.props.end}
                    {this.props.type} </td>
            </tr>
        );
    }
}

class TermList extends React.Component {
    render() {
        var tRow = null;

        if (this.props.mainData != null) {
            tRow = this.props.mainData.map(function(sub) {
              return (
                <TermRow
                      code={sub.code}
                      census={sub.census_date_timestamp}
                      descr={sub.description}
                      available={sub.available_on_timestamp}
                      start={sub.start_timestamp}
                      end={sub.end_timestamp}
                      type={sub.semester_type} />
              );
            }); // what is sub?
        }
        else {
          tRow = null;
        }

        return (
            <table className="table table-condensed table-striped">
              <thead>
                <tr>
                  <th>Term Code</th>
                  <th>Census Date</th>
                  <th>Description</th>
                  <th>Available Date</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Semester Type</th>
                </tr>
              </thead>
              <tbody>
                {tRow}
              </tbody>
            </table>
        );
    }
}

class CreateTerm extends React.Component {
    constructor(props) {
        super(props);

        //this.state = {semesterType: "_-1"};

        //this.handleDrop = this.handleDrop.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }
    // make it show up at all before making drop down.
    //TO DO
    //handleDrop(e) {
    //    this.setState({semesterType: e.target.value});
    //}
    handleSubmit() {
        this.props.saveTerm(code, census, descr, available, start, end, type);//this.state.semesterType);
    }
    render() {
        return (
            <div className="panel panel-default">
              <div className="panel-body">
                <div className="row">
                  <div className="col-md-6">
                    <label>Term Code: </label>
                    <input type="text" className="form-control" placeholder="0" ref="code" />
                  </div>
                  <div className="col-md-6">
                    <label>Census: </label>
                    <input type="text" className="form-control" placeholder="00/00/0000" ref="census" />
                  </div>
                  <div className="col-md-6">
                    <label>Description: </label>
                    <input type="text" className="form-control" placeholder="Season 20XX" ref="descr" />
                  </div>
                  <div className="col-md-6">
                    <label>Available: </label>
                    <input type="text" className="form-control" placeholder="00/00/0000" ref="available" />
                  </div>
                  <div className="col-md-6">
                    <label>Start: </label>
                    <input type="text" className="form-control" placeholder="00/00/0000" ref="start" />
                  </div>
                  <div className="col-md-6">
                    <label>End: </label>
                    <input type="text" className="form-control" placeholder="00/00/0000" ref="end" />
                  </div>
                  <div className="col-md-6">
                    <label>Semester Type: </label>
                    <input type="text" className="form-control" placeholder="00000" ref="type" />
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
        )
    }
}

class TermSelector extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            mainData: null,
            //notification?
        };

        this.getData = this.getData.bind(this);
        this.saveTerm = this.saveTerm.bind(this);
        this.editTerm = this.editTerm.bind(this);
    }
    componentDidMount() {
        this.getData();
    }
    getData() {
        $.ajax({
            url: 'index.php?module=intern&action=TermRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({mainData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab term data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    // Adding a term includes the term code, census date, description,
    // available date, start date, end date, and semester type (1 - 4)
    saveTerm(code, census, descr, available, start, end, type) {
        $.ajax({
            url: 'index.php?module=intern&action=TermRest&code=' + code
                + '&census=' + census + '&descr=' + descr + '&available=' + available
                + '&start=' + start + '&end=' + end + '&type=' + type,
            type: 'POST',
            success: function() {
                this.getData();

                // success message
                // idk what to do here yet
            }.bind(this),
            error: function(http) {
                // error message
                //idk what to do here either
            }.bind(this)
        });
    }

    render() {
        return (
            <div>
              <Notifications msg={this.state.msgNotification} msgType={this.state.msgType}/>

              <div className="row">
                <div className="col-lg-5">
                  <TermList subjectData={this.state.mainData}/> //subjectData?
                    hIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
                </div>

                <div className="col-lg-5 col-lg-offset-1">
                  <CreateTerm saveTerm={this.saveTerm}/> //term, saveterm
                </div>
              </div>
            </div>
        );
    }
}



ReactDOM.render(
    <TermSelector />,
    document.getElementById('edit_terms')// what is supposed to go in here?
);
