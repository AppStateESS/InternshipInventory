// !!The internshipId variable is important!!

// It's being used as a global variable from the head.js where this file is located
// to determine which internship is loaded so it can grab the emergency contacts.


var EmergencyContactList = React.createClass({
    getInitialState: function() {
        return {
            emgConData: null,
            errorWarning: ''

        };
    },
    componentWillMount: function(){
        this.getData();
    },
    getData: function(){
        // Grabs the emergency contact data
        $.ajax({
            url: 'index.php?module=intern&action=emergencyContactRest&internshipid='+internshipId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {      
                this.setState({emgConData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab emergency contact data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)                
        });
    },
    onContactRemove: function(contactId){
        // Deletes the emergency contact.
        $.ajax({
            url: 'index.php?module=intern&action=emergencyContactRest&contactId='+contactId,
            type: 'DELETE',
            success: function() {       
                this.getData();
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to DELETE data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)                
        });
    },
    render: function() {
        if (this.state.emgConData != null)
        {       
            var onContactRemove = this.onContactRemove;
            var getData = this.getData;
            var eData = this.state.emgConData.map(function (conData) {           
            return (
                    <EmergencyList key={conData.id}
                                   id={conData.id}
                                   name={conData.name}
                                   relation={conData.relation}
                                   phone={conData.phone}
                                   email={conData.email}
                                   onContactRemove={onContactRemove}
                                   getData={getData} />
                );
            });
        }
        else
        {
            var eData = "";
        }
        return (
            <div className="form-horizontal">
                <ul className="list-group">
                    {eData}
                </ul>
                <div className="row">
                    <div className="col-lg-12 col-lg-offset-9">
                        <div className="form-group">
                            <ReactBootstrap.ModalTrigger modal={<ModalForm getData={this.getData} />}>
                                <ReactBootstrap.Button bsStyle='default'><i className="fa fa-plus"></i> Add Contact</ReactBootstrap.Button>
                            </ReactBootstrap.ModalTrigger>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
});

var EmergencyList = React.createClass({
    handleRemove: function(event) {
        // Prevents the modal trigger from occuring when presing
        // the remove button.
        event.stopPropagation();
        this.props.onContactRemove(this.props.id);      
    },
    render: function() {
        var contactInfo = this.props.name +" "+"\u2022"+" "+ 
                          this.props.relation +" "+"\u2022"+" "+ 
                          this.props.phone +" "+"\u2022"+" "+ 
                          this.props.email;
        var text = 
                <span>
                    {contactInfo}
                    <button type="button" className="close" data-dismiss="alert" aria-label="Close" onClick={this.handleRemove}><span aria-hidden="true">&times;</span></button>
                </span>
        return (
            <ReactBootstrap.ModalTrigger modal={<ModalForm edit = {true}
                                                           id = {this.props.id}
                                                           name = {this.props.name}
                                                           relation = {this.props.relation}
                                                           phone = {this.props.phone}
                                                           email = {this.props.email}
                                                           getData={this.props.getData} />}>

                <li className="list-group-item" style={{cursor: "pointer"}}>{text}</li>
            </ReactBootstrap.ModalTrigger>
        );
    }
});


// !!This uses ReactBoostrap!!
var ModalForm = React.createClass({
    getInitialState: function() {
        return {
            id: -1,
            name: '',
            relation: '',
            phone: '',
            email: '',
            showError: false
        };
    },
    componentWillMount: function() {
        if (this.props.edit == true)
        {
            this.setState({id: this.props.id,
                           name: this.props.name,
                           relation: this.props.relation,
                           phone: this.props.phone,
                           email: this.props.email });
        }
    },
    handleSave: function() {
        // Event handler to help save the comments.

        var url = '&internshipId='              +internshipId+
                  '&contactId= '                +this.state.id+
                  '&emergency_contact_name='    +this.state.name+
                  '&emergency_contact_relation='+this.state.relation+
                  '&emergency_contact_phone='   +this.state.phone+
                  '&emergency_contact_email='   +this.state.email;

        if (this.state.name == '' || this.state.relation == '' ||  this.state.phone == '' || this.state.email == '')
        {  
            // If any field is left empty, it will display an error message in the modal form.
            this.setState({showError: true});
        }
        else
        {
            // Updates or adds a new emergency contact
            $.ajax({
                url: 'index.php?module=intern&action=emergencyContactRest'+ url,
                type: 'POST',
                success: function() {    
                    // Grabs the new data and hides the form  
                    this.props.getData();
                    this.props.onRequestHide();
                }.bind(this),
                error: function(xhr, status, err) {
                    alert("Failed to put emergency contact data.")
                    console.error(this.props.url, status, err.toString());
                }.bind(this)                
            });   
        }
    },
    handleChangeForm: function(event) {
        // Event handler for each of the textboxes.
        switch(event.target.id) {
            case "emg-name":
                this.setState({name: event.target.value});
                break;
            case "emg-relation":
                this.setState({relation: event.target.value});
                break;
            case "emg-phone":
                this.setState({phone: event.target.value});
                break;
            case "emg-email":
                this.setState({email: event.target.value});
                break;  
        };
    },
    render: function() {
        var warning = <div id="warningError" className="alert alert-warning alert-dismissable" role="alert">
                        <button type="button"  className="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Warning!</strong> Please input a value into any empty text fields.
                      </div>
        return (
          <ReactBootstrap.Modal {...this.props} title='Add An Emergency Contact' animation={true}>
            <div className='modal-body'>

                {this.state.showError ? warning : null}

                      
                <div className="form-group">
                    <label className="col-lg-3">Name</label>
                    <div className="col-lg-9"><input  type="text" className="form-control" id="emg-name" value={this.state.name} onChange={this.handleChangeForm} /></div>
                </div>
                <br />
                <div className="form-group">
                    <label className="col-lg-3 control-label">Relation</label>
                    <div className="col-lg-9"><input  type="text" className="form-control" id="emg-relation" value={this.state.relation} onChange={this.handleChangeForm} /></div>
                </div>
                <br />
                <div className="form-group">
                    <label className="col-lg-3 control-label">Phone</label>
                    <div className="col-lg-9"><input  type="text" className="form-control" id="emg-phone" value={this.state.phone} onChange={this.handleChangeForm} /></div>
                </div>
                <br />
                <div className="form-group">
                    <label className="col-lg-3 control-label">Email</label>
                    <div className="col-lg-9"><input  type="text" className="form-control" id="emg-email" value={this.state.email} onChange={this.handleChangeForm} /></div>
                </div>
                <br />
            </div>


            <div className='modal-footer'>
              <ReactBootstrap.Button onClick={this.props.onRequestHide}>Close</ReactBootstrap.Button>
              <ReactBootstrap.Button bsStyle='primary' onClick={this.handleSave}>Save Changes</ReactBootstrap.Button>
            </div>
          </ReactBootstrap.Modal>
        );
    }
});

React.render(
    <EmergencyContactList />,
    document.getElementById('content')
);
