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
        this.state = {
            editMode: false
        };

        this.handleEdit = this.handleEdit.bind(this);
        this.handleSave = this.handleSave.bind(this);
        this.timestampToDate = this.timestampToDate.bind(this);
    }
    timestampToDate(timestamp) {

        var date = new Date(timestamp * 1000);
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var day = date.getDate();
        var formattedDate = month + "/" + day + "/" + year;

        return formattedDate;
    }
    handleEdit() {
        this.setState({editMode: true});
    }
    handleSave() {
        this.setState({editMode: false});

        var newTcode = ReactDOM.findDOMNode(this.refs.savedTcode).value.trim();
        var newStype = ReactDOM.findDOMNode(this.refs.savedStype).value.trim();
        var newDescr = ReactDOM.findDOMNode(this.refs.savedDescr).value.trim();
        var newCensusDate = ReactDOM.findDOMNode(this.refs.savedCensusDate).value.trim();
        var newAvailDate = ReactDOM.findDOMNode(this.refs.savedAvailDate).value.trim();
        var newStartDate = ReactDOM.findDOMNode(this.refs.savedStartDate).value.trim();
        var newEndDate = ReactDOM.findDOMNode(this.refs.savedEndDate).value.trim();

        console.log(newTcode);
        //exit();

        if (newTcode === '') {
            newTcode = this.props.tcode;
        }
        if (newStype === '') {
            newStype = this.props.stype;
        }
        if (newDescr === '') {
            newDescr = this.props.descr;
        }
        if (newCensusDate === '') {
            newCensusDate = this.timestampToDate(this.props.census);
        }
        if (newAvailDate === '') {
            newAvailDate = this.timestampToDate(this.props.available);
        }
        if (newStartDate === '') {
            newStartDate = this.timestampToDate(this.props.start);
        }
        if (newEndDate === '') {
            newEndDate = this.timestampToDate(this.props.end);
        }

        var wholeTerm = {term: newTcode, stype: newStype, descr: newDescr,
                        census: newCensusDate, avail: newAvailDate, start: newStartDate,
                        end: newEndDate};

        this.props.onTermSave(newTcode, newStype, newDescr, newCensusDate, newAvailDate, newStartDate, newEndDate, this.props.tcode);

    }
    render() {

        var mainButton = null;

        let censusDate = this.timestampToDate(this.props.census);
        let availDate = this.timestampToDate(this.props.available);
        let startDate = this.timestampToDate(this.props.start);
        let endDate = this.timestampToDate(this.props.end);

        // if you are not editing
        if (!this.state.editMode)
        {
            mainButton = <a onClick={this.handleEdit} data-toggle="tooltip" title="Edit"><i className="fa fa-pencil"/></a>
            return (
            <tr>
                <td>{this.props.tcode}</td>
                <td>{this.props.stype}</td>
                <td>{this.props.descr}</td>
                <td>{censusDate}</td>
                <td>{availDate}</td>
                <td>{startDate}</td>
                <td>{endDate}</td>
                <td>{mainButton}</td>
            </tr>
            );
        }
        //if this.state.editMode ((true))
        //if you are editing
        else
        {
            mainButton = <a onClick={this.handleSave} data-toggle="tooltip" title="Save"><i className="glyphicon glyphicon-floppy-save"/></a>
            return (
            <tr>
                <td><input type="text" className="form-control" ref="savedTcode" defaultValue={this.props.tcode}/></td>
                <td><input type="text" className="form-control" ref="savedStype" defaultValue={this.props.stype}/></td>
                <td><input type="text" className="form-control" ref="savedDescr" defaultValue={this.props.descr}/></td>
                <td><input type="text" className="form-control" ref="savedCensusDate" defaultValue={censusDate}/></td>
                <td><input type="text" className="form-control" ref="savedAvailDate" defaultValue={availDate}/></td>
                <td><input type="text" className="form-control" ref="savedStartDate" defaultValue={startDate}/></td>
                <td><input type="text" className="form-control" ref="savedEndDate" defaultValue={endDate}/></td>
                <td style={{"verticalAlign" : "middle"}}>{mainButton}</td>
            </tr>
            );
        }
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
            messageType: null,
            //editing: false
        };

        //this.function blah
        this.dateToTimestamp = this.dateToTimestamp.bind(this);
        this.getData = this.getData.bind(this);
        this.onTermCreate = this.onTermCreate.bind(this);
        this.onTermSave = this.onTermSave.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        //this.sortList = this.sortList.bind(this);
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
        var displayedData = this.state.displayData;

        alert("Hello World you tried to add something");
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

        //displayedData = this.sortList(displayedData);
        this.setState({displayData: displayedData});
        //alert("Hello world");
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
                var errorMessage = "Failed to add term.";
                //alert("Failed to add the term.");
                console.error(this.props.url, status, err.toString());
                this.setState({errorWarning: errorMessage, messageType: "error"});
            }.bind(this)
        });
    }
    onTermSave(newtermc, newsemtype, newdescri, newcensusd, newavaild, newstartd, newendd, oldTcode) {

        newcensusd = this.dateToTimestamp(newcensusd);
        newavaild = this.dateToTimestamp(newavaild);
        newstartd = this.dateToTimestamp(newstartd);
        newendd = this.dateToTimestamp(newendd);
        console.log(newtermc);

        var cleanoldTcode = encodeURIComponent(oldTcode)
        var cleantermc = encodeURIComponent(newtermc);
        var cleansemtype = encodeURIComponent(newsemtype);
        var cleandescri = encodeURIComponent(newdescri);
        var cleancensusd = encodeURIComponent(newcensusd);
        var cleanavaild = encodeURIComponent(newavaild);
        var cleanstartd = encodeURIComponent(newstartd);
        var cleanendd = encodeURIComponent(newendd);

        alert(cleantermc);

        $.ajax({
            url: 'index.php?module=intern&action=termRest&newTcode='+cleantermc+'&newSemtype='+cleansemtype+
            '&newDesc='+cleandescri+'&newCensus='+cleancensusd+'&newAvail='+cleanavaild+'&newStart='+cleanstartd+
            '&newEnd='+cleanendd+'&oldTcode='+cleanoldTcode,
            type: 'PUT',
            success: function(data) {
                $("#success").show();
                var added = 'Updated the table.';
                this.setState({success: added});
                this.getData();
            }.bind(this),
            error: function(xhr, status, err) {
                var errorMessage = "Failed to update term.";
                console.error(this.props.url, status, err.toString());
                this.setState({errorWarning: errorMessage, messageType: "error"});
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
        return new Date(dateString).getTime() / 1000;
    }
    render()
    {
        var data = null;
        if (this.state.mainData != null) {
            var onTermSave = this.onTermSave;
            data = this.state.mainData.map(function (data) {
                return (
                    <TermRow  key={data.term}
                        tcode={data.term}
                        stype={data.semester_type}
                        descr={data.description}
                        census={data.census_date_timestamp}
                        available={data.available_on_timestamp}
                        start={data.start_timestamp}
                        end={data.end_timestamp}
                        onTermSave={onTermSave} />
                );
            });
        } else {
            data = "";
        }

        var errors;
        if (this.state.errorWarning == null) {
            errors = '';
        } else {
            errors = <ErrorMessagesBlock key="errorSet" errors={this.state.errorWarning} messageType={this.state.messageType} />
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
                                        <button className="btn btn-block" onClick={this.handleSubmit}>Create Term</button>
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
                                {data}
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
