import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
//import ReactCSSTransitionGroup from 'react-addons-css-transition-group'

//got from editAdmin.jsx

//error or notification block?


class TermRow extends React.Component {
    constructor(props) {
        super(props);
        //this.handleChange = this.handleChange.bind(this);
    }
    render () {
        return (
            <tr>
              <td></td>
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
                      code={sub.term_code}
                      descr={sub.descr}
                      census={sub.census}
                      start={sub.start}
                      end={sub.end} />
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
                  <th>Semester Type</th>
                  <th>Code</th>
                  <th>Description</th>
                  <th>Available Date</th>
                  <th>Census Date</th>
                  <th>Start Date</th>
                  <th>End Date</th>
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
    /*handleDrop(e) {
        this.setState({semesterType: e.target.value});
    }*/
    handleSubmit() {
        this.props.saveTerm(term_code, census, descr, available, start, end, type);//this.state.semesterType);
    }
    render() {
        return (
            <div className="panel panel-default">
              <div className="panel-body">
                <div className="row">
                  <div className="col-md-6">
                    <label>Semester Type: </label>
                    <input type="text" className="form-control" placeholder="0" ref="type" />
                  </div>
                  <div className="col-md-6">
                    <label>Code: </label>//label2
                    <input type="text" className="form-control" placeholder="00000" ref="term_code" />
                  </div>
                  <div className="col-md-6">
                    <label>Description: </label>//label3
                    <input type="text" className="form-control" placeholder="Season 20XX" ref="descr" />
                  </div>
                  <div className="col-md-6">
                    <label>Available: </label>//label4
                    <input type="text" className="form-control" placeholder="00/00/0000" ref="available" />
                  </div>
                  <div className="col-md-6">
                    <label>Census: </label>//label 5
                    <input type="text" className="form-control" placeholder="00/00/0000" ref="census" />
                  </div>
                  <div className="col-md-6">
                    <label>Start: </label>//label 6
                    <input type="text" className="form-control" placeholder="00/00/0000" ref="start" />
                  </div>
                  <div className="col-md-6"> //drop down for semester type here
                    <label>End: </label>
                    <input type="text" className="form-control" placeholder="00/00/0000" ref="end" />
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
        this.getTermData();
    }
    getData() {
        $.ajax({
            url: 'index.php?module=intern&action=TermRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({mainData: data});
            }.bind(this);
            error: function(xhr, status, err) {
                alert("Failed to grab term data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    // Adding a term includes the term code, census date, description,
    // available date, start date, end date, and semester type (1 - 4)
    saveTerm(term_code, census, descr, available, start, end, type) {
        $.ajax({
            url: 'index.php?module=intern&action=TermRest&term_code=' + term_code
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
              // notifications?

              <div className="row">
                <div className="col-lg-5">
                  <TermList /> //subjectData?
                </div>

                <div className="col-lg-5 col-lg-offset-1">
                  <CreateTerm /> //term, saveterm
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
