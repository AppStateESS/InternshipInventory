import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group';

class ErrorMessagesBlock extends React.Component {
    render() {
        if (this.props.errors === null) {
            return '';
        }

        var errors = this.props.errors;

        return (
            <div className="row">
                <div className="col-sm-12 col-md-6 col-md-push-3">
                    <div className="alert alert-warning" role="alert">
                        <p><i className="fa fa-exclamation-circle fa-2x"></i> Warning: {errors}</p>
                    </div>
                </div>
            </div>
        );
    }
}

class TermRow extends React.Component {
    constructor(props) {
        super(props);

        this.timestampToDate = this.timestampToDate.bind(this);
    }
    timestampToDate(timestamp) {
        //var temp = new Date(timestamp);
        //var date = temp.format("mm/dd/yyyy");
        var date = new Date(timestamp * 1000);
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var day = date.getDate();
        var formattedDate = month + "/" + day + "/" + year;

        return formattedDate;
    }
    //move to parent component and use in inTermCreate
    //date will come in as a string
    //dateToTimestamp(date) {
    //    var timestamp = new Date(date);
    //    return timestamp;
    //}
    render() {

      let censusDate = this.timestampToDate(this.props.census);
      let availDate = this.timestampToDate(this.props.available);
      let startDate = this.timestampToDate(this.props.start);
      let endDate = this.timestampToDate(this.props.end);

        return (
            <tr>
                <td>{this.props.tcode}</td>
                <td>{this.props.stype}</td>
                <td>{this.props.descr}</td>
                <td>{censusDate}</td>
                <td>{availDate}</td>
                <td>{startDate}</td>
                <td>{endDate}</td>
            </tr>
        )
    }
}

