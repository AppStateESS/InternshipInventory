import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group';
import Calendar from 'react-calendar';

class ErrorMessagesBlock extends React.Component {
    render() {
        if (this.props.errors === null) {
            return '';
        }

        var errors = this.props.errors; // The error or success message.

        // If this is an error notification.
        if (this.props.messageType === "error") {
            return (
                <div className="row">
                    <div className="alert alert-warning" role="alert">
                        <strong>Warning! </strong> {errors}
                    </div>
                </div>
            );
        }
        // If this is a succes notification.
        else if (this.props.messageType === "success") {
            return (
                <div className="row">
                    <div className="alert alert-success alert-dismissable" role="alert">
                        <strong>Success! </strong> {errors}
                        <button type="button" className="close close-alert" data-dismiss="alert" aria-hidden="true">x</button>
                    </div>
                </div>
            );
        }
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
        this.onCancelSave = this.onCancelSave.bind(this);
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

        var newStype = ReactDOM.findDOMNode(this.refs.savedStype).value.trim();
        var newDescr = ReactDOM.findDOMNode(this.refs.savedDescr).value.trim();
        var newCensusDate = ReactDOM.findDOMNode(this.refs.savedCensusDate).value.trim();
        var newAvailDate = ReactDOM.findDOMNode(this.refs.savedAvailDate).value.trim();
        var newStartDate = ReactDOM.findDOMNode(this.refs.savedStartDate).value.trim();
        var newEndDate = ReactDOM.findDOMNode(this.refs.savedEndDate).value.trim();
        var newUgradOverload = ReactDOM.findDOMNode(this.refs.savedUgradOverload).value.trim();
        var newGradOverload = ReactDOM.findDOMNode(this.refs.savedGradOverload).value.trim();

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
        if (newUgradOverload === '') {
            newUgradOverload = this.props.ugradOverload;
        }
        if (newGradOverload === '') {
            newGradOverload = this.props.gradOverload;
        }

        this.props.onTermSave(newStype, newDescr, newCensusDate, newAvailDate, newStartDate, newEndDate, newUgradOverload, newGradOverload, this.props.tcode);
    }
    onCancelSave() {
        this.setState({editMode: false});
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
            mainButton = <a onClick={this.handleEdit} data-toggle="tooltip" title="Edit"><i className="glyphicon glyphicon-pencil"/></a>
            return (
            <tr>
                <td>{this.props.tcode}</td>
                <td>{this.props.stype}</td>
                <td>{this.props.descr}</td>
                <td>{censusDate}</td>
                <td>{availDate}</td>
                <td>{startDate}</td>
                <td>{endDate}</td>
                <td>{this.props.ugradOver}</td>
                <td>{this.props.gradOver}</td>
                <td>{mainButton}</td>
            </tr>
            );
        }
        //if you are editing
        else
        {
            mainButton = <a onClick={this.handleSave} data-toggle="tooltip" title="Save Changes"><i className="glyphicon glyphicon-floppy-save"/></a>
            return (
            <tr>
                <td><label type="text" ref="savedTcode">{this.props.tcode}</label></td>
                <td><input type="text" className="form-control" ref="savedStype" defaultValue={this.props.stype}/></td>
                <td><input type="text" className="form-control" ref="savedDescr" defaultValue={this.props.descr}/></td>
                <td><input type="text" className="form-control" ref="savedCensusDate" defaultValue={censusDate}/></td>
                <td><input type="text" className="form-control" ref="savedAvailDate" defaultValue={availDate}/></td>
                <td><input type="text" className="form-control" ref="savedStartDate" defaultValue={startDate}/></td>
                <td><input type="text" className="form-control" ref="savedEndDate" defaultValue={endDate}/></td>
                <td><input type="text" className="form-control" ref="savedUgradOverload" defaultValue={this.props.ugradOver}/></td>
                <td><input type="text" className="form-control" ref="savedGradOverload" defaultValue={this.props.gradOver}/></td>
                <td style={{"verticalAlign" : "middle"}}>{mainButton}</td>
                <td style={{"verticalAlign" : "middle"}}><a onClick={this.onCancelSave} title="Cancel Changes"><i className="glyphicon glyphicon-remove"/></a></td>
            </tr>
            );
        }
    }
}

