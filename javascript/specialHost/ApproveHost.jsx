import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import {Button, Modal} from 'react-bootstrap';
import Message from '../emergencyContact/Message.jsx';

class ModalFormHost extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            showError: false,
            warningMsg: ''
        };

        this.handleSave = this.handleSave.bind(this);
        this.handleExit = this.handleExit.bind(this);
    }
    handleSave() {
        if (this.refs.host_name.value === '') {
            this.setState({showError: true, warningMsg: "Please enter a name of host."});
            return;
        }
        if (this.refs.host_state.length > 2) {
            this.setState({showError: true, warningMsg: "Please an abbreviation of the State."});
            return;
        }

        this.setState({showError: false,
                       warningMsg: ""});
        var host = {id: this.props.key,
                       main: this.refs.host_main.value,
                       name: this.refs.host_name.value,
                       address: this.refs.host_address.value,
                       city: this.refs.host_city.value,
                       state: this.refs.host_state.value,
                       zip: this.refs.host_zip.value,
                       province: this.refs.host_province.value,
                       country: this.refs.host_country.value,
                       other:this.refs.host_other.value,
                       condition: this.refs.host_condition.value,
                       flag: this.refs.host_flag.value};

        // Call parent's save handler
        this.props.handleSaveHost(host);
    }
    handleExit(){
        //resets state so any warnings previously are reset.
        this.setState({
            showError: false,
            warningMsg: '',
        })
        this.props.hide();
    }
    render() {
        // Create red asterisk for a required field
        var require = <span style={{color: '#FB0000'}}> *</span>;
        return (
            <Modal show={this.props.show} onHide={this.handleExit} backdrop='static'>
                <Modal.Header closeButton>
                  <Modal.Title>Edit Host</Modal.Title>
                  {this.state.showError ? <Message type="warning" children={this.state.warningMsg}></Message> : null}
                </Modal.Header>
                <Modal.Body>
                    <form className="form-horizontal">
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Host Name</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-main" ref="host_main" defaultValue={this.props.main}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Sub Name</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-name" ref="host_name" defaultValue={this.props.name}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Address</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-address" ref="host_address" defaultValue={this.props.address}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">City</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-city" ref="host_city" defaultValue={this.props.city}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">State</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-state" ref="host_state" defaultValue={this.props.state}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Zip/Postal</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-zip" ref="host_zip" defaultValue={this.props.zip}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Province</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-province" ref="host_province" defaultValue={this.props.province}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Country</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-country" ref="host_country" defaultValue={this.props.country}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Other Name</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-other" ref="host_other" defaultValue={this.props.other}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Special Condition</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-condition" ref="host_condition" defaultValue={this.props.condition}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Approve {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-flag" ref="host_flag" defaultValue={this.props.flag}/></div>
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
class ModalFormCondition extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            showError: false,
            warningMsg: ''
        };

        this.handleSave = this.handleSave.bind(this);
        this.handleExit = this.handleExit.bind(this);
    }
    handleSave() {
        if (this.refs.admin_message.value === '') {
            this.setState({showError: true, warningMsg: "Please enter a name for the condition."});
            return;
        }

        this.setState({showError: false,
                       warningMsg: ""});
        var host = {id: this.props.key,
                       admin_message: this.refs.admin_message.value,
                       user_message: this.refs.user_message.value,
                       stop_level: this.refs.stop_level.value,
                       sup_check: this.refs.sup_check.value,
                       email: this.refs.email.value,
                       special_notes: this.refs.notes.value};
console.log(host)
        // Call parent's save handler
        this.props.handleSaveHost(host);
    }
    handleExit(){
        //resets state so any warnings previously are reset.
        this.setState({
            showError: false,
            warningMsg: '',
        })
        this.props.hide();
    }
    render() {
        // Create red asterisk for a required field
        var require = <span style={{color: '#FB0000'}}> *</span>;
        return (
            <Modal show={this.props.show} onHide={this.handleExit} backdrop='static'>
                <Modal.Header closeButton>
                  <Modal.Title>Edit Condition</Modal.Title>
                  {this.state.showError ? <Message type="warning" children={this.state.warningMsg}></Message> : null}
                </Modal.Header>
                <Modal.Body>
                    <form className="form-horizontal">
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Admin Name</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="admin" ref="admin_message" defaultValue={this.props.admin}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Stop Level</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="stop" ref="stop_level" defaultValue={this.props.stop}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">User Message</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="user" ref="user_message" defaultValue={this.props.user}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Email Listed</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="email" ref="email" defaultValue={this.props.email}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Supervisor</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="sup" ref="sup_check" defaultValue={this.props.sup}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Notes</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="notes" ref="notes" defaultValue={this.props.notes}/></div>
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
class ShowApprove extends React.Component {
    constructor(props){
        super(props);
        this.state = {showModal: false};

        this.closeModal = this.closeModal.bind(this);
        this.openModal = this.openModal.bind(this);
        this.handleSaveHost = this.handleSaveHost.bind(this);
    }
    closeModal() {
        this.setState({ showModal: false });
    }
    openModal() {
        this.setState({ showModal: true });
    }
    handleSaveHost(host){
        this.closeModal(); // Close the modal box
        this.props.handleSave(host); // Call parent's handleSave method
    }
    render(){
        return (
            <li className="list-group-item" onClick={this.openModal} style={{cursor: "pointer"}}>
                <ModalFormHost show={this.state.showModal} hide={this.closeModal} edit={true} handleSaveHost={this.handleSaveHost}{...this.props} />
                <div className="row">
                    <div className="col-lg-2">{this.props.main}</div>
                </div>
            </li>

        );
    }
}

class ShowHostCon extends React.Component {
    constructor(props){
        super(props);
        this.state = {showModal: false};

        this.closeModal = this.closeModal.bind(this);
        this.openModal = this.openModal.bind(this);
        this.handleSaveHost = this.handleSaveHost.bind(this);
    }
    closeModal() {
        this.setState({ showModal: false });
    }
    openModal() {
        this.setState({ showModal: true });
    }
    handleSaveHost(host){
        this.closeModal(); // Close the modal box
        this.props.handleSave(host); // Call parent's handleSave method
    }
    render(){
        return (
            <li className="list-group-item" onClick={this.openModal} style={{cursor: "pointer"}}>
                <ModalFormHost show={this.state.showModal} hide={this.closeModal} edit={true} handleSaveHost={this.handleSaveHost}{...this.props} />
                <div className="row">
                    <div className="col-lg-2">{this.props.main}</div>
                    <div className="col-lg-3">{this.props.name}</div>
                    <div className="col-lg-2">{this.props.address}</div>
                    <div className="col-lg-1">{this.props.city}</div>
                    <div className="col-lg-1">{this.props.state}</div>
                    <div className="col-lg-1">{this.props.condition}</div>
                    <div className="col-lg-2">{this.props.notes}</div>
                </div>
            </li>

        );
    }
}

class ShowCondition extends React.Component {
    constructor(props){
        super(props);
        this.state = {showModal: false};

        this.closeModal = this.closeModal.bind(this);
        this.openModal = this.openModal.bind(this);
        this.handleSaveHost = this.handleSaveHost.bind(this);
    }
    closeModal() {
        this.setState({ showModal: false });
    }
    openModal() {
        this.setState({ showModal: true });
    }
    handleSaveHost(host){
        this.closeModal(); // Close the modal box
        this.props.handleSave(host); // Call parent's handleSave method
    }
    render(){
        return (
            <li className="list-group-item" onClick={this.openModal} style={{cursor: "pointer"}}>
                <ModalFormCondition show={this.state.showModal} hide={this.closeModal} edit={true} handleSaveHost={this.handleSaveHost}{...this.props} />
                <div className="row">
                    <div className="col-lg-2">{this.props.admin}</div>
                    <div className="col-lg-2">{this.props.stop}</div>
                    <div className="col-lg-4">{this.props.user}</div>
                    <div className="col-lg-1">{this.props.sup}</div>
                    <div className="col-lg-1">{this.props.email}</div>
                    <div className="col-lg-2">{this.props.notes}</div>
                </div>
            </li>

        );
    }
}

class ShowAllHost extends React.Component {
    constructor(props){
        super(props);
        this.state = {showModal: false};

        this.closeModal = this.closeModal.bind(this);
        this.openModal = this.openModal.bind(this);
        this.handleSaveHost = this.handleSaveHost.bind(this);
    }
    closeModal() {
        this.setState({ showModal: false });
    }
    openModal() {
        this.setState({ showModal: true });
    }
    handleSaveHost(host){
        this.closeModal(); // Close the modal box
        this.props.handleSave(host); // Call parent's handleSave method
    }
    render(){
        return (
            <li className="list-group-item" onClick={this.openModal} style={{cursor: "pointer"}}>
                <ModalFormHost show={this.state.showModal} hide={this.closeModal} edit={true} handleSaveHost={this.handleSaveHost}{...this.props} />
                <div className="row">
                    <div className="col-lg-2">{this.props.main}</div>
                    <div className="col-lg-3">{this.props.name}</div>
                    <div className="col-lg-2">{this.props.address}</div>
                    <div className="col-lg-1">{this.props.city}</div>
                    <div className="col-lg-1">{this.props.state}</div>
                    <div className="col-lg-1">{this.props.zip}</div>
                    <div className="col-lg-1">{this.props.province}</div>
                    <div className="col-lg-1">{this.props.country}</div>
                    <div className="col-lg-1">{this.props.other}</div>
                </div>
            </li>

        );
    }
}

// Main module that lists hosts.
class AllHostList extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            mainData: null,
            approveData: null,
            hostConData: null,
            conditionData: null,
            showAddModal: false
        };

        this.openAddModal = this.openAddModal.bind(this);
        this.closeAddModal = this.closeAddModal.bind(this);
        this.handleSaveSub = this.handleSaveSub.bind(this);
        this.handleSaveCondition = this.handleSaveCondition.bind(this);
    }
    componentWillMount() {
        this.getData();
    }
    openAddModal() {
        this.setState({ showAddModal: true });
    }
    closeAddModal() {
        this.setState({ showAddModal: false });
    }
    getData() {
        $.ajax({
            url: 'index.php?module=intern&action=HostRest&Waiting=true',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({aproveData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab approval data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
        $.ajax({
            url: 'index.php?module=intern&action=SubRest&Conditions=true',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({hostConData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab host data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
        $.ajax({
            url: 'index.php?module=intern&action=ConditionRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({conditionData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab condition data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
        $.ajax({
            url: 'index.php?module=intern&action=SubRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({mainData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab all host data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    handleSaveSub(host) {
        $.ajax({
            url: 'index.php?module=intern&action=SubRest',
            type: 'PUT',
            dataType: 'json',
            data: {id: host.id, name: host.name, main: host.main, address: host.address,
                city: host.city, state: host.state, zip: host.zip, province: host.province,
                country: host.country, other: host.other, condition: host.condition, flag: host.flag,
               },
            success: function(data) {
                // Grabs the new data
                this.setState({mainData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to save host data.")
            }
        });
    }
    handleSaveCondition(con) {
        $.ajax({
            url: 'index.php?module=intern&action=ConditionRest',
            type: 'PUT',
            dataType: 'json',
            data: {id: con.id, admin: con.admin_message, user: con.user_message,
                stop: con.stop_level, sup: con.sup_check, email: con.email, notes: con.special_notes
               },
            success: function(data) {
                // Grabs the new data
                this.setState({mainData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to save host data.")
            }
        });
    }
    render() {
        var ApproveData = null;
        if (this.state.approveData != null) {
            ApproveData = this.state.approveData.map(function (host) {
                return (
                    <ShowApprove key={host.id} main={host.main} handleSave={this.handleSave}/>
                );
            }.bind(this));
        } else {
            ApproveData = <p className="text-muted">No Hosts to Approve</p>;
        }

        var HostConData = null;
        if (this.state.hostConData != null) {
            HostConData = this.state.hostConData.map(function (host) {
                return (
                    <ShowHostCon key={host.id} main={host.host_name} name={host.sub_name} address={host.address}
                        city={host.city} state={host.state} condition={host.admin_message} notes={host.notes} handleSave={this.handleSaveSub}/>
                );
            }.bind(this));
        } else {
            HostConData = <p className="text-muted"><i className="fa fa-spinner fa-2x fa-spin"></i> Loading Host With Conditions...</p>;
        }

        var ConditionData = null;
        if (this.state.conditionData != null) {
            ConditionData = this.state.conditionData.map(function (host) {
                return (
                    <ShowCondition key={host.id} admin={host.admin_message} stop={host.stop_level} user={host.user_message}
                        sup={host.sup_check} email={host.email} notes={host.notes} handleSave={this.handleSaveCondition}/>
                );
            }.bind(this));
        } else {
            ConditionData = <p className="text-muted"><i className="fa fa-spinner fa-2x fa-spin"></i> Loading Conditions...</p>;
        }

        var HostData = null;
        if (this.state.mainData != null) {
            HostData = this.state.mainData.map(function (host) {
                return (
                    <ShowAllHost key={host.id} main={host.host_name} name={host.sub_name} address={host.address}
                        city={host.city} state={host.state} zip={host.zip} province={host.province} country={host.country}
                        other={host.other_name} condition={host.sub_condition} flag={host.sub_approve_flag} handleSave={this.handleSaveSub}/>
                );
            }.bind(this));
        } else {
            HostData = <p className="text-muted"><i className="fa fa-spinner fa-2x fa-spin"></i> Loading All Hosts...</p>;
        }

        return (
            <div className="hostList">
                <div className="row">
                    <div className="col-md-4">
                        <h3>Hosts To Approve</h3>
                    </div>
                </div>
                <ul className="list-group">
                    {ApproveData}
                </ul>
                <br></br>
                <div className="row">
                    <div className="col-md-4">
                        <h3>Hosts With Conditions</h3>
                    </div>
                </div>
                <div className="hostTable">
                        <table className="table">
                            <thead>
                                <tr>
                                    <th className="col-lg-2">Name</th>
                                    <th className="col-lg-3">Sub</th>
                                    <th className="col-lg-2">Address</th>
                                    <th className="col-lg-1">City</th>
                                    <th className="col-lg-1">State</th>
                                    <th className="col-lg-1">Condition</th>
                                    <th className="col-lg-2">Notes</th>
                                </tr>
                            </thead>
                        </table>
                    <ul className="list-group">
                        {HostConData}
                    </ul>
                </div>
                <br></br>
                <div className="row">
                    <div className="col-md-4">
                        <h3>Conditions</h3>
                    </div>
                </div>
                <div className="hostTable">
                        <table className="table">
                            <thead>
                                <tr>
                                    <th className="col-lg-2">Admin Name</th>
                                    <th className="col-lg-2">Stop Level</th>
                                    <th className="col-lg-4">User Message</th>
                                    <th className="col-lg-1">Supervisor Check</th>
                                    <th className="col-lg-1">Email Listed</th>
                                    <th className="col-lg-2">Notes</th>
                                </tr>
                            </thead>
                        </table>
                    <ul className="list-group">
                        {ConditionData}
                    </ul>
                </div>
                <br></br>
                <div className="row">
                    <div className="col-md-4">
                        <h3>All Hosts</h3>
                    </div>
                </div>
                        <table className="table">
                            <thead>
                                <tr>
                                    <th className="col-lg-2">Name</th>
                                    <th className="col-lg-3">Sub</th>
                                    <th className="col-lg-2">Address</th>
                                    <th className="col-lg-1">City</th>
                                    <th className="col-lg-1">State</th>
                                    <th className="col-lg-1">Zip</th>
                                    <th className="col-lg-1">Province</th>
                                    <th className="col-lg-1">Country</th>
                                </tr>
                            </thead>
                        </table>
                    <ul className="list-group">
                        {HostData}
                    </ul>
            </div>

        );
    }
}

ReactDOM.render(<AllHostList />, document.getElementById('approve_host'));
