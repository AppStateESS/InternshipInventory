import React from 'react';
import ReactDOM from 'react-dom';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group';

import StudentSearch from './StudentSearch.jsx';
import TermBlock from './TermBlock.jsx';
import LocationBlock from './LocationBlock.jsx';
import Department from './DepartmentBlock.jsx';
import HostAgency from './HostBlock.jsx';

/*****************
 * Submit Button *
 *****************/
class CreateInternshipButton extends React.Component {
    render() {
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
}

class ErrorMessagesBlock extends React.Component {
    render() {
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
}

/*********************************
 * Top level Interface Component *
 *********************************/
class CreateInternshipInterface extends React.Component {
    constructor(props){
        super(props);

        this.state = {submitted: false,
                    errorMessages: null,
                    domestic: undefined,
                    international: undefined,
                    location: undefined};

        this.handleSubmit = this.handleSubmit.bind(this);
    }

    // Top-level onSubmit handler for the creation form
    handleSubmit(e) {
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
    }
    validate(form, thisComponent) {

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
                thisComponent.refs.locationBlock.stateDropDown.setError(true);
                errors.push('State');
                valid = false;
            }else{
                thisComponent.refs.locationBlock.stateDropDown.setError(false);
            }
        } else if(form.elements.location.value === 'international') {
            if(form.elements.country.value === '-1') {
                thisComponent.refs.locationBlock.countryDropDown.setError(true);
                errors.push('Country');
                valid = false;
            }else{
                thisComponent.refs.locationBlock.countryDropDown.setError(false);
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

        // Check host
        if(form.elements.main_host.value === '-1'){
            thisComponent.refs.hostAgency.setError(true);
            valid = false;
            errors.push('Host Name: If you don\'t see the correct host, request it by clicking the plus(+)');
        }else{
            thisComponent.refs.hostAgency.setError(false);
        }
        if(form.elements.sub_host.value === '-1'){
            thisComponent.refs.hostAgency.setError(true);
            valid = false;
            errors.push('Sub Name: If you don\'t see the correct sub, request it by clicking the plus(+)');
        }else{
            thisComponent.refs.hostAgency.setError(false);
        }

        this.setErrorMessages(errors)

        return valid;
    }
    setErrorMessages(messages) {
        this.setState({errorMessages: messages});
    }
    render() {
        var errors;
        if(this.state.errorMessages == null || this.state.errorMessages.length == 0){
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

                <LocationBlock domestic={this.state.domestic} international={this.state.international} location={this.state.location} setDom={(dom) => this.setState({domestic:dom})} setInt={(inta) => this.setState({international: inta})}  setLoc={(loc) => this.setState({location: loc})} ref="locationBlock"/>

                <Department ref="department"/>

                <HostAgency domestic={this.state.domestic} location={this.state.location} key={this.state.location} ref="hostAgency"/>

                <CreateInternshipButton submitted={this.state.submitted}/>
            </form>
        );
    }
}

ReactDOM.render(
    <CreateInternshipInterface />, document.getElementById('createInternshipInterface')
);
