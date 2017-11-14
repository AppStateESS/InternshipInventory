import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
//import ReactCSSTransitionGroup from 'react-addons-css-transition-group'

//got from editAdmin.jsx

//error or notification block?


class TermRow extends React.Component {
    render () {
          return
    }
}

class TermList extends React.Component {
    render() {
        var tRow = null;

        if ()

        return (
            <table className="table table-condensed table-striped">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Description</th>
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

        this.state = {semesterType: "_-1"};

        //this.handleDrop = this.handleDrop.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }
    handleDrop(e) {
        this.setState({semesterType: e.target.value});
    }
    handleSubmit() {
        this.props.saveTerm(term_code, census, descr, available, start, end, this.state.semesterType);
    }
    render() {
        return (
            <div className="panel panel-default">
              <div className="panel-body">
                <div className="row">
                  <div className="col-md-6">
                    <label></label>

                  </div>
                  <div className="col-md-6">
                    //label2
                  </div>
                  <div className="col-md-6">
                    //label3
                  </div>
                  <div className="col-md-6">
                    //label4
                  </div>
                  <div className="col-md-6">
                    //label 5
                  </div>
                  <div className="col-md-6">
                    //label 6
                  </div>
                  <div className="col-md-6"> //drop down for semester type here

                  </div>
                </div>
                <div className="row">

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
                this.getTermData();

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
