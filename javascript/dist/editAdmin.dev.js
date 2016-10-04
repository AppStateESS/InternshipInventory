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
/*!********************************************!*\
  !*** ./javascript/editAdmin/editAdmin.jsx ***!
  \********************************************/
/***/ function(module, exports) {

	import React from 'react';
	import ReactDOM from 'react-dom';
	import $ from 'jquery';
	
	var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;
	
	var ErrorMessagesBlock = React.createClass({
		displayName: 'ErrorMessagesBlock',
	
		render: function () {
			if (this.props.errors === null) {
				return '';
			}
	
			var errors = this.props.errors;
	
			return React.createElement(
				'div',
				{ className: 'row' },
				React.createElement(
					'div',
					{ className: 'col-sm-12 col-md-6 col-md-push-3' },
					React.createElement(
						'div',
						{ className: 'alert alert-warning', role: 'alert' },
						React.createElement(
							'p',
							null,
							React.createElement('i', { className: 'fa fa-exclamation-circle fa-2x' }),
							' Warning: ',
							errors
						)
					)
				)
			);
		}
	});
	
	var DepartmentList = React.createClass({
		displayName: 'DepartmentList',
	
		render: function () {
			return React.createElement(
				'option',
				{ value: this.props.id },
				this.props.name
			);
		}
	});
	
	var DeleteAdmin = React.createClass({
		displayName: 'DeleteAdmin',
	
		handleChange: function () {
			this.props.onAdminDelete(this.props.id);
		},
		render: function () {
			return React.createElement(
				'tr',
				null,
				React.createElement(
					'td',
					null,
					this.props.fullname
				),
				React.createElement(
					'td',
					null,
					this.props.username
				),
				React.createElement(
					'td',
					null,
					this.props.department
				),
				React.createElement(
					'td',
					null,
					' ',
					React.createElement(
						'a',
						{ onClick: this.handleChange },
						' ',
						React.createElement('i', { className: 'fa fa-trash-o' }),
						' '
					),
					' '
				)
			);
		}
	});
	
	var SearchAdmin = React.createClass({
		displayName: 'SearchAdmin',
	
		getInitialState: function () {
			return {
				mainData: null,
				displayData: null,
				deptData: null,
				errorWarning: null,
				searchPhrase: '',
				dropData: "",
				textData: ""
			};
		},
		componentWillMount: function () {
			// Grabs the department data and admin data
			this.getData();
			this.getDept();
		},
		getData: function () {
			$.ajax({
				url: 'index.php?module=intern&action=adminRest',
				type: 'GET',
				dataType: 'json',
				success: function (data) {
					this.setState({ mainData: data,
						displayData: data });
				}.bind(this),
				error: function (xhr, status, err) {
					alert("Failed to grab displayed data.");
					console.error(this.props.url, status, err.toString());
				}.bind(this)
			});
		},
		getDept: function () {
			$.ajax({
				url: 'index.php?module=intern&action=deptRest',
				action: 'GET',
				dataType: 'json',
				success: function (data) {
					data.unshift({ name: "Select a department", id: "-1" });
					this.setState({ deptData: data });
				}.bind(this),
				error: function (xhr, status, err) {
					alert("Failed to grab deptartment data.");
					console.error(this.props.url, status, err.toString());
				}.bind(this)
			});
		},
		onAdminDelete: function (idNum) {
			// Updating the new state for optimization (snappy response on the client)
			// When a value is being deleted
			var newVal = this.state.displayData.filter(function (el) {
				return el.id !== idNum;
			});
			this.setState({ displayData: newVal });
	
			$.ajax({
				url: 'index.php?module=intern&action=adminRest&id=' + idNum,
				type: 'DELETE',
				success: function () {
					this.getData();
				}.bind(this)
			});
		},
		onAdminCreate: function (username, department) {
			var displayName = '';
			var displayData = this.state.displayData;
			var dept = this.state.deptData;
	
			var errorMessage = null;
	
			// Catch whether the created admin is missing a department
			if (department === '' || department === -1) {
				errorMessage = "Please choose a department.";
				this.setState({ errorWarning: errorMessage });
				return;
			}
	
			// Catch whether the created admin is missing a username
			if (username === '') {
				errorMessage = "Please enter a valid username.";
				this.setState({ errorWarning: errorMessage });
				return;
			}
	
			// Finds the index of the array if the department number matches
			// the id of the object.
			var deptIndex = dept.findIndex(function (element, index, arr) {
				if (department === element.id) {
					return true;
				} else {
					return false;
				}
			});
	
			for (var j = 0, k = displayData.length; j < k; j++) {
				if (displayData[j].username === username) {
					displayName = displayData[j].display_name;
					if (displayData[j].name === dept[deptIndex].name) {
						errorMessage = "Multiple usernames in the same department.";
						this.setState({ errorWarning: errorMessage });
						return;
					}
				}
			}
	
			var deptName = dept[deptIndex].name;
	
			if (displayName !== '') {
				displayData.unshift({ username: username, id: -1, name: deptName, display_name: displayName });
			}
	
			// Updating the new state for optimization (snappy response on the client)
			var newVal = this.state.displayData;
			this.setState({ displayData: newVal });
	
			$.ajax({
				url: 'index.php?module=intern&action=adminRest&user=' + username + '&dept=' + department,
				type: 'POST',
				success: function (data) {
					this.getData();
					this.setState({ errorWarning: null });
				}.bind(this),
				error: function (http) {
					var errorMessage = http.responseText;
					this.setState({ errorWarning: errorMessage });
				}.bind(this)
			});
		},
		searchList: function (e) {
			var phrase = null;
			try {
				// Saves the phrase that the user is looking for.
				phrase = e.target.value.toLowerCase();
				this.setState({ searchPhrase: phrase });
			} catch (err) {
				phrase = this.state.searchPhrase;
			}
	
			var filtered = [];
	
			// Looks for the phrase by filtering the mainData
			for (var i = 0; i < this.state.mainData.length; i++) {
				var item = this.state.mainData[i];
	
				if (item.name.toLowerCase().includes(phrase) || item.username.toLowerCase().includes(phrase) || item.display_name.toLowerCase().includes(phrase)) {
					filtered.push(item);
				}
			}
	
			this.setState({ displayData: filtered });
		},
		handleDrop: function (e) {
			this.setState({ dropData: e.target.value });
		},
		handleSubmit: function () {
			var username = ReactDOM.findDOMNode(this.refs.username).value.trim();
			var deptNum = this.state.dropData;
	
			this.onAdminCreate(username, deptNum);
		},
		render: function () {
			var AdminsData = null;
			if (this.state.mainData != null) {
				var onAdminDelete = this.onAdminDelete;
				AdminsData = this.state.displayData.map(function (admin) {
					return React.createElement(DeleteAdmin, { key: admin.id,
						fullname: admin.display_name,
						username: admin.username,
						department: admin.name,
						id: admin.id,
						onAdminDelete: onAdminDelete });
				});
			} else {
				AdminsData = "";
			}
	
			var dData = null;
			if (this.state.deptData != null) {
				dData = this.state.deptData.map(function (dept) {
					return React.createElement(DepartmentList, { key: dept.id,
						name: dept.name,
						id: dept.id });
				});
			} else {
				dData = "";
			}
	
			var errors;
			if (this.state.errorWarning == null) {
				errors = '';
			} else {
				errors = React.createElement(ErrorMessagesBlock, { key: 'errorSet', errors: this.state.errorWarning });
			}
	
			return React.createElement(
				'div',
				{ className: 'search' },
				React.createElement(
					ReactCSSTransitionGroup,
					{ transitionName: 'example', transitionEnterTimeout: 500, transitionLeaveTimeout: 500 },
					errors
				),
				React.createElement(
					'div',
					{ className: 'row' },
					React.createElement(
						'div',
						{ className: 'col-md-5' },
						React.createElement(
							'h1',
							null,
							' Administrators '
						),
						React.createElement('br', null),
						React.createElement(
							'div',
							{ className: 'input-group' },
							React.createElement('input', { type: 'text', className: 'form-control', placeholder: 'Search for...', onChange: this.searchList })
						),
						React.createElement('br', null),
						React.createElement(
							'table',
							{ className: 'table table-condensed table-striped' },
							React.createElement(
								'thead',
								null,
								React.createElement(
									'tr',
									null,
									React.createElement(
										'th',
										null,
										'Fullname'
									),
									React.createElement(
										'th',
										null,
										'Username'
									),
									React.createElement(
										'th',
										null,
										'Department'
									),
									React.createElement('th', null)
								)
							),
							React.createElement(
								'tbody',
								null,
								AdminsData
							)
						)
					),
					React.createElement('br', null),
					' ',
					React.createElement('br', null),
					' ',
					React.createElement('br', null),
					React.createElement(
						'div',
						{ className: 'col-md-5 col-md-offset-1' },
						React.createElement(
							'div',
							{ className: 'panel panel-default' },
							React.createElement(
								'div',
								{ className: 'panel-body' },
								React.createElement(
									'div',
									{ className: 'row' },
									React.createElement(
										'div',
										{ className: 'col-md-6' },
										React.createElement(
											'label',
											null,
											'Department:'
										),
										React.createElement(
											'select',
											{ className: 'form-control', onChange: this.handleDrop },
											dData
										)
									),
									React.createElement(
										'div',
										{ className: 'col-md-6' },
										React.createElement(
											'label',
											null,
											'Username:'
										),
										React.createElement('input', { type: 'text', className: 'form-control', placeholder: 'Username', ref: 'username' })
									)
								),
								React.createElement(
									'div',
									{ className: 'row' },
									React.createElement('br', null),
									React.createElement(
										'div',
										{ className: 'col-md-3 col-md-offset-6' },
										React.createElement(
											'button',
											{ className: 'btn btn-default', onClick: this.handleSubmit },
											'Create Admin'
										)
									)
								)
							)
						)
					)
				)
			);
		}
	});
	
	ReactDOM.render(React.createElement(SearchAdmin, null), document.getElementById('content'));

/***/ }
/******/ ]);
//# sourceMappingURL=editAdmin.dev.js.map