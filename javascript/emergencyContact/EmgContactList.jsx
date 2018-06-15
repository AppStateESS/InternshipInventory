import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import {Button, Modal} from 'react-bootstrap';
import Message from './Message.jsx';


// !!The internshipId variable is important!!

// It's being used as a global variable from the head.js where this file is located
// to determine which internship is loaded so it can grab the emergency contacts.

/****************************
 * Modal Form
 * This uses ReactBoostrap!!
 ****************************/
class ModalForm extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            showError: false,
            warningMsg: '',
            isInternational: false
        };

        this.formatPhone = this.formatPhone.bind(this);
        this.formatEmail = this.formatEmail.bind(this);
        this.handleSave = this.handleSave.bind(this);
        this.handleExit = this.handleExit.bind(this);
        this.handleChange = this.handleChange.bind(this);
    }
    componentDidMount() {
        let cChecked;
        if (this.props.phone !== undefined && this.props.phone.charAt(0) !== '('){
            cChecked = true;
        } else {
            cChecked = false;
        }
        this.setState({
            isInternational: cChecked
        })
    }
    formatPhone(event) {
        var input = event.target.value;
        // Strip all characters from the input except digits
        input = input.replace(/\D/g,'');

        if (!this.state.isInternational){
          // Trim the remaining input to ten characters, to preserve phone number format
          input = input.substring(0,10);

          // Based upon the length of the string, we add formatting as necessary
          var size = input.length;
          if(size === 0){
            input = '';
          }else if(size < 4){
            input = '('+input;
          }else if(size < 7){
            input = '('+input.substring(0,3)+') '+input.substring(3,6);
          }else{
            input = '('+input.substring(0,3)+') '+input.substring(3,6)+' - '+input.substring(6,10);
          }
        }
        event.target.value = input
    }
    formatEmail(input) {
        var exp = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        return exp.test(input)
    }
    handleSave() {
        if (this.refs.emg_name.value === '' || this.refs.emg_relation.value === '' ||  this.refs.emg_phone.value === '') {
            // If any field is left empty, it will display an error message in the modal form.
            this.setState({showError: true,
                           warningMsg: "Please check to ensure all fields have been filled in."});
            return;
        }

        // format is (111) 111 - 1111 (16 chars)
        if (!this.state.isInternational && this.refs.emg_phone.value.length !== 16){
            this.setState({showError: true,
                           warningMsg: "Please use a valid phone number."});
            return
        }

        // If it fails the format check, show error
        if (!this.formatEmail(this.refs.emg_email.value)){
            this.setState({showError: true,
                           warningMsg: "Please use a valid email."});
            return
        }

        this.setState({showError: false,
                       warningMsg: ""});
        var contact = {id: this.props.id,
                       name: this.refs.emg_name.value,
                       relation: this.refs.emg_relation.value,
                       phone: this.refs.emg_phone.value,
                       email:this.refs.emg_email.value};

        // Call parent's save handler
        this.props.handleSaveContact(contact);
    }
    handleExit(){
        //resets state so any warnings previously are reset.
        this.setState({
            showError: false,
            warningMsg: '',
        })
        this.props.hide();
    }
    handleChange(){
        this.setState({
           isInternational: !this.state.isInternational
        })
    }
    render() {

        return (
            <Modal show={this.props.show} onHide={this.handleExit} backdrop='static'>
                <Modal.Header closeButton>
                  <Modal.Title>Emergency Contact</Modal.Title>
                  {this.state.showError ? <Message type="warning" children={this.state.warningMsg}></Message> : null}

                </Modal.Header>
                <Modal.Body>
                    <form className="form-horizontal">
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Name</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="emg-name" ref="emg_name" defaultValue={this.props.name} /></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Relation</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="emg-relation" ref="emg_relation" defaultValue={this.props.relation} /></div>
                        </div>
                        <div className="form-group">
                          <div className="col-sm-offset-3 col-sm-10">
                            <div className="checkbox">
                              <label>
                                <input type="checkbox" id="emg-international" ref="emg_international" checked={this.state.isInternational} onChange={this.handleChange}/> International Number
                              </label>
                            </div>
                          </div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Phone</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="emg-phone" ref="emg_phone" defaultValue={this.props.phone} onChange={this.formatPhone} /></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Email</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="emg-email" ref="emg_email" defaultValue={this.props.email}/></div>
                        </div>
                    </form>
                </Modal.Body>
                <Modal.Footer>
                    <Button onClick={this.handleSave}>Save</Button>
                    <Button onClick={this.handleExit}>Close</Button>
                </Modal.Footer>
            </Modal>
        );
    }
}


