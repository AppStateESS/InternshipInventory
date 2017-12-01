import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';

class TermRow extends React.Component {
    constructor(props) {
        super(props);
    }
    render() {
        return (
            <tr>
                <td>{this.props.tcode}</td>
                <td>{this.props.stype}</td>
                <td>{this.props.descr}</td>
                <td>{this.props.census}</td>
                <td>{this.props.available}</td>
                <td>{this.props.start}</td>
                <td>{this.props.end}</td>
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
        this.getData = this.getData.bind(this);
        this.onTermCreate = this.onTermCreate.bind(this);
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
        $.ajax({
            url: 'index.php?module=intern&action=termRest&code='+tcode+'&type='+stype+
            '&descr='+descr+'&census='+census+'&available='+available+'&start='+start+
            '&end='+end,
            type: 'POST',
            dataType: 'json',
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
    render()
    {
        var TermData = null;
        if (this.state.mainData != null) {
            TermData = this.state.displayData.map(function (term) {
                return (
                    <TermRow tcode={term.term}
                        stype={term.semester_type}
                        descr={term.description}
                        census={term.census_date_timestamp}
                        available={term.available_on_timestamp}
                        start={term.start_timestamp}
                        end={term.end_timestamp} />
                );
            });
        }
        return (
            <div className="terms">
                <h3>Add Term: </h3>
                <div className="addTerm">
                    <div className="panel panel-default">
                        <div className="form-group" style={{margin: '1em'}}>
                            <div className="row">
                                <div className="col-lg-3">
                                    <label>Term Code: </label>
                                    <input type="text" className="form-control" placeholder="00000"/>
                                    <br></br>
                                </div>
                                <div className="col-lg-3">
                                    <label>Semester Type: </label>
                                    <input type="text" className="form-control" placeholder="0"/>
                                    <br></br>
                                </div>
                                <div className="col-lg-3">
                                    <label>Description: </label>
                                    <input type="text" className="form-control" placeholder="Season 0 0000"/>
                                    <br></br>
                                </div>
                                <div className="col-lg-3">
                                    <label>Census Date: </label>
                                    <input type="text" className="form-control" placeholder="00/00/0000"/>
                                    <br></br>
                                </div>

                            </div>
                            <div className="row">
                                <div className="col-lg-3">
                                    <label>Available On Date: </label>
                                    <br></br>
                                    <input type="text" className="form-control" placeholder="00/00/0000"/>
                                </div>
                                <div className="col-lg-3">
                                    <label>Start Date: </label>
                                    <br></br>
                                    <input type="text" className="form-control" placeholder="00/00/0000"/>
                                </div>
                                <div className="col-lg-3">
                                    <label>End Date: </label>
                                    <br></br>
                                    <input type="text" className="form-control" placeholder="00/00/0000"/>
                                </div>
                                <div className="col-lg-3">
                                    <div className="form-group">
                                        <br></br>
                                        <button className="btn btn-block" onClick=""><strong>Create Term</strong></button>
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
