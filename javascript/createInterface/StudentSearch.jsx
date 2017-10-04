import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import classNames from 'classnames';
import Bloodhound from 'corejs-typeahead';

class SearchBox extends React.Component {
    constructor(props) {
      super(props);

      this.state = {dataError: null};
    }
    componentDidMount() {

    	var searchSuggestions = new Bloodhound({
            datumTokenizer: function(datum){
                var nameTokens      = Bloodhound.tokenizers.obj.whitespace('name');
                var studentIdTokens = Bloodhound.tokenizers.obj.whitespace('studentId');
                var usernameTokens  = Bloodhound.tokenizers.obj.whitespace('email');

                return nameTokens.concat(studentIdTokens).concat(usernameTokens);
            },
    		queryTokenizer: Bloodhound.tokenizers.whitespace,
    		remote: {
                url: 'index.php?module=intern&action=GetSearchSuggestions&searchString=%QUERY',
                wildcard: '%QUERY'
            }
    	});

        var myComponent = this;
        var element = this.refs.typeahead;
        $(element).typeahead({
            minLength: 3,
            highlight: true,
            hint: true
        },
        {
        	name: 'students',
        	display: 'studentId',
        	source: searchSuggestions.ttAdapter(),
            limit: 15,
        	templates: {
        		suggestion: function(row) {
                    if(row.error === undefined){
                        return ('<p>'+row.name + ' &middot; ' + row.studentId + '</p>');
                    } else {
                        myComponent.setState({dataError: row.error});
                        return ('<p></p>');
                    }
        		}
        	}
        });

        // Event handler for selecting a suggestion
        var handleSearch = this.props.onSelect;
        $(element).bind('typeahead:select', function(obj, datum, name) {
            if(datum.error === undefined){
                handleSearch(datum.studentId);
            }
        });

        // Event handler for enter key.. Search with whatever the person put in the box
        var handleReset = this.props.onReset;
        var thisElement = this;
        $(element).keydown(function(e){

            // Look for the enter key
            if(e.keyCode === 13) {
                // Prevent default to keep the form from being submitted on enter
                e.preventDefault();
                return;
            }

            // Ignore the tab key
            if(e.keyCode === 9){
                return;
            }

            // For any other key, reset the search results because the input box has changed
            thisElement.setState({dataError: null});
            handleReset();
        });

        // Do a search onBlur too (in case user tabs away from the search field)
        $(element).blur(function(e){
            handleSearch($(element).typeahead('val'));
        });
    }
    componentWillUnmount() {
        var element = ReactDOM.findDOMNode(this);
        $(element).typeahead('destroy');
    }
    render() {
        var errorNotice = null;

        if(this.state.dataError !== null){
            errorNotice = <div style={{marginTop: "1em"}} className="alert alert-danger">
                                <p>{this.state.dataError}</p>
                            </div>
        }

        return (
            <div>
                <div>
                    <input type="search" name="studentId" id="studentSearch" className="form-control typeahead input-lg" placeholder="Banner ID, User name, or Full Name" ref="typeahead" autoComplete="off" autoFocus={true}/>
                </div>
                {errorNotice}
            </div>
        );
    }
}

// Student Preview
class StudentPreview extends React.Component {
    render() {
        return (
            <div>
                <span className="lead"> {this.props.student.name}</span><br />
                <i className="fa fa-credit-card"></i> {this.props.student.studentId}<br />
                <i className="fa fa-envelope"></i> {this.props.student.email}<br />
                <i className="fa fa-graduation-cap"></i> {this.props.student.major}
            </div>
        );
    }
}

// Student Search Parent Component
class StudentSearch extends React.Component {
    constructor(props) {
      super(props);

      this.state = {student: null, studentFound: false, hasError: false};

      this.resetPreview = this.resetPreview.bind(this);
      this.doSearch = this.doSearch.bind(this);
    }
    // Performs a search and handles the response
    doSearch(searchString) {
        $.ajax({
            url: 'index.php?module=intern&action=GetSearchSuggestions',
            dataType: 'json',
            data: {searchString: searchString},
            success: function(data) {
                if(data.length === 1 && data[0].error === undefined) {
                    this.setState({student:data[0], studentFound: true, hasError: false});
                }
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });
    }
    studentFound() {
        return this.state.studentFound;
    }
    // Clears results from current state, resets for next search
    resetPreview() {
        this.setState({student: null, studentFound: false});
    }
    setError(status) {
        this.setState({hasError: status});
    }
    render() {
        var fgClasses = classNames({
            'form-group': true,
            'has-success': this.state.studentFound || this.state.hasError,
            'has-feedback': this.state.studentFound,
            'has-error': this.state.hasError
        });
        return (

            <div className="row">
                <div className="col-sm-12 col-md-6 col-md-push-3">
                    <div className="panel panel-default">
                        <div className="panel-body">
                            <h3 style={{marginTop: "0"}}><i className="fa fa-user"></i> Student</h3>
                            <div className="row">
                                <div className="col-sm-12 col-md-10 col-md-push-1">
                                    <div className={fgClasses} id="studentId">
                                        <label htmlFor="studentId2" className="sr-only">Banner ID, User name, or Full Name</label>
                                        <SearchBox onSelect={this.doSearch} onReset={this.resetPreview}/>
                                        {this.state.studentFound ? <span className="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span> : null }
                                        {this.state.studentFound ? <span id="inputSuccess2Status" className="sr-only">(success)</span> : null }
                                    </div>

                                    {this.state.studentFound ? <StudentPreview student={this.state.student}/> : null }
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default StudentSearch;
