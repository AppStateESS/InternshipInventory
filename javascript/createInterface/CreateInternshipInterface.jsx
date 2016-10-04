import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import classNames from 'classnames';
//import typeahead from 'corejs-typeahead';
import Bloodhound from 'corejs-typeahead';

import InternationalDropDown from './InternationalDropDown.jsx';
import StateDropDown from './StateDropDown.jsx';

import ReactCSSTransitionGroup from 'react-addons-css-transition-group'

//var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;


var SearchBox = React.createClass({
    getInitialState: function() {
        return {dataError: null};
    },
    componentDidMount: function() {

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
    },
    componentWillUnmount: function() {
        var element = ReactDOM.findDOMNode(this);
        $(element).typeahead('destroy');
    },
    render: function() {
        var errorNotice = null;

        if(this.state.dataError !== null){
            errorNotice = <div style={{marginTop: "1em"}} className="alert alert-danger">
                                <p>{this.state.dataError}</p>
                            </div>
        }

        return (
            <div>
                <input type="search" name="studentId" id="studentSearch" className="form-control typeahead input-lg" placeholder="Banner ID, User name, or Full Name" ref="typeahead" autoComplete="off" autofocus/>
                {errorNotice}
            </div>
        );
    }
});

// Student Preview
var StudentPreview = React.createClass({
    render: function() {
        return (
            <div>
                <span className="lead"> {this.props.student.name}</span><br />
                <i className="fa fa-credit-card"></i> {this.props.student.studentId}<br />
                <i className="fa fa-envelope"></i> {this.props.student.email}<br />
                <i className="fa fa-graduation-cap"></i> {this.props.student.major}
            </div>
        );
    }
});

