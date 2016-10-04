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
/*!********************************************************!*\
  !*** ./javascript/searchInterface/SearchInterface.jsx ***!
  \********************************************************/
/***/ function(module, exports) {

	import React from 'react';
	import ReactDOM from 'react-dom';
	import $ from 'jquery';
	import classNames from 'classnames';
	
	import InternationalDropDown from '../createInterface/InternationalDropDown.jsx';
	import StateDropDown from '../createInterface/StateDropDown.jsx';
	
	var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;
	
	var LocationSelector = React.createClass({
	    displayName: 'LocationSelector',
	
	    getInitialState: function () {
	        return {
	            domestic: false,
	            international: false,
	            availableStates: null,
	            availableCountries: null,
	            hasError: false
	        };
	    },
	    componentDidMount: function () {
	        // Fetch list of available states
	        $.ajax({
	            url: 'index.php?module=intern&action=GetStates',
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
	    anyLocation: function () {
	        this.setState({ domestic: false, international: false });
	    },
	    render: function () {
	
	        var dropdown;
	        if (!this.state.domestic && !this.state.international) {
	            dropdown = '';
	        } else if (this.state.domestic) {
	            dropdown = React.createElement(StateDropDown, { key: 'states', ref: 'state', states: this.state.availableStates, formStyle: 'horizontal' });
	        } else {
	            dropdown = React.createElement(InternationalDropDown, { key: 'countries', ref: 'country', countries: this.state.availableCountries, formStyle: 'horizontal' });
	        }
	
	        var anyLabelClass = classNames({
	            'btn': true,
	            'btn-default': true,
	            'active': !this.state.domestic && !this.state.international
	        });
	
	        var domesticLabelClass = classNames({
	            'btn': true,
	            'btn-default': true,
	            'active': this.state.domestic
	        });
	
	        var internationalLabelClass = classNames({
	            'btn': true,
	            'btn-default': true,
	            'active': this.state.international
	        });
	
	        return React.createElement(
	            'div',
	            null,
	            React.createElement(
	                'div',
	                { className: 'form-group' },
	                React.createElement(
	                    'label',
	                    { className: 'col-lg-3 control-label', htmlFor: 'location' },
	                    'Location'
	                ),
	                React.createElement(
	                    'div',
	                    { className: 'col-lg-8' },
	                    React.createElement(
	                        'div',
	                        { className: 'btn-group' },
	                        React.createElement(
	                            'label',
	                            { className: anyLabelClass },
	                            'Any Location',
	                            React.createElement('input', { type: 'radio', name: 'location', value: '-1', style: { position: "absolute", clip: "rect(0, 0, 0, 0)" }, onClick: this.anyLocation })
	                        ),
	                        React.createElement(
	                            'label',
	                            { className: domesticLabelClass },
	                            'Domestic',
	                            React.createElement('input', { type: 'radio', name: 'location', value: 'domestic', style: { position: "absolute", clip: "rect(0, 0, 0, 0)" }, onClick: this.domestic })
	                        ),
	                        React.createElement(
	                            'label',
	                            { className: internationalLabelClass },
	                            'International',
	                            React.createElement('input', { type: 'radio', name: 'location', value: 'internat', style: { position: "absolute", clip: "rect(0, 0, 0, 0)" }, onClick: this.international })
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
	
	ReactDOM.render(React.createElement(LocationSelector, null), document.getElementById('LocationSelector'));

/***/ }
/******/ ]);
//# sourceMappingURL=searchInterface.dev.js.map