class TermInput extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            censusDateInput: new Date(),
            availableDateInput: new Date(),
            startDateInput: new Date(),
            endDateInput: new Date(),
            showCalendarCensus: false,
            showCalendarAvailable: false,
            showCalendarStart: false,
            showCalendarEnd: false
        };

        this.onChangeCensus = this.onChangeCensus.bind(this);
        this.onChangeAvailable = this.onChangeAvailable.bind(this);
        this.onChangeStart = this.onChangeStart.bind(this);
        this.onChangeEnd = this.onChangeEnd.bind(this);
        this.showCalendarCensus = this.showCalendarCensus.bind(this);
        this.showCalendarAvailable = this.showCalendarAvailable.bind(this);
        this.showCalendarStart = this.showCalendarStart.bind(this);
        this.showCalendarEnd = this.showCalendarEnd.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }
    onChangeCensus(censusDateInput) {
      this.setState({censusDateInput: censusDateInput});
      (this.refs.census_date).value = censusDateInput.toLocaleDateString("en-US");
    }
    onChangeAvailable(availableDateInput) {
      this.setState({availableDateInput: availableDateInput});
      (this.refs.available_date).value = availableDateInput.toLocaleDateString("en-US");
    }
    onChangeStart(startDateInput) {
      this.setState({startDateInput: startDateInput});
      (this.refs.start_date).value = startDateInput.toLocaleDateString("en-US");
    }
    onChangeEnd(endDateInput) {
       this.setState({endDateInput: endDateInput});
       (this.refs.end_date).value = endDateInput.toLocaleDateString("en-US");
    }
    showCalendarCensus() {
        if (this.state.showCalendarCensus === false) {
            this.setState({showCalendarAvailable: false});
            this.setState({showCalendarStart: false});
            this.setState({showCalendarEnd: false});
        }
        this.setState({showCalendarCensus: !this.state.showCalendarCensus});
    }
    showCalendarAvailable() {
        if (this.state.showCalendarAvailable === false) {
            this.setState({showCalendarCensus: false});
            this.setState({showCalendarStart: false});
            this.setState({showCalendarEnd: false});
        }
        this.setState({showCalendarAvailable: !this.state.showCalendarAvailable});

    }
    showCalendarStart() {
        if (this.state.showCalendarStart === false) {
            this.setState({showCalendarCensus: false});
            this.setState({showCalendarAvailable: false});
            this.setState({showCalendarEnd: false});
        }
        this.setState({showCalendarStart: !this.state.showCalendarStart});

    }
    showCalendarEnd() {
        if (this.state.showCalendarEnd === false) {
            this.setState({showCalendarCensus: false});
            this.setState({showCalendarAvailable: false});
            this.setState({showCalendarStart: false});
        }
        this.setState({showCalendarEnd: !this.state.showCalendarEnd});

    }
    handleSubmit() {
        var tcode = ReactDOM.findDOMNode(this.refs.term_code).value.trim();
        var stype = ReactDOM.findDOMNode(this.refs.sem_type).value.trim();
        var descr = ReactDOM.findDOMNode(this.refs.description).value;
        var census = ReactDOM.findDOMNode(this.refs.census_date).value.trim();
        var available = ReactDOM.findDOMNode(this.refs.available_date).value.trim();
        var start = ReactDOM.findDOMNode(this.refs.start_date).value.trim();
        var end = ReactDOM.findDOMNode(this.refs.end_date).value.trim();
        var ugradOver = ReactDOM.findDOMNode(this.refs.undergrad_overload).value.trim();
        var gradOver = ReactDOM.findDOMNode(this.refs.grad_overload).value.trim();

        if (tcode !== '' && stype !== '' && descr !== '' && census !== '' &&
            available !== '' && start !== '' && end !== '' && ugradOver !== '' &&
            gradOver !== '') {
            this.refs.term_code.value = '';
            this.refs.sem_type.value = '';
            this.refs.description.value = '';
            this.refs.census_date.value = '';
            this.refs.available_date.value = '';
            this.refs.start_date.value = '';
            this.refs.end_date.value = '';
            this.refs.undergrad_overload.value = '';
            this.refs.grad_overload.value = '';
        }
        this.props.onTermCreate(tcode, stype, descr, census, available, start, end, ugradOver, gradOver);
    }
    render() {

      var censusCalendar = null;
      var availableCalendar = null;
      var startCalendar = null;
      var endCalendar = null;

      if (this.state.showCalendarCensus) {
          censusCalendar = <Calendar onChange={this.onChangeCensus}
                            value={this.state.censusDateInput} calendarType="US"/>
      }
      if (this.state.showCalendarAvailable) {
          availableCalendar = <Calendar onChange={this.onChangeAvailable}
                               value={this.state.availableDateInput} calendarType="US"/>
      }
      if (this.state.showCalendarStart) {
          startCalendar = <Calendar onChange={this.onChangeStart}
                           value={this.state.startDateInput} calendarType="US"/>
      }
      if (this.state.showCalendarEnd) {
          endCalendar = <Calendar onChange={this.onChangeEnd}
                         value={this.state.endDateInput} calendarType="US"/>
      }

      //Treated as census date to regular users but is actually the drop/add date
      return (
      <div className="form-group" style={{margin: '1em'}}>

          <div className="row">

              <div className="col-sm-3">
                  <div className="form-group">
                      <label>Term Code: </label>
                      <input type="text" className="form-control" placeholder="000000" ref="term_code"/>
                  </div>
              </div>

              <div className="col-sm-3">
                  <div className="form-group">
                      <label>Semester Type: </label>
                      <input type="text" className="form-control" placeholder="0" ref="sem_type"/>
                  </div>
              </div>

              <div className="col-sm-3">
                  <div className="form-group">
                      <label>Description: </label>
                      <input type="text" className="form-control" placeholder="Season 0 0000" ref="description"/>
                  </div>
              </div>

              <div className="col-sm-3">
                  <div className="form-group">
                      <label>Drop/Add Date:
                          <i className="fa fa-calendar" aria-hidden="true" onClick={this.showCalendarCensus}
                             style={{paddingLeft: '5px'}} title="Click for Calendar View"></i>
                      </label>
                      <input type="text" className="form-control" placeholder="00/00/0000" ref="census_date"/>
                      {censusCalendar}
                  </div>
              </div>

          </div>

          <div className="row">

              <div className="col-sm-3">
                  <div className="form-group">
                      <label>Available On Date:
                          <i className="fa fa-calendar" aria-hidden="true" onClick={this.showCalendarAvailable}
                             style={{paddingLeft: '5px'}} title="Click for Calendar View"></i>
                      </label>
                      <input type="text" className="form-control" placeholder="00/00/0000" ref="available_date"/>
                      {availableCalendar}
                  </div>
              </div>

              <div className="col-sm-3">
                  <div className="form-group">
                      <label>Start Date:
                          <i className="fa fa-calendar" aria-hidden="true" onClick={this.showCalendarStart}
                             style={{paddingLeft: '5px'}} title="Click for Calendar View"></i>
                      </label>
                      <input type="text" className="form-control" placeholder="00/00/0000" ref="start_date"/>
                      {startCalendar}
                  </div>
              </div>

              <div className="col-sm-3">
                  <div className="form-group">

                      <label>End Date:
                          <i className="fa fa-calendar" aria-hidden="true" onClick={this.showCalendarEnd}
                             style={{paddingLeft: '5px'}} title="Click for Calendar View"></i>
                      </label>
                      <input type="text" className="form-control" placeholder="00/00/0000" ref="end_date"/>
                      {endCalendar}
                  </div>
              </div>

              <div className="col-sm-3">
                  <div className="form-group">
                      <label>Undergraduate Overload Hours: </label>
                      <input type="text" className="form-control" placeholder="00" ref="undergrad_overload"/>
                  </div>
              </div>

          </div>

          <div className="row">

              <div className="col-sm-3">
                  <div className="form-group">
                      <label>Graduate Overload Hours: </label>
                      <input type="text" className="form-control" placeholder="00" ref="grad_overload"/>
                  </div>
              </div>

              <div className="col-sm-9">
                  <br></br>
                  <button type="button" className="btn btn-primary btn-block" onClick={this.handleSubmit}>Create Term</button>
              </div>

          </div>
      </div>);
    }
}

