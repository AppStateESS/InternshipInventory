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
        var host = {id: this.props.id,
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
        var conData = null;
        if (this.props.conditionData != null) {
            conData = this.props.conditionData.map(function (condition) {
            return (
                    <option key={condition.id} value={condition.id}>{condition.admin_message}</option>
                );
            });
        } else {
            conData = "";
        }
        // Create red asterisk for a required field
        var require = <span style={{color: '#FB0000'}}> *</span>;
        return (
            <Modal show={this.props.show} onHide={this.handleExit} backdrop='static'>
                <Modal.Header closeButton>
                  <Modal.Title>Edit Sub Host</Modal.Title>
                  {this.state.showError ? <Message type="warning" children={this.state.warningMsg}></Message> : null}
                </Modal.Header>
                <Modal.Body>
                    <form className="form-horizontal">
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Host Name</label>
                            <div className="col-lg-9"><p  type="text" className="form-control-static" id="host-main" ref="host_main">{this.props.main}</p></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Sub Name {require}</label>
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
                            <label className="col-lg-3 control-label">Sub Condition</label>
                            <select className="form-control col-lg-7 select-sub-host" id="host-condition" ref="host_condition" defaultValue={this.props.conId}>
                                <option value="-1">Select a Condition</option>
                                {conData}
                            </select>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Approve {require}</label>
                                <select className="form-control col-lg-7 select-sub-host" id="host-flag" ref="host_flag" defaultValue={this.props.flag}>
                                    <option value="0">Not Approved</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Waiting</option>
                                </select>
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
        var host = {id: this.props.id,
                       admin_message: this.refs.admin_message.value,
                       user_message: this.refs.user_message.value,
                       stop_level: this.refs.stop_level.value,
                       sup_check: this.refs.sup_check.value,
                       email: this.refs.email.value,
                       special_notes: this.refs.notes.value};

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
                            <label className="col-lg-3 control-label">Admin Name {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="admin" ref="admin_message" defaultValue={this.props.admin}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Stop Level {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="stop" ref="stop_level" defaultValue={this.props.stop}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">User Message {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="user" ref="user_message" defaultValue={this.props.user}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Email Listed {require}</label>
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

class ModalFormHostCondition extends React.Component {
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
        let newdate = Math.round(new Date().getTime()/1000);
        this.setState({showError: false,
                       warningMsg: ""});
        var host = {id: this.props.id,
                       name: this.refs.name.value,
                       condition: this.refs.host_condition.value,
                       flag: this.refs.flag.value,
                       date: newdate,
                       notes: this.refs.notes.value};

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
        var conData = null;
        if (this.props.conditionData != null) {
            conData = this.props.conditionData.map(function (condition) {
            return (
                    <option key={condition.id} value={condition.id}>{condition.admin_message}</option>
                );
            });
        } else {
            conData = "";
        }
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
                            <label className="col-lg-3 control-label">Host Name {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="name" ref="name" defaultValue={this.props.name}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Condition</label>
                            <select className="form-control col-lg-7 select-sub-host" id="host-condition" ref="host_condition" defaultValue={this.props.conId}>
                                <option value="-1">Select a Condition</option>
                                {conData}
                            </select>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Approve {require}</label>
                                <select className="form-control col-lg-7 select-sub-host" id="host-flag" ref="flag" defaultValue={this.props.flag}>
                                    <option value="0">Not Approved</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Waiting</option>
                                </select>
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
        this.state = {
            conditionData: this.props.conditionData,
            availableHost: this.props.hostData
        };

        this.handleApproveSave = this.handleApproveSave.bind(this);
        this.handleSwitchSave = this.handleSwitchSave.bind(this);
    }

    handleApproveSave(){
        var newMain = ReactDOM.findDOMNode(this.refs.newMain).value.trim();
        var newCon = ReactDOM.findDOMNode(this.refs.con_data).value;
        let newdate = Math.round(new Date().getTime()/1000);
        let dataToSend = {id: this.props.id, name: newMain, condition: newCon, flag: 1, date: newdate}
        this.props.handleApproveSave(dataToSend);
    }

    handleSwitchSave(){
        //set current host to denied and change everywhere host and it's sub is used to the switched id
        var newHost = ReactDOM.findDOMNode(this.refs.new_host).value;
        var host = {id: newHost, old: this.props.id};

        // Call parent's save handler
        if(newHost !== '' && newHost !== '-1'){
            this.props.handleSwitchSave(host);
        }
    }

    render(){
        var availHost = null;
        if (this.state.availableHost != null) {
            availHost = this.state.availableHost.map(function (available) {
            return (
                    <option key={available.id} value={available.id}>{available.host_name}</option>
                );
            });
        } else {
            availHost = "";
        }
        var conData = null;
        if (this.state.conditionData != null) {
            conData = this.state.conditionData.map(function (condition) {
            return (
                    <option key={condition.id} value={condition.id}>{condition.admin_message}</option>
                );
            });
        } else {
            conData = "";
        }
        return (
            <li className="list-group-item" style={{cursor: "pointer"}}>
                <div className="row">
                    <div className="col-lg-4"><input type="text" className="form-control" ref="newMain" defaultValue={this.props.main}/></div>
                    <select className="col-lg-2" id="con-data" ref="con_data">
                        <option value="-1">Select a Condition</option>
                        {conData}
                    </select>
                    <div className="col-lg-2"><button className="btn btn-success" onClick={this.handleApproveSave}>Approve</button></div>
                    <select className="col-lg-3 align-right" id="new-host" ref="new_host">
                        <option value="-1">Select a Host</option>
                        {availHost}
                    </select>
                    <div className="col-lg-1"><button className="btn btn-primary" onClick={this.handleSwitchSave}>Switch</button></div>
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
        let readDate = null
        if(this.props.date > 1577876400){
            readDate = new Date(this.props.date*1000).toLocaleDateString();
        } else{ readDate = ' '}
        let apFlag = null
        if(this.props.flag === 0){apFlag = 'Not Approved'}
        else if(this.props.flag === 1){apFlag = 'Approved'}
        else{apFlag = 'Waiting'}
        return (
            <li className="list-group-item" onClick={this.openModal} style={{cursor: "pointer"}}>
                <ModalFormHostCondition show={this.state.showModal} hide={this.closeModal} edit={true} handleSaveHost={this.handleSaveHost}{...this.props} />
                <div className="row">
                    <div className="col-lg-3">{this.props.name}</div>
                    <div className="col-lg-2">{this.props.condition}</div>
                    <div className="col-lg-1">{readDate}</div>
                    <div className="col-lg-2">{apFlag}</div>
                    <div className="col-lg-4">{this.props.notes}</div>
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
                    <div className="col-lg-1">{this.props.stop}</div>
                    <div className="col-lg-4">{this.props.user}</div>
                    <div className="col-lg-1">{this.props.sup}</div>
                    <div className="col-lg-2">{this.props.email}</div>
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
            mainDisplayData: null,
            approveData: null,
            hostConData: null,
            hostDisplayData: null,
            conditionData: null,
            hostData: null,
            showAddModal: false,
            searchName: '',
            sortBy: '',
            showFilter: '',
            searchHostName: '',
            sortHostBy: '',
            showHostFilter: ''
        };

        this.openAddModal = this.openAddModal.bind(this);
        this.closeAddModal = this.closeAddModal.bind(this);
        this.handleSaveSub = this.handleSaveSub.bind(this);
        this.handleSaveCondition = this.handleSaveCondition.bind(this);
        this.handleApproveSave = this.handleApproveSave.bind(this);
        this.handleSwitchSave = this.handleSwitchSave.bind(this);
        this.handleHostConSave = this.handleHostConSave.bind(this);
        this.onSearchListChange = this.onSearchListChange.bind(this);
        this.onSortByChange = this.onSortByChange.bind(this);
        this.onShow = this.onShow.bind(this);
        this.onSearchHostListChange = this.onSearchHostListChange.bind(this);
        this.onSortByHostChange = this.onSortByHostChange.bind(this);
        this.onHostShow = this.onHostShow.bind(this);
        this.viewShowFilter = this.viewShowFilter.bind(this)
        this.updateDisplayData = this.updateDisplayData.bind(this);
    }
    componentWillMount() {
        this.getData();
        this.getHostConData()
        this.getMainData()
    }
    openAddModal() {
        this.setState({ showAddModal: true });
    }
    closeAddModal() {
        this.setState({ showAddModal: false });
    }
    getData() {
        $.ajax({
            url: 'index.php?module=intern&action=HostRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({approveData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab wating approval data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
        $.ajax({
            url: 'index.php?module=intern&action=HostRest&Waiting=false',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({hostData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab approval data.")
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
    }
    getMainData(){
        $.ajax({
            url: 'index.php?module=intern&action=SubRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({mainData: data, mainDisplayData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab all host data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    getHostConData(){
        $.ajax({
            url: 'index.php?module=intern&action=HostRest&Condition=true',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({hostConData: data, hostDisplayData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab wating approval data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    handleSaveSub(host) {
        $.ajax({
            url: 'index.php?module=intern&action=SubRest',
            type: 'PUT',
            dataType: 'json',
            data: JSON.stringify({id: host.id, name: host.name, address: host.address,
                city: host.city, state: host.state, zip: host.zip, province: host.province,
                country: host.country, other: host.other, condition: host.condition, flag: host.flag,
            }),
            success: function(data) {
                this.getMainData()
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
            data: JSON.stringify({id: con.id, admin: con.admin_message, user: con.user_message,
                stop: con.stop_level, sup: con.sup_check, email: con.email, notes: con.special_notes
            }),
            success: function(data) {
                // Grabs the new data
                this.setState({conditionData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to save condition data.")
            }
        });
    }
    handleApproveSave(data){
        $.ajax({
            url: 'index.php?module=intern&action=HostRest',
            type: 'PUT',
            dataType: 'json',
            data: JSON.stringify(data),
            success: function(data) {
                // Grabs the new data
                this.setState({approveData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to save approve host data.")
            }
        });
    }
    handleSwitchSave(data){
        //set current host to denied and change everywhere host and it's sub is used to the switched id
        $.ajax({
            url: 'index.php?module=intern&action=HostRest',
            type: 'PUT',
            dataType: 'json',
            data: JSON.stringify(data),
            success: function(data) {
                // Grabs the new data
                this.setState({approveData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to switch host data.")
            }
        });
    }
    handleHostConSave(data){
        //set current host to denied and change everywhere host and it's sub is used to the switched id
        $.ajax({
            url: 'index.php?module=intern&action=HostRest&HostCon=true',
            type: 'PUT',
            dataType: 'json',
            data: JSON.stringify(data),
            success: function(data) {
                // Grabs the new data
                this.getHostConData()
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to update host condition data.")
            }
        });
    }
    onSearchListChange(e) {
        var name = null;
        name = e.target.value.toLowerCase();
        this.setState({searchName: name});
        this.updateDisplayData(name, this.state.sortBy, this.state.showFilter, this.state.mainData, 'mainDisplayData');
    }
    onSearchHostListChange(e) {
        var name = null;
        name = e.target.value.toLowerCase();
        this.setState({searchHostName: name});
        this.updateDisplayData(name, this.state.sortHostBy, this.state.showHostFilter, this.state.hostConData, 'hostDisplayData');
    }
    searchListByName(data, nameToSearch, display) {
      var filtered = [];
      // Looks for the name by filtering the mainData
      for (var i = 0; i < data.length; i++) {
          var item = data[i];
          // Make the item, name lowercase for easier searching
          if (display === 'mainDisplayData' && item.sub_name.toLowerCase().includes(nameToSearch)) {
              filtered.push(item);
          }
          if (display === 'hostDisplayData' && item.host_name.toLowerCase().includes(nameToSearch)) {
              filtered.push(item);
          }
      }
      return filtered;
    }
    onSortByChange(e) {
        var sort = null;
        sort = e.target.value;
        this.setState({sortBy: sort});
        this.updateDisplayData(this.state.searchName, sort, this.state.showFilter, this.state.mainData, 'mainDisplayData');
    }
    onSortByHostChange(e) {
        var sort = null;
        sort = e.target.value;
        this.setState({sortHostBy: sort});
        this.updateDisplayData(this.state.searchHostName, sort, this.state.showHostFilter, this.state.hostConData, 'hostDisplayData');
    }
    sortBy(unsorted, typeOfSort) {
      var sorted = [];
      switch(typeOfSort) {
          case 'subA':
              sorted = unsorted.sort(function (a, b) {
                  if (a.sub_name.toLowerCase() < b.sub_name.toLowerCase()) return -1;
                  if (a.sub_name.toLowerCase() > b.sub_name.toLowerCase()) return 1;
                  return 0;
              });
              break;
          case 'subZ':
              sorted = unsorted.sort(function (a, b) {
                  if (a.sub_name.toLowerCase() > b.sub_name.toLowerCase()) return -1;
                  if (a.sub_name.toLowerCase() < b.sub_name.toLowerCase()) return 1;
                  return 0;
              });
              break;
          case 'hostA':
              sorted = unsorted.sort(function (a,b) {
                  if (a.host_name.toLowerCase() < b.host_name.toLowerCase()) return -1;
                  if (a.host_name.toLowerCase() > b.host_name.toLowerCase()) return 1;
                  return 0;
              });
              break;
          case 'hostZ':
              sorted = unsorted.sort(function (a,b) {
                  if (a.host_name.toLowerCase() > b.host_name.toLowerCase()) return -1;
                  if (a.host_name.toLowerCase() < b.host_name.toLowerCase()) return 1;
                  return 0;
              });
              break;
          default:
              sorted = unsorted;
      }
      return sorted;

    }
    onShow(e) {
        var option = null;
        option = e.target.value;
        this.setState({showFilter: option});
        this.updateDisplayData(this.state.searchName, this.state.sortBy, option, this.state.mainData, 'mainDisplayData');

    }
    onHostShow(e) {
        var option = null;
        option = e.target.value;
        this.setState({showHostFilter: option});
        this.updateDisplayData(this.state.searchHostName, this.state.sortHostBy, option, this.state.hostConData, 'hostDisplayData');

    }
    viewShowFilter(data, filter) {
        var filtered = [];
        for (var i = 0; i < data.length; i++) {
            var item = data[i];
            if (filter === 'condition') {
                if (typeof(item.sub_condition) === 'number') {
                    filtered.push(item);
                }
            }
            else if (filter === 'conditions') {
                if (typeof(item.host_condition) === 'number') {
                    filtered.push(item);
                }
            }
            else if (filter === 'approved') {
                if (item.host_approve_flag === 1) {
                    filtered.push(item);
                }
            }
            else {
                filtered.push(item);
            }
        }
        return filtered;

    }
    updateDisplayData(typedName, sort, showFilter, data, display) {
        var filtered = [];

        // First filters data.
        if (showFilter !== null) {
            filtered = this.viewShowFilter(data, showFilter);
        } else {
            filtered = data;
        }

        // Second searches list for name.
        if (typedName !== null) {
            filtered = this.searchListByName(filtered, typedName, display);
        }

        // Third sorts list.
        if (sort !== null) {
            filtered = this.sortBy(filtered, sort);
        } else {
            filtered = this.sortBy(filtered, 'hostA');
        }

        if(display === 'mainDisplayData'){this.setState({mainDisplayData: filtered});}
        else{this.setState({hostDisplayData: filtered});}

    }
    render() {
        var ApproveData = null;
        if (this.state.approveData != null && this.state.hostData != null && this.state.conditionData != null) {
            ApproveData = this.state.approveData.map(function (host) {
                return (
                    <ShowApprove key={host.id} id={host.id} main={host.host_name} condition={host.host_condition} conditionData={this.state.conditionData}
                        hostData={this.state.hostData} handleApproveSave={this.handleApproveSave} handleSwitchSave={this.handleSwitchSave}/>
                );
            }.bind(this));
        } else {
            ApproveData = <p className="text-muted"><i className="fa fa-spinner fa-2x fa-spin"></i> Loading Hosts to Approve...</p>;
        }

        var HostConData = null;
        if (this.state.hostConData != null && this.state.conditionData != null) {
            HostConData = this.state.hostDisplayData.map(function (host) {
                return (
                    <ShowHostCon key={host.id} id={host.id} name={host.host_name} conditionData={this.state.conditionData} conId={host.con_id}
                        condition={host.admin_message} date={host.host_condition_date} flag={host.host_approve_flag} notes={host.host_notes} handleSave={this.handleHostConSave}/>
                );
            }.bind(this));
        } else {
            HostConData = <p className="text-muted"><i className="fa fa-spinner fa-2x fa-spin"></i> Loading Host With Conditions...</p>;
        }

        var ConditionData = null;
        if (this.state.conditionData != null) {
            ConditionData = this.state.conditionData.map(function (host) {
                return (
                    <ShowCondition key={host.id} id={host.id} admin={host.admin_message} stop={host.stop_level} user={host.user_message}
                        sup={host.sup_check} email={host.email} notes={host.special_notes} handleSave={this.handleSaveCondition}/>
                );
            }.bind(this));
        } else {
            ConditionData = <p className="text-muted"><i className="fa fa-spinner fa-2x fa-spin"></i> Loading Conditions...</p>;
        }

        var HostData = null;
        if (this.state.mainData != null) {
            HostData = this.state.mainDisplayData.map(function (host) {
                return (
                    <ShowAllHost key={host.id} id={host.id} main={host.host_name} name={host.sub_name} address={host.address}
                        city={host.city} state={host.state} zip={host.zip} province={host.province} country={host.country}
                        other={host.other_name} conditionData={this.state.conditionData} condition={host.sub_condition}
                        conId={host.con_id} flag={host.sub_approve_flag} handleSave={this.handleSaveSub}/>
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
                        <h3>Conditions</h3>
                    </div>
                </div>
                <div className="hostTable">
                        <table className="table">
                            <thead>
                                <tr>
                                    <th className="col-lg-2">Admin Name</th>
                                    <th className="col-lg-1">Stop Level</th>
                                    <th className="col-lg-4">User Message</th>
                                    <th className="col-lg-1">Supervisor Check</th>
                                    <th className="col-lg-2">Email Listed</th>
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
                            <h3>Hosts</h3>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-md-3">
                            <div className="input-group">
                                <label>Search by Name</label>
                                <input type="text" className="form-control" placeholder="Search for..." onChange={this.onSearchHostListChange} />
                            </div>
                        </div>
                        <div className="col-md-2">
                            <label className="control-label">Sort By</label> <br />
                            <div className="btn-group" data-toggle="buttons" onClick={this.onSortByHostChange} value={this.state.value}>
                                <button className="btn btn-default" value="hostA">
                                    <input  type="radio"/>Host A-Z
                                </button>
                                <button className="btn btn-default" value="hostZ">
                                    <input  type="radio"/>Host Z-A
                                </button>
                            </div>
                        </div>
                        <div className="col-md-3">
                            <label className="control-label">Filter</label> <br />
                            <div className="btn-group" data-toggle="buttons" onClick={this.onHostShow} value={this.state.value}>
                                <button className="btn btn-default" value="all">
                                    <input  type="radio"/>All
                                </button>
                                <button className="btn btn-default" value="conditions">
                                    <input type="radio"/>Conditions
                                </button>
                                <button className="btn btn-default" value="approved">
                                    <input type="radio"/>Approved
                                </button>
                            </div>
                        </div>
                    </div>
                    <div className="hostTable">
                            <table className="table">
                                <thead>
                                    <tr>
                                        <th className="col-lg-3">Name</th>
                                        <th className="col-lg-2">Condition</th>
                                        <th className="col-lg-1">Condition Changed</th>
                                        <th className="col-lg-2">Approval</th>
                                        <th className="col-lg-4">Notes</th>
                                    </tr>
                                </thead>
                            </table>
                        <div id="container" className="col host-overflow">
                            <ul className="list-group bottom-host-ul">
                                {HostConData}
                            </ul>
                        </div>
                    </div>
                <br></br>
                <div className="row">
                    <div className="col-md-4">
                        <h3>Sub Host</h3>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-3">
                        <div className="input-group">
                            <label>Search by Name</label>
                            <input type="text" className="form-control" placeholder="Search for..." onChange={this.onSearchListChange} />
                        </div>
                    </div>
                    <div className="col-md-4">
                        <label className="control-label">Sort By</label> <br />
                        <div className="btn-group" data-toggle="buttons" onClick={this.onSortByChange} value={this.state.value}>
                            <button className="btn btn-default" value="subA">
                                <input  type="radio"/>Sub A-Z
                            </button>
                            <button className="btn btn-default" value="subZ">
                                <input type="radio"/>Sub Z-A
                            </button>
                            <button className="btn btn-default" value="hostA">
                                <input  type="radio"/>Host A-Z
                            </button>
                            <button className="btn btn-default" value="hostZ">
                                <input  type="radio"/>Host Z-A
                            </button>
                        </div>
                    </div>
                    <div className="col-md-3">
                        <label className="control-label">Filter</label> <br />
                        <div className="btn-group" data-toggle="buttons" onClick={this.onShow} value={this.state.value}>
                            <button className="btn btn-default" value="all">
                                <input  type="radio"/>All
                            </button>
                            <button className="btn btn-default" value="condition">
                                <input type="radio"/>Conditions
                            </button>
                        </div>
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
                    <div id="container" className="col host-overflow">
                            <ul className="list-group bottom-host-ul">
                            {HostData}
                        </ul>
                    </div>
            </div>

        );
    }
}
const containerStyle = {
  overflowY: 'scroll', height: 600, scrollbarWidth: 'thin'
}

ReactDOM.render(<AllHostList />, document.getElementById('approve_host'));