// Student Search Parent Component
var StudentSearch = React.createClass({
    getInitialState: function() {
        return {student: null, studentFound: false, hasError: false};
    },
    // Performs a search and handles the response
    doSearch: function(searchString) {
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
    },
    studentFound: function() {
        return this.state.studentFound;
    },
    // Clears results from current state, resets for next search
    resetPreview: function() {
        this.setState({student: null, studentFound: false});
    },
    setError: function(status) {
        this.setState({hasError: status});
    },
    render: function() {
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
});


/*********
 * Terms *
 *********/
var TermBlock = React.createClass({
    getInitialState: function() {
        return ({terms: null, hasError: false});
    },
    componentWillMount: function() {
        $.ajax({
            url: 'index.php?module=intern&action=GetAvailableTerms',
            dataType: 'json',
            success: function(data) {
                this.setState({terms: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });
    },
    setError: function(status){
        this.setState({hasError: status});
    },
    render: function() {
        var terms = this.state.terms;

        if(terms === null){
            return (<div></div>);
        }

        var fgClasses = classNames({
                        'form-group': true,
                        'has-error': this.state.hasError
                    });

        return (
            <div className="row">
                <div className="col-sm-12 col-md-6 col-md-push-3">
                    <div className={fgClasses} id="term">
                    <label htmlFor="term" className="control-label">Term</label><br />
                        <div className="btn-group" data-toggle="buttons">

                            {Object.keys(terms).map(function(key) {
                                return (
                                    <label className="btn btn-default" key={key}>
                                        <input type="radio" name="term" key={key} value={key} />{terms[key]}
                                    </label>
                                );
                            })}

                        </div>
                    </div>
                </div>
            </div>

        );
    }
});

/*************
 * Locations *
 *************/
var LocationBlock = React.createClass({
    getInitialState: function() {
        return ({
            domestic: null,
            international: null,
            availableStates: null,
            availableCountries: null,
            hasError: false
            });
    },
    componentDidMount: function() {
        // Fetch list of states
        $.ajax({
            url: 'index.php?module=intern&action=GetStates',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({availableStates: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });

        // Fetch list of available countries
        $.ajax({
            url: 'index.php?module=intern&action=GetAvailableCountries',
            dataType: 'json',
            success: function(data) {
                this.setState({availableCountries: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });
    },
    domestic: function() {
        this.setState({domestic: true, international: false});
    },
    international: function() {
        this.setState({domestic: false, international: true});
    },
    setError: function(status){
        this.setState({hasError: status});
    },
    render: function () {
        var fgClasses = classNames({
                        'form-group': true,
                        'has-error': this.state.hasError
                    });

        var dropdown;
        if(this.state.domestic === null) {
            dropdown = '';
        } else if (this.state.domestic) {
            dropdown = <StateDropDown key="states" ref="state" states={this.state.availableStates}/>;
        } else {
            dropdown = <InternationalDropDown key="countries" ref="country" countries={this.state.availableCountries}/>;
        }
        return (
            <div>
                <div className="row">
                    <div className="col-sm-12 col-md-6 col-md-push-3">
                        <div className={fgClasses} id="location">
                            <label htmlFor="location" className="control-label">Location</label> <br />
                            <div className="btn-group" data-toggle="buttons">
                                <label className="btn btn-default" onClick={this.domestic}>
                                    <input type="radio" name="location" defaultValue="domestic" />Domestic
                                </label>
                                <label className="btn btn-default" onClick={this.international}>
                                    <input type="radio" name="location" defaultValue="international" />International
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <ReactCSSTransitionGroup transitionName="example" transitionLeave={false} transitionEnterTimeout={500} >
                    {dropdown}
                </ReactCSSTransitionGroup>
            </div>
        );
    }
});


/***********************
 * Department Dropdown *
 ***********************/
var Department = React.createClass({
    getInitialState: function() {
        return {departments: null, hasError: false};
    },
    setError: function(status){
        this.setState({hasError: status});
    },
    componentWillMount: function() {
        $.ajax({
            url: 'index.php?module=intern&action=GetDepartments',
            dataType: 'json',
            success: function(data) {
                this.setState({departments: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });
    },
    render: function() {
        var departments = this.state.departments;
        if(departments === null) {
            return (<div></div>);
        }

        var fgClasses = classNames({
                        'form-group': true,
                        'has-error': this.state.hasError
                    });

        return (
            <div className="row">
                <div className="col-sm-12 col-md-4 col-md-push-3">
                    <div className={fgClasses} id="department">
                        <label htmlFor="department2" className="control-label">Department</label>
                        <select id="department2" name="department" className="form-control" defaultValue="-1">
                            {Object.keys(departments).map(function(key) {
                                return <option key={key} value={key}>{departments[key]}</option>;
                            })}
                        </select>
                    </div>
                </div>
            </div>
        );
    }
});


/*********************
 * Host Agency Field *
 *********************/
var HostAgency = React.createClass({
    getInitialState: function() {
        return ({hasError: false});
    },
    setError: function(status){
        this.setState({hasError: status});
    },
    render: function() {
        var fgClasses = classNames({
                        'form-group': true,
                        'has-error': this.state.hasError
                    });
        return (
            <div className="row">
                <div className="col-sm-12 col-md-4 col-md-push-3">
                    <div className={fgClasses} id="agency">
                        <label htmlFor="agency2" className="control-label">Internship Host</label>
                        <input type="text" id="agency2" name="agency" className="form-control" placeholder="Acme, Inc." />
                    </div>
                </div>
            </div>
        );
    }
});


/*****************
 * Submit Button *
 *****************/
var CreateInternshipButton = React.createClass({
    render: function() {
        var button = null;
        if(this.props.submitted) {
            button = <button type="submit" className="btn btn-lg btn-primary pull-right" id="create-btn" disabled ><i className="fa fa-spinner fa-spin"></i> Saving...</button>;
        } else {
            button = <button type="submit" className="btn btn-lg btn-primary pull-right" id="create-btn" onClick={this.handleClick} >Create Internship</button>;
        }
        return (
            <div className="row">
                <div className="col-sm-12 col-md-6 col-md-push-3">
                    {button}
                </div>
            </div>
        );
    }
});

var ErrorMessagesBlock = React.createClass({
    render: function() {
        if(this.props.errors === null){
            return '';
        }

        var errors = this.props.errors.map(function(message, i){
            return (
                <li key={i}>{message}</li>
            );
        });

        return (
            <div className="row">
                <div className="col-sm-12 col-md-6 col-md-push-3">
                    <div className="alert alert-danger" role="alert">
                        <p><i className="fa fa-exclamation-circle fa-2x"></i> Please select values for the following fields: </p>
                        <ul>
                            {errors}
                        </ul>
                    </div>
                </div>
            </div>
        );
    }
});

/*********************************
 * Top level Interface Component *
 *********************************/
var CreateInternshipInterface = React.createClass({
    getInitialState: function(){
        return ({submitted: false, errorMessages: null});
    },
    // Top-level onSubmit handler for the creation form
    handleSubmit: function(e) {
        // Stop the browser from immediately sending the post
        e.preventDefault();

        // Set submitted=true on the state to disable submit button and prevent double-submission
        var thisComponent = this; // Save a reference to 'this' for later use
        var formElement = e.target; // Save a reference to the form DOM nodes that were submitted

        this.setState({submitted: true, errorMessages: null}, function(){
            // After disabling submit buttons, use callback to validate the data
            if(!this.validate(formElement, thisComponent)){
                // If the data doesn't validate, wait a second before re-enabling the submit button
                // This makes sure the user sees the "Creating..." spinner, instead of it re-rendering
                // so fast that they don't think it did anything
                setTimeout(function(){
                    thisComponent.setState({submitted: false});
                }, 1000);

                return;
            }

            // If we get here, then validation was successful
            formElement.submit();
        });
    },
    validate: function(form, thisComponent) {

        // Assume everything is valid, change this if we detect otherwise
        var valid = true;
        var errors = [];

        // Check the student Component
        if(form.elements.studentId.value === '' || !thisComponent.refs.studentSearch.studentFound()){
            thisComponent.refs.studentSearch.setError(true);
            errors.push('Student ID');
            valid = false;
        }else{
            thisComponent.refs.studentSearch.setError(false);
        }

        // Check the term
        if(form.elements.term.value === ''){
            thisComponent.refs.termBlock.setError(true);
            errors.push('Term');
            valid = false;
        }else {
            thisComponent.refs.termBlock.setError(false);
        }

        // Check the location
        if(form.elements.location.value === ''){
            thisComponent.refs.locationBlock.setError(true);
            errors.push('Location');
            valid = false;
        }else{
            thisComponent.refs.locationBlock.setError(false);
        }

        // Check the location's state/internal drop down's value
        if(form.elements.location.value === 'domestic'){
            if(form.elements.state.value === '-1'){
                thisComponent.refs.locationBlock.refs.state.setError(true);
                errors.push('State');
                valid = false;
            }else{
                thisComponent.refs.locationBlock.refs.state.setError(false);
            }
        } else if(form.elements.location.value === 'international') {
            if(form.elements.country.value === '-1') {
                thisComponent.refs.locationBlock.refs.country.setError(true);
                errors.push('Country');
                valid = false;
            }else{
                thisComponent.refs.locationBlock.refs.country.setError(false);
            }
        }

        // Check the department
        if(form.elements.department.value === '_-1'){
            thisComponent.refs.department.setError(true);
            valid = false;
            errors.push('Department');
        }else{
            thisComponent.refs.department.setError(false);
        }

        // Check the host agency
        if(form.elements.agency.value === ''){
            thisComponent.refs.hostAgency.setError(true);
            valid = false;
            errors.push('Host Agency');
        }else{
            thisComponent.refs.hostAgency.setError(false);
        }

        if(errors.length !== 0){
            thisComponent.setErrorMessages(errors);
        }

        return valid;
    },
    setErrorMessages: function(messages) {
        this.setState({errorMessages: messages});
    },
    render: function() {
        var errors;
        if(this.state.errorMessages == null){
            errors = '';
        } else {
            errors = <ErrorMessagesBlock key="errorSet" errors={this.state.errorMessages} />
        }

        return (

            <form role="form" id="newInternshipForm" className="form-protected" action="index.php" method="post" onSubmit={this.handleSubmit}>
                <input type="hidden" name="module" value="intern"/>
                <input type="hidden" name="action" value="AddInternship"/>

                <ReactCSSTransitionGroup transitionName="example" transitionEnterTimeout={500} transitionLeaveTimeout={500}>
                    {errors}
                </ReactCSSTransitionGroup>

                <StudentSearch ref="studentSearch"/>

                <TermBlock ref="termBlock"/>

                <LocationBlock ref="locationBlock"/>

                <Department ref="department"/>

                <HostAgency ref="hostAgency"/>

                <CreateInternshipButton submitted={this.state.submitted}/>
            </form>
        );
    }
});

ReactDOM.render(
    <CreateInternshipInterface />, document.getElementById('createInternshipInterface')
);
