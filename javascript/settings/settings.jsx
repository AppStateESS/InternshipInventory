import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';

class Settings extends Component {
    constructor(props) {
        super(props);
        this.state = {
            data: null,
            submitted: false
        };

        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(e) {
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
                    fromEmail: this.fromEmailInput.value};

        $.ajax({
            url: 'index.php?module=intern&action=settingsRest',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function() {
                alert("Updated...");
                this.setState({data: data});
            }.bind(this)
        });

        this.setState({submitted: true});
        e.preventDefault();

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
				alert("Failed to grab displayed data.")
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

        return (
            <div> <h1> Admin Settings </h1>
            <form onSubmit={this.handleSubmit} >
                <div className="form-group">
                    <label htmlFor="systemName">System Name:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.systemName} id="systemName" name="systemName" ref={input => this.systemNameInput = input} ></input>
                </div>
                <div className="form-group">
                    <label>Registrar Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.registrarEmail} id="registrarEmail" name="registrarEmail" ref={input => this.registrarEmailInput = input}></input>
                </div>
                <div className="form-group">
                    <label>Grad School Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.gradSchoolEmail} id="gradSchoolEmail" name="gradSchoolEmail" ref={input => this.gradSchoolEmailInput = input}></input>
                </div>
                <div className="form-group">
                    <label>Background Check Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.backgroundCheckEmail} id="backgroundCheckEmail" name="backgroundCheckEmail" ref={input => this.backgroundCheckEmailInput = input}></input>
                </div>
                <div className="form-group">
                    <label>Graduate Registrar Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.graduateRegEmail} id="gradRegistrarEmail" name="gradRegistrarEmail" ref={input => this.gradRegistrarEmailInput = input}></input>
                </div>
                <div className="form-group">
                    <label>International Registrar Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.internationalRegEmail} id="internationalRegistrarEmail" name="internationalRegistrarEmail" ref={input => this.internationalRegistrarEmailInput = input}></input>
                </div>
                <div className="form-group">
                    <label>Distance Education Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.distanceEdEmail} id="distanceEdEmail" name="distanceEdEmail" ref={input => this.distanceEdEmailInput = input}></input>
                </div>
                <div className="form-group">
                    <label>Email Domain:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.emailDomain} id="emailDomain" name="emailDomain" ref={input => this.emailDomainInput = input}></input>
                </div>
                <div className="form-group">
                    <label>International Office Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.internationalOfficeEmail} id="internationalOfficeEmail" name="internationalOfficeEmail" ref={input => this.internationalOfficeEmailInput = input}></input>
                </div>
                <div className="form-group">
                    <label>wsdlUri:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.wsdlUri} id="wsdlUri" name="wsdlUri" ref={input => this.wsdlUriInput = input}></input>
                </div>
                <div className="form-group">
                    <label>From Email:</label>
                    <input className="form-control" type="text" defaultValue={this.state.data.fromEmail} id="fromEmail" name="fromEmail" ref={input => this.fromEmailInput = input}></input>
                </div>
            </form>

            <div className="row">
                <div className="col-sm-12 col-md-4 col-md-push-3">
                    {button}
                </div>
            </div>
        </div>
        )
    }
}
//
// export default class SettingsRender extends Component {
//     render() {
//         return (
//             <div>
//                 <Settings name='systemName' />
//                 <Settings name='registrarEmail' />
//                 <Settings name='gradSchoolEmail' />
//                 <Settings name='backgroundCheckEmail' />
//                 <Settings name='graduateRegEmail' />
//                 <Settings name='internationalRegEmail' />
//                 <Settings name='distanceEdEmail' />
//                 <Settings name='emailDomain' />
//                 <Settings name='internationalOfficeEmail' />
//                 <Settings name='wsdlUri' />
//                 <Settings name='fromEmail' />
//             </div>
//         )
//     }
// }
//
ReactDOM.render(
    <Settings />,
    document.getElementById('content')
);
