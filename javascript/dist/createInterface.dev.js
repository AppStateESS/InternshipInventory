/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/*!******************************************************************!*\
  !*** ./javascript/createInterface/CreateInternshipInterface.jsx ***!
  \******************************************************************/
/***/ function(module, exports) {

	import React from 'react';
	import ReactDOM from 'react-dom';
	import $ from 'jquery';
	import classNames from 'classnames';
	//import typeahead from 'corejs-typeahead';
	import Bloodhound from 'corejs-typeahead';
	
	import InternationalDropDown from './InternationalDropDown.jsx';
	import StateDropDown from './StateDropDown.jsx';
	
	import ReactCSSTransitionGroup from 'react-addons-css-transition-group';
	
	//var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;
	
	
	var SearchBox = React.createClass({
	    displayName: 'SearchBox',
	
	    getInitialState: function () {
	        return { dataError: null };
	    },
	    componentDidMount: function () {
	
	        var searchSuggestions = new Bloodhound({
	            datumTokenizer: function (datum) {
	                var nameTokens = Bloodhound.tokenizers.obj.whitespace('name');
	                var studentIdTokens = Bloodhound.tokenizers.obj.whitespace('studentId');
	                var usernameTokens = Bloodhound.tokenizers.obj.whitespace('email');
	
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
	        }, {
	            name: 'students',
	            display: 'studentId',
	            source: searchSuggestions.ttAdapter(),
	            limit: 15,
	            templates: {
	                suggestion: function (row) {
	                    if (row.error === undefined) {
	                        return '<p>' + row.name + ' &middot; ' + row.studentId + '</p>';
	                    } else {
	                        myComponent.setState({ dataError: row.error });
	                        return '<p></p>';
	                    }
	                }
	            }
	        });
	
	        // Event handler for selecting a suggestion
	        var handleSearch = this.props.onSelect;
	        $(element).bind('typeahead:select', function (obj, datum, name) {
	            if (datum.error === undefined) {
	                handleSearch(datum.studentId);
	            }
	        });
	
	        // Event handler for enter key.. Search with whatever the person put in the box
	        var handleReset = this.props.onReset;
	        var thisElement = this;
	        $(element).keydown(function (e) {
	
	            // Look for the enter key
	            if (e.keyCode === 13) {
	                // Prevent default to keep the form from being submitted on enter
	                e.preventDefault();
	                return;
	            }
	
	            // Ignore the tab key
	            if (e.keyCode === 9) {
	                return;
	            }
	
	            // For any other key, reset the search results because the input box has changed
	            thisElement.setState({ dataError: null });
	            handleReset();
	        });
	
	        // Do a search onBlur too (in case user tabs away from the search field)
	        $(element).blur(function (e) {
	            handleSearch($(element).typeahead('val'));
	        });
	    },
	    componentWillUnmount: function () {
	        var element = ReactDOM.findDOMNode(this);
	        $(element).typeahead('destroy');
	    },
	    render: function () {
	        var errorNotice = null;
	
	        if (this.state.dataError !== null) {
	            errorNotice = React.createElement(
	                'div',
	                { style: { marginTop: "1em" }, className: 'alert alert-danger' },
	                React.createElement(
	                    'p',
	                    null,
	                    this.state.dataError
	                )
	            );
	        }
	
	        return React.createElement(
	            'div',
	            null,
	            React.createElement('input', { type: 'search', name: 'studentId', id: 'studentSearch', className: 'form-control typeahead input-lg', placeholder: 'Banner ID, User name, or Full Name', ref: 'typeahead', autoComplete: 'off', autofocus: true }),
	            errorNotice
	        );
	    }
	});
	
	// Student Preview
	var StudentPreview = React.createClass({
	    displayName: 'StudentPreview',
	
	    render: function () {
	        return React.createElement(
	            'div',
	            null,
	            React.createElement(
	                'span',
	                { className: 'lead' },
	                ' ',
	                this.props.student.name
	            ),
	            React.createElement('br', null),
	            React.createElement('i', { className: 'fa fa-credit-card' }),
	            ' ',
	            this.props.student.studentId,
	            React.createElement('br', null),
	            React.createElement('i', { className: 'fa fa-envelope' }),
	            ' ',
	            this.props.student.email,
	            React.createElement('br', null),
	            React.createElement('i', { className: 'fa fa-graduation-cap' }),
	            ' ',
	            this.props.student.major
	        );
	    }
	});
	
	// Student Search Parent Component
	var StudentSearch = React.createClass({
	    displayName: 'StudentSearch',
	
	    getInitialState: function () {
	        return { student: null, studentFound: false, hasError: false };
	    },
	    // Performs a search and handles the response
	    doSearch: function (searchString) {
	        $.ajax({
	            url: 'index.php?module=intern&action=GetSearchSuggestions',
	            dataType: 'json',
	            data: { searchString: searchString },
	            success: function (data) {
	                if (data.length === 1 && data[0].error === undefined) {
	                    this.setState({ student: data[0], studentFound: true, hasError: false });
	                }
	            }.bind(this),
	            error: function (xhr, status, err) {
	                console.error(status, err.toString());
	            }
	        });
	    },
	    studentFound: function () {
	        return this.state.studentFound;
	    },
	    // Clears results from current state, resets for next search
	    resetPreview: function () {
	        this.setState({ student: null, studentFound: false });
	    },
	    setError: function (status) {
	        this.setState({ hasError: status });
	    },
	    render: function () {
	        var fgClasses = classNames({
	            'form-group': true,
	            'has-success': this.state.studentFound || this.state.hasError,
	            'has-feedback': this.state.studentFound,
	            'has-error': this.state.hasError
	        });
	        return React.createElement(
	            'div',
	            { className: 'row' },
	            React.createElement(
	                'div',
	                { className: 'col-sm-12 col-md-6 col-md-push-3' },
	                React.createElement(
	                    'div',
	                    { className: 'panel panel-default' },
	                    React.createElement(
	                        'div',
	                        { className: 'panel-body' },
	                        React.createElement(
	                            'h3',
	                            { style: { marginTop: "0" } },
	                            React.createElement('i', { className: 'fa fa-user' }),
	                            ' Student'
	                        ),
	                        React.createElement(
	                            'div',
	                            { className: 'row' },
	                            React.createElement(
	                                'div',
	                                { className: 'col-sm-12 col-md-10 col-md-push-1' },
	                                React.createElement(
	                                    'div',
	                                    { className: fgClasses, id: 'studentId' },
	                                    React.createElement(
	                                        'label',
	                                        { htmlFor: 'studentId2', className: 'sr-only' },
	                                        'Banner ID, User name, or Full Name'
	                                    ),
	                                    React.createElement(SearchBox, { onSelect: this.doSearch, onReset: this.resetPreview }),
	                                    this.state.studentFound ? React.createElement('span', { className: 'glyphicon glyphicon-ok form-control-feedback', 'aria-hidden': 'true' }) : null,
	                                    this.state.studentFound ? React.createElement(
	                                        'span',
	                                        { id: 'inputSuccess2Status', className: 'sr-only' },
	                                        '(success)'
	                                    ) : null
	                                ),
	                                this.state.studentFound ? React.createElement(StudentPreview, { student: this.state.student }) : null
	                            )
	                        )
	                    )
	                )
	            )
	        );
	    }
	});
	
	/*********
	 * Terms *
	 *********/
	var TermBlock = React.createClass({
	    displayName: 'TermBlock',
	
	    getInitialState: function () {
	        return { terms: null, hasError: false };
	    },
	    componentWillMount: function () {
	        $.ajax({
	            url: 'index.php?module=intern&action=GetAvailableTerms',
	            dataType: 'json',
	            success: function (data) {
	                this.setState({ terms: data });
	            }.bind(this),
	            error: function (xhr, status, err) {
	                console.error(status, err.toString());
	            }
	        });
	    },
	    setError: function (status) {
	        this.setState({ hasError: status });
	    },
	    render: function () {
	        var terms = this.state.terms;
	
	        if (terms === null) {
	            return React.createElement('div', null);
	        }
	
	        var fgClasses = classNames({
	            'form-group': true,
	            'has-error': this.state.hasError
	        });
	
	        return React.createElement(
	            'div',
	            { className: 'row' },
	            React.createElement(
	                'div',
	                { className: 'col-sm-12 col-md-6 col-md-push-3' },
	                React.createElement(
	                    'div',
	                    { className: fgClasses, id: 'term' },
	                    React.createElement(
	                        'label',
	                        { htmlFor: 'term', className: 'control-label' },
	                        'Term'
	                    ),
	                    React.createElement('br', null),
	                    React.createElement(
	                        'div',
	                        { className: 'btn-group', 'data-toggle': 'buttons' },
	                        Object.keys(terms).map(function (key) {
	                            return React.createElement(
	                                'label',
	                                { className: 'btn btn-default', key: key },
	                                React.createElement('input', { type: 'radio', name: 'term', key: key, value: key }),
	                                terms[key]
	                            );
	                        })
	                    )
	                )
	            )
	        );
	    }
	});
	
	/*************
	 * Locations *
	 *************/
	var LocationBlock = React.createClass({
	    displayName: 'LocationBlock',
	
	    getInitialState: function () {
	        return {
	            domestic: null,
	            international: null,
	            availableStates: null,
	            availableCountries: null,
	            hasError: false
	        };
	    },
	    componentDidMount: function () {
	        // Fetch list of states
	        $.ajax({
	            url: 'index.php?module=intern&action=GetStates',
	            type: 'GET',
	            dataType: 'json',
	            success: function (data) {
	                this.setState({ availableStates: data });
	            }.bind(this),
	            error: function (xhr, status, err) {
	                console.error(status, err.toString());
	            }
	        });
	
	        // Fetch list of available countries
	        $.ajax({
	            url: 'index.php?module=intern&action=GetAvailableCountries',
	            dataType: 'json',
	            success: function (data) {
	                this.setState({ availableCountries: data });
	            }.bind(this),
	            error: function (xhr, status, err) {
	                console.error(status, err.toString());
	            }
	        });
	    },
	    domestic: function () {
	        this.setState({ domestic: true, international: false });
	    },
	    international: function () {
	        this.setState({ domestic: false, international: true });
	    },
	    setError: function (status) {
	        this.setState({ hasError: status });
	    },
	    render: function () {
	        var fgClasses = classNames({
	            'form-group': true,
	            'has-error': this.state.hasError
	        });
	
	        var dropdown;
	        if (this.state.domestic === null) {
	            dropdown = '';
	        } else if (this.state.domestic) {
	            dropdown = React.createElement(StateDropDown, { key: 'states', ref: 'state', states: this.state.availableStates });
	        } else {
	            dropdown = React.createElement(InternationalDropDown, { key: 'countries', ref: 'country', countries: this.state.availableCountries });
	        }
	        return React.createElement(
	            'div',
	            null,
	            React.createElement(
	                'div',
	                { className: 'row' },
	                React.createElement(
	                    'div',
	                    { className: 'col-sm-12 col-md-6 col-md-push-3' },
	                    React.createElement(
	                        'div',
	                        { className: fgClasses, id: 'location' },
	                        React.createElement(
	                            'label',
	                            { htmlFor: 'location', className: 'control-label' },
	                            'Location'
	                        ),
	                        ' ',
	                        React.createElement('br', null),
	                        React.createElement(
	                            'div',
	                            { className: 'btn-group', 'data-toggle': 'buttons' },
	                            React.createElement(
	                                'label',
	                                { className: 'btn btn-default', onClick: this.domestic },
	                                React.createElement('input', { type: 'radio', name: 'location', defaultValue: 'domestic' }),
	                                'Domestic'
	                            ),
	                            React.createElement(
	                                'label',
	                                { className: 'btn btn-default', onClick: this.international },
	                                React.createElement('input', { type: 'radio', name: 'location', defaultValue: 'international' }),
	                                'International'
	                            )
	                        )
	                    )
	                )
	            ),
	            React.createElement(
	                ReactCSSTransitionGroup,
	                { transitionName: 'example', transitionLeave: false, transitionEnterTimeout: 500 },
	                dropdown
	            )
	        );
	    }
	});
	
	/***********************
	 * Department Dropdown *
	 ***********************/
	var Department = React.createClass({
	    displayName: 'Department',
	
	    getInitialState: function () {
	        return { departments: null, hasError: false };
	    },
	    setError: function (status) {
	        this.setState({ hasError: status });
	    },
	    componentWillMount: function () {
	        $.ajax({
	            url: 'index.php?module=intern&action=GetDepartments',
	            dataType: 'json',
	            success: function (data) {
	                this.setState({ departments: data });
	            }.bind(this),
	            error: function (xhr, status, err) {
	                console.error(status, err.toString());
	            }
	        });
	    },
	    render: function () {
	        var departments = this.state.departments;
	        if (departments === null) {
	            return React.createElement('div', null);
	        }
	
	        var fgClasses = classNames({
	            'form-group': true,
	            'has-error': this.state.hasError
	        });
	
	        return React.createElement(
	            'div',
	            { className: 'row' },
	            React.createElement(
	                'div',
	                { className: 'col-sm-12 col-md-4 col-md-push-3' },
	                React.createElement(
	                    'div',
	                    { className: fgClasses, id: 'department' },
	                    React.createElement(
	                        'label',
	                        { htmlFor: 'department2', className: 'control-label' },
	                        'Department'
	                    ),
	                    React.createElement(
	                        'select',
	                        { id: 'department2', name: 'department', className: 'form-control', defaultValue: '-1' },
	                        Object.keys(departments).map(function (key) {
	                            return React.createElement(
	                                'option',
	                                { key: key, value: key },
	                                departments[key]
	                            );
	                        })
	                    )
	                )
	            )
	        );
	    }
	});
	
	/*********************
	 * Host Agency Field *
	 *********************/
	var HostAgency = React.createClass({
	    displayName: 'HostAgency',
	
	    getInitialState: function () {
	        return { hasError: false };
	    },
	    setError: function (status) {
	        this.setState({ hasError: status });
	    },
	    render: function () {
	        var fgClasses = classNames({
	            'form-group': true,
	            'has-error': this.state.hasError
	        });
	        return React.createElement(
	            'div',
	            { className: 'row' },
	            React.createElement(
	                'div',
	                { className: 'col-sm-12 col-md-4 col-md-push-3' },
	                React.createElement(
	                    'div',
	                    { className: fgClasses, id: 'agency' },
	                    React.createElement(
	                        'label',
	                        { htmlFor: 'agency2', className: 'control-label' },
	                        'Internship Host'
	                    ),
	                    React.createElement('input', { type: 'text', id: 'agency2', name: 'agency', className: 'form-control', placeholder: 'Acme, Inc.' })
	                )
	            )
	        );
	    }
	});
	
	/*****************
	 * Submit Button *
	 *****************/
	var CreateInternshipButton = React.createClass({
	    displayName: 'CreateInternshipButton',
	
	    render: function () {
	        var button = null;
	        if (this.props.submitted) {
	            button = React.createElement(
	                'button',
	                { type: 'submit', className: 'btn btn-lg btn-primary pull-right', id: 'create-btn', disabled: true },
	                React.createElement('i', { className: 'fa fa-spinner fa-spin' }),
	                ' Saving...'
	            );
	        } else {
	            button = React.createElement(
	                'button',
	                { type: 'submit', className: 'btn btn-lg btn-primary pull-right', id: 'create-btn', onClick: this.handleClick },
	                'Create Internship'
	            );
	        }
	        return React.createElement(
	            'div',
	            { className: 'row' },
	            React.createElement(
	                'div',
	                { className: 'col-sm-12 col-md-6 col-md-push-3' },
	                button
	            )
	        );
	    }
	});
	
	var ErrorMessagesBlock = React.createClass({
	    displayName: 'ErrorMessagesBlock',
	
	    render: function () {
	        if (this.props.errors === null) {
	            return '';
	        }
	
	        var errors = this.props.errors.map(function (message, i) {
	            return React.createElement(
	                'li',
	                { key: i },
	                message
	            );
	        });
	
	        return React.createElement(
	            'div',
	            { className: 'row' },
	            React.createElement(
	                'div',
	                { className: 'col-sm-12 col-md-6 col-md-push-3' },
	                React.createElement(
	                    'div',
	                    { className: 'alert alert-danger', role: 'alert' },
	                    React.createElement(
	                        'p',
	                        null,
	                        React.createElement('i', { className: 'fa fa-exclamation-circle fa-2x' }),
	                        ' Please select values for the following fields: '
	                    ),
	                    React.createElement(
	                        'ul',
	                        null,
	                        errors
	                    )
	                )
	            )
	        );
	    }
	});
	
	/*********************************
	 * Top level Interface Component *
	 *********************************/
	var CreateInternshipInterface = React.createClass({
	    displayName: 'CreateInternshipInterface',
	
	    getInitialState: function () {
	        return { submitted: false, errorMessages: null };
	    },
	    // Top-level onSubmit handler for the creation form
	    handleSubmit: function (e) {
	        // Stop the browser from immediately sending the post
	        e.preventDefault();
	
	        // Set submitted=true on the state to disable submit button and prevent double-submission
	        var thisComponent = this; // Save a reference to 'this' for later use
	        var formElement = e.target; // Save a reference to the form DOM nodes that were submitted
	
	        this.setState({ submitted: true, errorMessages: null }, function () {
	            // After disabling submit buttons, use callback to validate the data
	            if (!this.validate(formElement, thisComponent)) {
	                // If the data doesn't validate, wait a second before re-enabling the submit button
	                // This makes sure the user sees the "Creating..." spinner, instead of it re-rendering
	                // so fast that they don't think it did anything
	                setTimeout(function () {
	                    thisComponent.setState({ submitted: false });
	                }, 1000);
	
	                return;
	            }
	
	            // If we get here, then validation was successful
	            formElement.submit();
	        });
	    },
	    validate: function (form, thisComponent) {
	
	        // Assume everything is valid, change this if we detect otherwise
	        var valid = true;
	        var errors = [];
	
	        // Check the student Component
	        if (form.elements.studentId.value === '' || !thisComponent.refs.studentSearch.studentFound()) {
	            thisComponent.refs.studentSearch.setError(true);
	            errors.push('Student ID');
	            valid = false;
	        } else {
	            thisComponent.refs.studentSearch.setError(false);
	        }
	
	        // Check the term
	        if (form.elements.term.value === '') {
	            thisComponent.refs.termBlock.setError(true);
	            errors.push('Term');
	            valid = false;
	        } else {
	            thisComponent.refs.termBlock.setError(false);
	        }
	
	        // Check the location
	        if (form.elements.location.value === '') {
	            thisComponent.refs.locationBlock.setError(true);
	            errors.push('Location');
	            valid = false;
	        } else {
	            thisComponent.refs.locationBlock.setError(false);
	        }
	
	        // Check the location's state/internal drop down's value
	        if (form.elements.location.value === 'domestic') {
	            if (form.elements.state.value === '-1') {
	                thisComponent.refs.locationBlock.refs.state.setError(true);
	                errors.push('State');
	                valid = false;
	            } else {
	                thisComponent.refs.locationBlock.refs.state.setError(false);
	            }
	        } else if (form.elements.location.value === 'international') {
	            if (form.elements.country.value === '-1') {
	                thisComponent.refs.locationBlock.refs.country.setError(true);
	                errors.push('Country');
	                valid = false;
	            } else {
	                thisComponent.refs.locationBlock.refs.country.setError(false);
	            }
	        }
	
	        // Check the department
	        if (form.elements.department.value === '_-1') {
	            thisComponent.refs.department.setError(true);
	            valid = false;
	            errors.push('Department');
	        } else {
	            thisComponent.refs.department.setError(false);
	        }
	
	        // Check the host agency
	        if (form.elements.agency.value === '') {
	            thisComponent.refs.hostAgency.setError(true);
	            valid = false;
	            errors.push('Host Agency');
	        } else {
	            thisComponent.refs.hostAgency.setError(false);
	        }
	
	        if (errors.length !== 0) {
	            thisComponent.setErrorMessages(errors);
	        }
	
	        return valid;
	    },
	    setErrorMessages: function (messages) {
	        this.setState({ errorMessages: messages });
	    },
	    render: function () {
	        var errors;
	        if (this.state.errorMessages == null) {
	            errors = '';
	        } else {
	            errors = React.createElement(ErrorMessagesBlock, { key: 'errorSet', errors: this.state.errorMessages });
	        }
	
	        return React.createElement(
	            'form',
	            { role: 'form', id: 'newInternshipForm', className: 'form-protected', action: 'index.php', method: 'post', onSubmit: this.handleSubmit },
	            React.createElement('input', { type: 'hidden', name: 'module', value: 'intern' }),
	            React.createElement('input', { type: 'hidden', name: 'action', value: 'AddInternship' }),
	            React.createElement(
	                ReactCSSTransitionGroup,
	                { transitionName: 'example', transitionEnterTimeout: 500, transitionLeaveTimeout: 500 },
	                errors
	            ),
	            React.createElement(StudentSearch, { ref: 'studentSearch' }),
	            React.createElement(TermBlock, { ref: 'termBlock' }),
	            React.createElement(LocationBlock, { ref: 'locationBlock' }),
	            React.createElement(Department, { ref: 'department' }),
	            React.createElement(HostAgency, { ref: 'hostAgency' }),
	            React.createElement(CreateInternshipButton, { submitted: this.state.submitted })
	        );
	    }
	});
	
	ReactDOM.render(React.createElement(CreateInternshipInterface, null), document.getElementById('createInternshipInterface'));

/***/ }
/******/ ]);
//# sourceMappingURL=createInterface.dev.js.map