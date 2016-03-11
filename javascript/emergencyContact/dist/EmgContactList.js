// !!The internshipId variable is important!!

// It's being used as a global variable from the head.js where this file is located
// to determine which internship is loaded so it can grab the emergency contacts.

"use strict";

/****************************
 * Modal Form
 * This uses ReactBoostrap!!
 ****************************/

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var ModalForm = React.createClass({
    displayName: 'ModalForm',

    getInitialState: function getInitialState() {
        return { showError: false };
    },
    handleSave: function handleSave() {
        if (this.refs.emg_name.value == '' || this.refs.emg_relation.value == '' || this.refs.emg_phone.value == '') {
            // If any field is left empty, it will display an error message in the modal form.
            this.setState({ showError: true });
            return;
        } else {
            this.setState({ showError: false });
        }

        var contact = { id: this.props.id,
            name: this.refs.emg_name.value,
            relation: this.refs.emg_relation.value,
            phone: this.refs.emg_phone.value,
            email: this.refs.emg_email.value };

        // Call parent's save handler
        this.props.handleSaveContact(contact);
    },
    render: function render() {
        var warning = React.createElement(
            'div',
            { id: 'warningError', className: 'alert alert-warning alert-dismissable', role: 'alert' },
            React.createElement(
                'button',
                { type: 'button', className: 'close', 'data-dismiss': 'alert', 'aria-label': 'Close' },
                React.createElement(
                    'span',
                    { 'aria-hidden': 'true' },
                    '×'
                )
            ),
            React.createElement(
                'strong',
                null,
                'Warning!'
            ),
            ' Please input a value into any empty text fields.'
        );

        return React.createElement(
            ReactBootstrap.Modal,
            { show: this.props.show, onHide: this.props.hide, backdrop: 'static' },
            React.createElement(
                ReactBootstrap.Modal.Header,
                { closeButton: true },
                React.createElement(
                    ReactBootstrap.Modal.Title,
                    null,
                    'Emergency Contact'
                ),
                this.state.showError ? warning : null
            ),
            React.createElement(
                ReactBootstrap.Modal.Body,
                null,
                React.createElement(
                    'form',
                    { className: 'form-horizontal' },
                    React.createElement(
                        'div',
                        { className: 'form-group' },
                        React.createElement(
                            'label',
                            { className: 'col-lg-3 control-label' },
                            'Name'
                        ),
                        React.createElement(
                            'div',
                            { className: 'col-lg-9' },
                            React.createElement('input', { type: 'text', className: 'form-control', id: 'emg-name', ref: 'emg_name', defaultValue: this.props.name })
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'form-group' },
                        React.createElement(
                            'label',
                            { className: 'col-lg-3 control-label' },
                            'Relation'
                        ),
                        React.createElement(
                            'div',
                            { className: 'col-lg-9' },
                            React.createElement('input', { type: 'text', className: 'form-control', id: 'emg-relation', ref: 'emg_relation', defaultValue: this.props.relation })
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'form-group' },
                        React.createElement(
                            'label',
                            { className: 'col-lg-3 control-label' },
                            'Phone'
                        ),
                        React.createElement(
                            'div',
                            { className: 'col-lg-9' },
                            React.createElement('input', { type: 'text', className: 'form-control', id: 'emg-phone', ref: 'emg_phone', defaultValue: this.props.phone })
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'form-group' },
                        React.createElement(
                            'label',
                            { className: 'col-lg-3 control-label' },
                            'Email'
                        ),
                        React.createElement(
                            'div',
                            { className: 'col-lg-9' },
                            React.createElement('input', { type: 'text', className: 'form-control', id: 'emg-email', ref: 'emg_email', defaultValue: this.props.email })
                        )
                    )
                )
            ),
            React.createElement(
                ReactBootstrap.Modal.Footer,
                null,
                React.createElement(
                    ReactBootstrap.Button,
                    { onClick: this.handleSave },
                    'Save'
                ),
                React.createElement(
                    ReactBootstrap.Button,
                    { onClick: this.props.hide },
                    'Close'
                )
            )
        );
    }
});

/*********************
 * Emergency Contact *
 *********************/