class TermSelector extends React.Component
{
    constructor(props) {
        super(props);
        this.state = {
            mainData: null,
            displayData: null,
            errorWarning: null,
            messageType: null
        };

        //this.function blah
        this.dateToTimestamp = this.getData.bind(this);
        this.getData = this.getData.bind(this);
        this.onTermCreate = this.onTermCreate.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }
    componentWillMount() {
        // Get the term data at the start of execution
        this.getData();
    }
    getData() {
        // Sends an ajax request to TermRest to grab the
        // display data.
        $.ajax({
            url: 'index.php?module=intern&action=termRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({mainData: data,
                          displayData: data});
            }.bind(this),
            error: function(xhr, status, err) {
              alert("Failed to grab term data.")
              console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    onTermCreate(tcode, stype, descr, census, available, start, end) {

        var errorMessage = null;
        var displayData

        if (tcode === '') {
            errorMessage = "Please enter a term code.";
            this.setState({errorWarning: errorMessage, messageType: "error"});
            return;
        }
        if (stype === '') {
            errorMessage = "Please enter a semester type.";
            this.setState({errorWarning: errorMessage});
            return;
        }
        if (descr === '') {
            errorMessage = "Please enter a term description.";
            this.setState({errorWarning: errorMessage});
            return;
        }
        if (census === '') {
            errorMessage = "Please enter a census date.";
            this.setState({errorWarning: errorMessage});
            return;
        }
        if (available === '') {
            errorMessage = "Please enter the date the term is available.";
            this.setState({errorWarning: errorMessage});
            return;
        }
        if (start === '') {
            errorMessage = "Please enter a start date.";
            this.setState({errorWarning: errorMessage});
            return;
        }
        if (end === '') {
            errorMessage = "Please enter an end date.";
            this.setState({errorWarning: errorMessage});
            return;
        }

        census = this.dateToTimestamp(census);
        available = this.dateToTimestamp(available);
        start = this.dateToTimestamp(start);
        end = this.dateToTimestamp(end);



        $.ajax({
            url: 'index.php?module=intern&action=termRest&code='+tcode+'&type='+stype+
            '&descr='+descr+'&census='+census+'&available='+available+'&start='+start+
            '&end='+end,
            type: 'POST',
            //dataType: 'json',
            success: function(data) {
                this.getData();
                var message = "Term successfully added.";
                this.setState({errorWarning: message, messageType: "success"});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to add term.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    handleSubmit() {
        var tcode = ReactDOM.findDOMNode(this.refs.term_code).value.trim();
        var stype = ReactDOM.findDOMNode(this.refs.sem_type).value.trim();
        var descr = ReactDOM.findDOMNode(this.refs.description).value;
        var census = ReactDOM.findDOMNode(this.refs.census_date).value.trim();
        var available = ReactDOM.findDOMNode(this.refs.available_date).value.trim();
        var start = ReactDOM.findDOMNode(this.refs.start_date).value.trim();
        var end = ReactDOM.findDOMNode(this.refs.end_date).value.trim();

        this.onTermCreate(tcode, stype, descr, census, available, start, end);
    }
    dateToTimestamp(dateString) {
        //var timestamp = Date.parse(dateString);
        //return timestamp
        return new Date(dateString).getTime() / 1000;
    }
    render()
    {
        var TermData = null;
        if (this.state.mainData != null) {
            TermData = this.state.displayData.map(function (term) {
                return (
                    <TermRow  key={term.term}
                        tcode={term.term}
                        stype={term.semester_type}
                        descr={term.description}
                        census={term.census_date_timestamp}
                        available={term.available_on_timestamp}
                        start={term.start_timestamp}
                        end={term.end_timestamp} />
                );
            });
        } else {
            TermData = "";
        }

        var errors;
        if (this.state.errorWarning == null) {
            errors = '';
        } else {
            errors = <ErrorMessageBlock key="errorSet" errors={this.state.errorWarning} messageType={this.state.messageType} />
        }
        return (
            <div className="terms">

              <ReactCSSTransitionGroup transitionName="example" transitionEnterTimeout={500} transitionLeaveTimeout={500}>
                  {errors}
              </ReactCSSTransitionGroup>

                <h3>Add Term: </h3>
                <div className="addTerm">
                    <div className="panel panel-default">
                        <div className="form-group" style={{margin: '1em'}}>
                            <div className="row">
                                <div className="col-lg-3">
                                    <div className="form-group">
                                        <label>Term Code: </label>
                                        <input type="text" className="form-control" placeholder="00000" ref="term_code"/>
                                        <br></br>
                                    </div>
                                </div>
                                <div className="col-lg-3">
                                    <div className="form-group">
                                        <label>Semester Type: </label>
                                        <input type="text" className="form-control" placeholder="0" ref="sem_type"/>
                                        <br></br>
                                    </div>
                                </div>
                                <div className="col-lg-3">
                                    <div className="form-group">
                                        <label>Description: </label>
                                        <input type="text" className="form-control" placeholder="Season 0 0000" ref="description"/>
                                        <br></br>
                                    </div>
                                </div>
                                <div className="col-lg-3">
                                    <div className="form-group">
                                        <label>Census Date: </label>
                                        <input type="text" className="form-control" placeholder="00/00/0000" ref="census_date"/>
                                        <br></br>
                                    </div>
                                </div>

                            </div>
                            <div className="row">
                                <div className="col-lg-3">
                                    <div className="form-group">
                                        <label>Available On Date: </label>
                                        <br></br>
                                        <input type="text" className="form-control" placeholder="00/00/0000" ref="available_date"/>
                                    </div>
                                </div>
                                <div className="col-lg-3">
                                    <div className="form-group">
                                        <label>Start Date: </label>
                                        <br></br>
                                        <input type="text" className="form-control" placeholder="00/00/0000" ref="start_date"/>
                                    </div>
                                </div>
                                <div className="col-lg-3">
                                    <div className="form-group">
                                        <label>End Date: </label>
                                        <br></br>
                                        <input type="text" className="form-control" placeholder="00/00/0000" ref="end_date"/>
                                    </div>
                                </div>
                                <div className="col-lg-3">
                                    <div className="form-group">
                                        <br></br>
                                        <button className="btn btn-block" onClick={this.handleSubmit}><strong>Create Term</strong></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br></br>
                <h3>Current Terms:</h3>
                <div className="termTable">
                    <div className="row">
                        <table className="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>Term Code</th>
                                    <th>Semester Type</th>
                                    <th>Description</th>
                                    <th>Census Date</th>
                                    <th>Available On Date</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                {TermData}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        );
    }
}


ReactDOM.render(
    <TermSelector />,
    document.getElementById('edit_terms')
);
