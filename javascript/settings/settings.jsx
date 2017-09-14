import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';

class ErrorMessagesBlock extends Component{
    // constructor(props) {
    //     super(props);
    // }

    render() {
        // if(this.props.errorSet == null){
        //     return '';
        // }

        //var errors = this.props.errorSet;

        return (
            <div className="row">
                <div className="col-sm-12 col-md-6 col-md-push-3">
                    <div className={"alert alert-" + this.props.stat} role="alert">
                        <p><i className="fa fa-exclamation-circle fa-2x"></i> Success: {this.props.notif}</p>

                    </div>
                </div>
            </div>
        );
    }
};

class Settings extends Component {
    constructor(props) {
        super(props);
        this.state = {
            data: null,
            submitted: false,
            notification: null,
            notificationStatus: null
        };

        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(e) {
        e.preventDefault();

        var data = {systemName: this.systemNameInput.value,
                    registrarEmail: this.registrarEmailInput.value,
                    gradSchoolEmail: this.gradSchoolEmailInput.value,
                    backgroundCheckEmail: this.backgroundCheckEmailInput.value,
                    graduateRegEmail: this.gradRegistrarEmailInput.value,
                    internationalRegEmail: this.internationalRegistrarEmailInput.value,
                    distanceEdEmail: this.distanceEdEmailInput.value,
                    emailDomain: this.emailDomainInput.value,
                    internationalOfficeEmail: this.internationalOfficeEmailInput.value,
                    wsdlUri: this.wsdlUriInput.value,
                    fromEmail: this.fromEmailInput.value,
                    unusualCourseEmail: this.unusualCourseEmailInput.value,
                    uncaughtExceptionEmail: this.uncaughtExceptionEmailInput.value};

        this.setState({submitted: true}, function(){
            $.ajax({
                url: 'index.php?module=intern&action=settingsRest',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function() {
                    var message = "Settings have been updated.";
                    var notifStatus = "success";
                    this.setState({data: data, submitted: false, notification: message, notificationStatus: notifStatus});
                }.bind(this),
                error: function(xhr, status, err) {
    				var message = "Settings did not save.";
                    var notifStatus = "danger";
                    this.setState({notification: message, notificationStatus: notifStatus});
    				console.error(this.props.url, status, err.toString());
    			}.bind(this)
            });
        });

        console.log(data);
    }

    componentDidMount() {
        $.ajax({
			url: 'index.php?module=intern&action=settingsRest',
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				this.setState({data: data});
			}.bind(this),
			error: function(xhr, status, err) {
				var message = "Failed to grab settings data.";
                var notifStatus = "danger";
                this.setState({errorMessage: message, notificationStatus: notifStatus});
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});


    }

    render() {
        if(this.state.data === null){
            return (<div></div>);
        }

        var button = null;
        if(this.state.submitted) {
            button = <button type="submit" className="btn btn-lg btn-primary pull-right" id="create-btn" disabled ><i className="fa fa-spinner fa-spin"></i> Saving...</button>;
        } else {
            button = <button type="submit" className="btn btn-lg btn-primary pull-right" id="create-btn" onClick={this.handleSubmit} >Save Settings</button>;
        }

        var errorSet;
        if(this.state.notification === null){
            errorSet = '';
        } else {
            errorSet = <ErrorMessagesBlock key="notification" notif={this.state.notification} stat={this.state.notificationStatus}/>
        }

        return (
            <div className="container">
            {errorSet}
            <h1> Admin Settings </h1><br/>
            <form onSubmit={this.handleSubmit} >
                <div className="form-group col-md-6">
                    <label htmlFor="systemName">System Name:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.systemName} id="systemName" name="systemName" ref={input => this.systemNameInput = input} ></input>
                </div>
                <div className="form-group col-md-6">
                    <label>wsdlUri:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.wsdlUri} id="wsdlUri" name="wsdlUri" ref={input => this.wsdlUriInput = input}></input>
                </div>
                <div className="col-md-12">
                <h2>Email Settings</h2><br/>
                </div>
                <div className="form-group col-md-6">
                    <label>Email Domain:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.emailDomain} id="emailDomain" name="emailDomain" ref={input => this.emailDomainInput = input}></input>
                </div>
                <div className="form-group col-md-6">
                    <label>Registrar Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.registrarEmail} id="registrarEmail" name="registrarEmail" ref={input => this.registrarEmailInput = input}></input>
                </div>
                <div className="form-group col-md-6">
                    <label>Grad School Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.gradSchoolEmail} id="gradSchoolEmail" name="gradSchoolEmail" ref={input => this.gradSchoolEmailInput = input}></input>
                </div>
                <div className="form-group col-md-6">
                    <label>Background Check Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.backgroundCheckEmail} id="backgroundCheckEmail" name="backgroundCheckEmail" ref={input => this.backgroundCheckEmailInput = input}></input>
                </div>
                <div className="form-group col-md-6">
                    <label>Graduate Registrar Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.graduateRegEmail} id="gradRegistrarEmail" name="gradRegistrarEmail" ref={input => this.gradRegistrarEmailInput = input}></input>
                </div>
                <div className="form-group col-md-6">
                    <label>International Registrar Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.internationalRegEmail} id="internationalRegistrarEmail" name="internationalRegistrarEmail" ref={input => this.internationalRegistrarEmailInput = input}></input>
                </div>
                <div className="form-group col-md-6">
                    <label>Distance Education Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.distanceEdEmail} id="distanceEdEmail" name="distanceEdEmail" ref={input => this.distanceEdEmailInput = input}></input>
                </div>
                <div className="form-group col-md-6">
                    <label>International Office Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.internationalOfficeEmail} id="internationalOfficeEmail" name="internationalOfficeEmail" ref={input => this.internationalOfficeEmailInput = input}></input>
                </div>
                <div className="form-group col-md-6">
                    <label>From Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.fromEmail} id="fromEmail" name="fromEmail" ref={input => this.fromEmailInput = input}></input>
                </div>
                <div className="form-group col-md-6">
                    <label>Unusual Course Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.unusualCourseEmail} id="unusualCourseEmail" name="unusualCourseEmail" ref={input => this.unusualCourseEmailInput = input}></input>
                </div>
                <div className="form-group col-md-6">
                    <label>Uncaught Exception Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.uncaughtExceptionEmail} id="uncaughtExceptionEmail" name="uncaughtExceptionEmail" ref={input => this.uncaughtExceptionEmailInput = input}></input>
                </div>
            </form>

            <div className="row">
                <div className="col-sm-12 col-md-2 ">
                    {button}
                </div>
            </div>
        </div>
        )
    }
}

ReactDOM.render(
    <Settings />,
    document.getElementById('content')
);