class TermSelector extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            mainData: null,
            errorWarning: null,
            messageType: null,
            inputData: null
        };

        this.dateToTimestamp = this.dateToTimestamp.bind(this);
        this.getData = this.getData.bind(this);
        this.onTermCreate = this.onTermCreate.bind(this);
        this.onTermSave = this.onTermSave.bind(this);
    }
    componentWillMount() {
        this.getData();
    }
    getData() {
        $.ajax({
            url: 'index.php?module=intern&action=termRest',
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
    onTermCreate(tcode, stype, descr, census, available, start, end, ugradOver, gradOver) {

        var errorMessage = null;

        if (tcode === '' || tcode.length !== 6) {
            errorMessage = "Please enter a term code with 6 digits.";
            this.setState({errorWarning: errorMessage, messageType: "error"});
            return;
        }
        if (stype === '' || stype.length !== 1) {
            errorMessage = "Please enter a semester type that is 1 digit long.";
            this.setState({errorWarning: errorMessage, messageType: "error"});
            return;
        }
        if (descr === '') {
            errorMessage = "Please enter a term description.";
            this.setState({errorWarning: errorMessage, messageType: "error"});
            return;
        }
        if (census === '') {
            errorMessage = "Please enter a drop/add date.";
            this.setState({errorWarning: errorMessage, messageType: "error"});
            return;
        }
        if (available === '') {
            errorMessage = "Please enter the date the term is available.";
            this.setState({errorWarning: errorMessage, messageType: "error"});
            return;
        }
        if (start === '') {
            errorMessage = "Please enter a start date.";
            this.setState({errorWarning: errorMessage, messageType: "error"});
            return;
        }
        if (end === '') {
            errorMessage = "Please enter an end date.";
            this.setState({errorWarning: errorMessage, messageType: "error"});
            return;
        }
        if (ugradOver === '' || ugradOver.length > 2) {
            errorMessage = "Please enter undergraduate overload hours or lower the number of hours.";
            this.setState({errorWarning: errorMessage, messageType: "error"});
            return;
        }
        if (gradOver === '' || gradOver.length > 2) {
            errorMessage = "Please enter graduate overload hours or lower the number of hours.";
            this.setState({errorWarning: errorMessage, messageType: "error"});
            return;
        }

        census = this.dateToTimestamp(census);
        available = this.dateToTimestamp(available);
        start = this.dateToTimestamp(start);
        end = this.dateToTimestamp(end);

        $.ajax({
            url: 'index.php?module=intern&action=termRest&code='+tcode+'&type='+stype+
            '&descr='+descr+'&census='+census+'&available='+available+'&start='+start+
            '&end='+end+'&ugradOver='+ugradOver+'&gradOver='+gradOver,
            type: 'POST',
            success: function(data) {
                this.getData();
                var message = "Term successfully added.";
                this.setState({errorWarning: message, messageType: "success"});
            }.bind(this),
            error: function(xhr, status, err) {
                var errorMessage = "Failed to add term.";
                console.error(this.props.url, status, err.toString());
                this.setState({errorWarning: errorMessage, messageType: "error"});
            }.bind(this)
        });
    }
    onTermSave(newsemtype, newdescri, newcensusd, newavaild, newstartd, newendd, newugradover, newgradover, tcode) {

        newcensusd = this.dateToTimestamp(newcensusd);
        newavaild = this.dateToTimestamp(newavaild);
        newstartd = this.dateToTimestamp(newstartd);
        newendd = this.dateToTimestamp(newendd);

        var cleantcode = encodeURIComponent(tcode)
        var cleansemtype = encodeURIComponent(newsemtype);
        var cleandescri = encodeURIComponent(newdescri);
        var cleancensusd = encodeURIComponent(newcensusd);
        var cleanavaild = encodeURIComponent(newavaild);
        var cleanstartd = encodeURIComponent(newstartd);
        var cleanendd = encodeURIComponent(newendd);
        var cleanugradover = encodeURIComponent(newugradover);
        var cleangradover = encodeURIComponent(newgradover);

        $.ajax({
            url: 'index.php?module=intern&action=termRest&newSemtype='+cleansemtype+
            '&newDesc='+cleandescri+'&newCensus='+cleancensusd+'&newAvail='+cleanavaild+'&newStart='+cleanstartd+
            '&newEnd='+cleanendd+'&newUgradOver='+cleanugradover+'&newGradOver='+cleangradover+'&tCode='+cleantcode,
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
    dateToTimestamp(dateString) {
        return new Date(dateString).getTime() / 1000;
    }
    render()
    {
        var data = null;
        var inData = null;
        if (this.state.mainData != null) {
            var termSave = this.onTermSave;
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
                        ugradOver={data.undergrad_overload_hours}
                        gradOver={data.grad_overload_hours}
                        onTermSave={termSave} />
                );
            });
            var termCreate = this.onTermCreate;
            inData = <TermInput onTermCreate={termCreate}
                                messageType={this.state.messageType} />

        } else {
            data = "";
            inData = "";
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
                        {inData}
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
                                    <th>Drop/Add Date</th>
                                    <th>Available On Date</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Undergraduate<br></br>Overload Hours</th>
                                    <th>Graduate<br></br>Overload Hours</th>
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