var EmergencyContact = React.createClass({
    displayName: 'EmergencyContact',

    getInitialState: function getInitialState() {
        return { showModal: false };
    },
    closeModal: function closeModal() {
        this.setState({ showModal: false });
    },
    openModal: function openModal() {
        this.setState({ showModal: true });
    },
    handleSaveContact: function handleSaveContact(contact) {
        this.closeModal(); // Close the modal box
        this.props.handleSave(contact); // Call parent's handleSave method
    },
    handleRemove: function handleRemove(event) {
        // Prevents the modal trigger from occuring when presing
        // the remove button.
        event.stopPropagation();
        this.props.onContactRemove(this.props.id);
    },
    render: function render() {
        var contactInfo = React.createElement(
            'span',
            null,
            this.props.name,
            ' ',
            '•',
            ' ',
            this.props.relation,
            ' ',
            '•',
            ' ',
            this.props.phone,
            ' ',
            '•',
            ' ',
            this.props.email
        );
        return React.createElement(
            'li',
            { className: 'list-group-item', onClick: this.openModal, style: { cursor: "pointer" } },
            contactInfo,
            React.createElement(
                'button',
                { type: 'button', className: 'close', 'data-dismiss': 'alert', 'aria-label': 'Close', onClick: this.handleRemove },
                React.createElement(
                    'span',
                    { 'aria-hidden': 'true' },
                    '×'
                )
            ),
            React.createElement(ModalForm, _extends({ show: this.state.showModal, hide: this.closeModal, edit: true, handleSaveContact: this.handleSaveContact }, this.props))
        );
    }
});

var EmergencyContactList = React.createClass({
    displayName: 'EmergencyContactList',

    getInitialState: function getInitialState() {
        return {
            emgConData: null,
            showAddModal: false
        };
    },
    componentWillMount: function componentWillMount() {
        this.getData();
    },
    closeAddModal: function closeAddModal() {
        this.setState({ showAddModal: false });
    },
    openAddModal: function openAddModal() {
        this.setState({ showAddModal: true });
    },
    handleNewContact: function handleNewContact(contact) {
        this.closeAddModal(); // Close the modal box
        this.handleSave(contact); // Call parent's handleSave method
    },
    getData: function getData() {
        // Grabs the emergency contact data
        $.ajax({
            url: 'index.php?module=intern&action=emergencyContactRest&internshipId=' + internshipId,
            type: 'GET',
            dataType: 'json',
            success: (function (data) {
                this.setState({ emgConData: data });
            }).bind(this),
            error: (function (xhr, status, err) {
                alert("Failed to load emergency contact data.");
                console.error(this.props.url, status, err.toString());
            }).bind(this)
        });
    },
    handleSave: function handleSave(contact) {
        // Event handler to save the comments.

        // Updates or adds a new emergency contact
        $.ajax({
            url: 'index.php?module=intern&action=emergencyContactRest',
            type: 'POST',
            dataType: 'json',
            data: { internshipId: internshipId,
                contactId: contact.id,
                emergency_contact_name: contact.name,
                emergency_contact_relation: contact.relation,
                emergency_contact_phone: contact.phone,
                emergency_contact_email: contact.email
            },
            success: (function (data) {
                // Grabs the new data
                this.setState({ emgConData: data });
            }).bind(this),
            error: (function (xhr, status, err) {
                alert("Failed to save emergency contact data.");
                console.error(this.props.url, status, err.toString());
            }).bind(this)
        });
    },
    onContactRemove: function onContactRemove(contactId) {
        // Deletes the emergency contact.
        $.ajax({
            url: 'index.php?module=intern&action=emergencyContactRest&contactId=' + contactId + '&internshipId=' + internshipId,
            type: 'DELETE',
            dataType: 'json',
            success: (function (data) {
                this.setState({ emgConData: data });
            }).bind(this),
            error: (function (xhr, status, err) {
                alert("Failed to DELETE data.");
                console.error(this.props.url, status, err.toString());
            }).bind(this)
        });
    },
    render: function render() {
        if (this.state.emgConData != null) {
            var eData = this.state.emgConData.map((function (conData) {
                return React.createElement(EmergencyContact, { key: conData.id,
                    id: conData.id,
                    name: conData.name,
                    relation: conData.relation,
                    phone: conData.phone,
                    email: conData.email,
                    handleSave: this.handleSave,
                    onContactRemove: this.onContactRemove,
                    getData: this.getData });
            }).bind(this));
        } else {
            var eData = React.createElement(
                'p',
                { className: 'text-muted' },
                React.createElement('i', { className: 'fa fa-spinner fa-2x fa-spin' }),
                ' Loading Emergency Contacts...'
            );
        }

        return React.createElement(
            'div',
            null,
            React.createElement(
                'ul',
                { className: 'list-group' },
                eData
            ),
            React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-lg-12 col-lg-offset-9' },
                    React.createElement(
                        'div',
                        { className: 'form-group' },
                        React.createElement(
                            'button',
                            { type: 'button', className: 'btn btn-default', onClick: this.openAddModal },
                            React.createElement('i', { className: 'fa fa-plus' }),
                            ' Add Contact'
                        ),
                        React.createElement(ModalForm, { show: this.state.showAddModal, hide: this.closeAddModal, edit: false, handleSaveContact: this.handleNewContact, id: -1 })
                    )
                )
            )
        );
    }
});

ReactDOM.render(React.createElement(EmergencyContactList, null), document.getElementById('emergency-contact-list'));
//# sourceMappingURL=EmgContactList.js.map