/*********************
 * Emergency Contact *
 *********************/
class EmergencyContact extends React.Component {
    constructor(props) {
        super(props);
        this.state = {showModal: false};

        this.closeModal = this.closeModal.bind(this);
        this.openModal = this.openModal.bind(this);
        this.handleSaveContact = this.handleSaveContact.bind(this);
        this.handleRemove = this.handleRemove.bind(this);
    }
    closeModal() {
        this.setState({ showModal: false });
    }
    openModal() {
        this.setState({ showModal: true });
    }
    handleSaveContact(contact){
        this.closeModal(); // Close the modal box
        this.props.handleSave(contact); // Call parent's handleSave method
    }
    handleRemove(event) {
        // Prevents the modal trigger from occuring when presing
        // the remove button.
        event.stopPropagation();
        this.props.onContactRemove(this.props.id);
    }
    render() {
        var contactInfo = <span>
                            {this.props.name} {'\u2022'} {this.props.relation} {'\u2022'} {this.props.phone} {'\u2022'} {this.props.email}
                          </span>
        return (
                <li className="list-group-item" onClick={this.openModal} style={{cursor: "pointer"}}>
                    {contactInfo}
                    <button type="button" className="close" data-dismiss="alert" aria-label="Close" onClick={this.handleRemove}><span aria-hidden="true">&times;</span></button>

                    <ModalForm show={this.state.showModal} hide={this.closeModal} edit={true} handleSaveContact={this.handleSaveContact}{...this.props} />

                </li>
        );
    }
}

class EmergencyContactList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            emgConData: null,
            showAddModal: false
        };

        this.handleNewContact = this.handleNewContact.bind(this);
        this.onContactRemove = this.onContactRemove.bind(this);
        this.handleSave = this.handleSave.bind(this);
        this.openAddModal = this.openAddModal.bind(this);
        this.closeAddModal = this.closeAddModal.bind(this);
    }
    componentWillMount(){
        this.getData();
    }
    closeAddModal() {
        this.setState({ showAddModal: false });
    }
    openAddModal() {
        this.setState({ showAddModal: true });
    }
    handleNewContact(contact){
        this.closeAddModal(); // Close the modal box
        this.handleSave(contact); // Call parent's handleSave method
    }
    getData(){
        // Grabs the emergency contact data
        $.ajax({
            url: 'index.php?module=intern&action=emergencyContactRest&internshipId='+this.props.internshipId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({emgConData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to load emergency contact data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    handleSave(contact) {
        // Event handler to save the comments.

        // Updates or adds a new emergency contact
        $.ajax({
            url: 'index.php?module=intern&action=emergencyContactRest',
            type: 'POST',
            dataType: 'json',
            data: {internshipId: this.props.internshipId,
                   contactId: contact.id,
                   emergency_contact_name: contact.name,
                   emergency_contact_relation: contact.relation,
                   emergency_contact_phone: contact.phone,
                   emergency_contact_email: contact.email
               },
            success: function(data) {
                // Grabs the new data
                this.setState({emgConData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to save emergency contact data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    onContactRemove(contactId){
        // Deletes the emergency contact.
        $.ajax({
            url: 'index.php?module=intern&action=emergencyContactRest&contactId='+contactId+'&internshipId='+this.props.internshipId,
            type: 'DELETE',
            dataType: 'json',
            success: function(data) {
                this.setState({emgConData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to DELETE data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    render() {
        var eData = null;
        if(this.state.emgConData != null){
            eData = this.state.emgConData.map(function (conData) {
                return (

                        <EmergencyContact key={conData.id}
                                       id={conData.id}
                                       name={conData.name}
                                       relation={conData.relation}
                                       phone={conData.phone}
                                       email={conData.email}
                                       handleSave={this.handleSave}
                                       onContactRemove={this.onContactRemove}
                                       getData={this.getData} />
                    );
            }.bind(this));

        }else{
            eData = <p className="text-muted"><i className="fa fa-spinner fa-2x fa-spin"></i> Loading Emergency Contacts...</p>;
        }

        return (
            <div>
                <ul className="list-group">
                    {eData}
                </ul>
                <div className="row">
                    <div className="col-lg-12 col-lg-offset-9">
                        <div className="form-group">
                            <button type="button" className="btn btn-default" onClick={this.openAddModal}><i className="fa fa-plus"></i> Add Contact</button>

                            <ModalForm show={this.state.showAddModal} hide={this.closeAddModal} edit={false} handleSaveContact={this.handleNewContact} id={-1}/>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}


ReactDOM.render(
    <EmergencyContactList internshipId={window.internshipId}/>,
    document.getElementById('emergency-contact-list')
);
