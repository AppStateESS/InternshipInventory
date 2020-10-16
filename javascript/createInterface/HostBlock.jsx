import React from 'react';
import classNames from 'classnames';
import {Button, Modal} from 'react-bootstrap';
import LocationBlock from './LocationBlock.jsx';
import Message from '../emergencyContact/Message.jsx';
import $ from 'jquery';

// on add internship page
class ModalHostForm extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            availableHost: this.props.availableData,
            hostDisplayData: null,
            searchName: null,
            showError: false,
            warningMsg: ''
        };

        this.handleSave = this.handleSave.bind(this);
        this.handleExit = this.handleExit.bind(this);
        this.onSearchListChange = this.onSearchListChange.bind(this);
        this.updateDisplayData = this.updateDisplayData.bind(this);
    }
    handleSave() {
        if (this.refs.host_name.value === '' || this.refs.host_name.value === undefined) {
            this.setState({showError: true, warningMsg: "Please enter a name of host."});
            return;
        }

        this.setState({showError: false,
                       warningMsg: ""});
        var host = {name: this.refs.host_name.value};

        // Call parent's save handler
        this.props.handleSaveHost(host);
    }
    handleExit(){
        //resets state so any warnings previously are reset.
        this.setState({
            showError: false,
        })
        this.props.hide();
    }
    searchListByName(data, nameToSearch) {
      var filtered = [];
      // Looks for the name by filtering the mainData
      for (var i = 0; i < data.length; i++) {
          var item = data[i];
          // Make the item, name lowercase for easier searching
          if (item.host_name.toLowerCase().includes(nameToSearch)) {
              filtered.push(item);
          }
      }
      return filtered;
    }
    updateDisplayData(typedName, data) {
        var filtered = [];

        // Second searches list for name.
        if (typedName !== null) {
            filtered = this.searchListByName(data, typedName);
        }

        this.setState({hostDisplayData: filtered});

    }
    onSearchListChange(e) {
        var name = null;
        name = e.target.value.toLowerCase();
        this.setState({searchName: name});
        if(name === ''){this.state.hostDisplayData = null;}
        else if(this.state.availableHost!=null){this.updateDisplayData(name, this.state.availableHost);}
    }
    render() {
        // Create red asterisk for a required field
        var require = <span style={{color: '#FB0000'}}> *</span>;
        var HostData = null;
        if (this.state.hostDisplayData != null) {
            HostData = this.state.hostDisplayData.map(function (host) {
                return (
                    <p>{host.host_name}</p>
                );
            }.bind(this));
        }
        return (
            <Modal show={this.props.show} onHide={this.handleExit} backdrop='static'>
                <Modal.Header closeButton>
                  <Modal.Title>Request Host</Modal.Title>
                  {this.state.showError ? <Message type="warning" children={this.state.warningMsg}></Message> : null}
                </Modal.Header>
                <Modal.Body>
                    <form className="form-horizontal">
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Host Name {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-name" ref="host_name" defaultValue={this.props.name} onChange={this.onSearchListChange}/></div>
                        </div>
                    </form>
                    <label className="col-lg-6 control-label">Suggested Host Previously Added:</label>
                    <div id="container" className="col host-add-overflow">
                            <ul className="list-group bottom-host-ul">
                            {HostData}
                        </ul>
                    </div>
                <p></p>
                </Modal.Body>
                <Modal.Footer>
                    <Button onClick={this.handleSave}>Request Host</Button>
                    <Button onClick={this.handleExit}>Close</Button>
                </Modal.Footer>
            </Modal>
        );
    }
}
const containerStyle = {
  overflowY: 'scroll', height: 100, scrollbarWidth: 'thin'
}
class ModalSubForm extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            showError: false,
            warningMsg: '',
            domestic: undefined,
            international: undefined,
            location: undefined,
            availableHost: this.props.availableData
        };

        this.handleSave = this.handleSave.bind(this);
        this.handleExit = this.handleExit.bind(this);
    }
    handleSave() {
        if (this.refs.sub_address.value === ''||this.refs.sub_zip.value === ''||this.state.location === undefined) {
            this.setState({showError: true, warningMsg: "Please enter sub host address information."});
            return;
        }
        if (this.refs.host_name.value === '-1'||this.refs.sub_name.value === '') {
            this.setState({showError: true, warningMsg: "Please enter host names."});
            return;
        }
        if ((this.state.domestic === true&&this.refs.sub_city.value === '')||(this.state.domestic === false&&this.refs.sub_province.value === ''&&this.refs.sub_city.value === '')) {
            this.setState({showError: true, warningMsg: "Please enter host names."});
            return;
        }

        this.setState({showError: false,
                       warningMsg: ""});
        var host = null
        if(this.state.domestic === true){
            host = {host: this.refs.host_name.value, name: this.refs.sub_name.value, address: this.refs.sub_address.value,
                    zip: this.refs.sub_zip.value,city: this.refs.sub_city.value, province: null, state: this.state.location,
                    country: 'US', other: null}
        } else{
            host = {host: this.refs.host_name.value, name: this.refs.sub_name.value, address: this.refs.sub_address.value,
                    zip: this.refs.sub_zip.value,city: this.refs.sub_city.value, province: this.refs.sub_province.value, state: null,
                    country: this.state.location, other: null}
        }

        // Call parent's save handler
        this.props.handleSaveSub(host);
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
        var loc = null;
        if(this.state.domestic === true){
            loc = (<div className="form-group">
                <label className="col-lg-3 control-label">City {require}</label>
                <div className="col-lg-9"><input  type="text" className="form-control" id="sub-city" ref="sub_city"/></div>
            </div>);
        } else if (this.state.domestic === false) {
            loc = (<div>
                <div className="form-group">
                  <label className="col-lg-3 control-label">City {require}</label>
                  <div className="col-lg-9"><input  type="text" className="form-control" id="sub-city" ref="sub_city"/></div>
                </div>
                <div className="form-group">
                  <label className="col-lg-3 control-label">Province {require}</label>
                  <div className="col-lg-9"><input  type="text" className="form-control" id="sub-province" ref="sub_province"/></div>
                </div>
            </div>);
        }
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
        return (
            <Modal show={this.props.show} onHide={this.handleExit} backdrop='static'>
                <Modal.Header closeButton>
                  <Modal.Title>Request Sub Host</Modal.Title>
                  {this.state.showError ? <Message type="warning" children={this.state.warningMsg}></Message> : null}
                </Modal.Header>
                <Modal.Body>
                    <form className="form-horizontal">
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Host Name {require}</label>
                            <select className="form-control col-lg-9 select-sub-host" id="host-name" ref="host_name">
                                <option value="-1">Select a Host</option>
                                {availHost}
                            </select>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Sub Name {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="sub-name" ref="sub_name"/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Address {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="sub-address" ref="sub_address"/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Zip/Postal {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="sub-zip" ref="sub_zip"/></div>
                        </div>
                        <LocationBlock domestic={this.state.domestic} international={this.state.international} setDom={(dom) => this.setState({domestic:dom})} setInt={(inta) => this.setState({international: inta})}  setLoc={(loc) => this.setState({location: loc})} ref="locationBlock"/>
                        {loc}
                    </form>
                </Modal.Body>
                <Modal.Footer>
                    <Button onClick={this.handleSave}>Request Sub</Button>
                    <Button onClick={this.handleExit}>Close</Button>
                </Modal.Footer>
            </Modal>
        );
    }
}

class HostAgency extends React.Component {
    constructor(props) {
        super(props);
        this.state = {hasError: false,
            showHostModal: false,
            showSubModal: false,
            availableHost: null,
            availableSub: null,
            hostSelect: null};
        this.getHostSelect = this.getHostSelect.bind(this)
        this.getSubData = this.getSubData.bind(this);
        this.closeHostModal = this.closeHostModal.bind(this);
        this.openHostModal = this.openHostModal.bind(this);
        this.handleSaveHost = this.handleSaveHost.bind(this);
        this.closeSubModal = this.closeSubModal.bind(this);
        this.openSubModal = this.openSubModal.bind(this);
        this.handleSaveSub = this.handleSaveSub.bind(this);
    }
    componentDidMount() {
        // Fetch list of all host
        $.ajax({
            url: 'index.php?module=intern&action=HostRest&Waiting=true',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({availableHost: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });
    }
    getHostSelect(e){
        //holds main host selection for getting the sub host
        this.setState({hostSelect: e.target.value}, this.getSubData)
    }
    getSubData(){
        // Fetch list of available sub by location and main host
        if(this.props.domestic !== undefined && this.props.location !== undefined){
            $.ajax({
                url: 'index.php?module=intern&action=SubRest&domestic=' + this.props.domestic + '&location=' + this.props.location + '&main=' + this.state.hostSelect,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.unshift({sub_name: "Select a Sub Name", id: "-1"});
                    this.setState({availableSub: data});
                }.bind(this),
                error: function(xhr, status, err) {
                    console.error(status, err.toString());
                }
            });
        }
    }
    setError(status){
        this.setState({hasError: status});
    }
    closeHostModal() {
        this.setState({ showHostModal: false });
    }
    openHostModal() {
        this.setState({ showHostModal: true });
    }
    handleSaveHost(host){
        $.ajax({
            url: 'index.php?module=intern&action=HostRest',
            type: 'POST',
            dataType: 'json',
            data: {name: host.name},
            success: function(data) {
                this.setState({availableHost:data});
                this.closeHostModal(); // Close the modal box
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to add Host to database properly.");
                console.error(status, err.toString());
            }
        });
    }
    closeSubModal() {
        this.setState({ showSubModal: false });
    }
    openSubModal() {
        this.setState({ showSubModal: true });
    }
    handleSaveSub(sub){
        $.ajax({
            url: 'index.php?module=intern&action=SubRest',
            type: 'POST',
            dataType: 'json',
            data: {main: sub.host,
                   name: sub.name,
                   address: sub.address,
                   city: sub.city,
                   state: sub.state,
                   zip: sub.zip,
                   province: sub.province,
                   country: sub.country,
                   other_name: sub.other
               },
            success: function() {
                this.getSubData();
                this.closeSubModal(); // Close the modal box
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to add Sub Host to database properly.")
                console.error(status, err.toString());
            }
        });

    }
    render() {
        var fgClasses = classNames({'form-group': true, 'has-error': this.state.hasError});

        var availHostOptions = null;
        if (this.state.availableHost != null) {
            availHostOptions = this.state.availableHost.map(function (avail) {
            return (
                    <option key={avail.id} value={avail.id}>{avail.host_name}</option>
                );
            });
        } else {
            availHostOptions = "";
        }

        var locationDropDown;
        if(this.state.availableSub === null) {
            locationDropDown = <option value="-1">Select a Location and Host First</option>;
        } else if(this.state.availableSub.length === 0){
            locationDropDown = <option value="-1">No Sub Name With That Location and Host</option>;
        } else {
    		locationDropDown = this.state.availableSub.map(function (availS) {
    		return (
    				<option key={availS.id} value={availS.id}>{availS.sub_name}</option>
    			);
    		});
    	}
        return (
            <div className="row">
                <div className="row">
                    <div className="col-sm-12 col-md-4 col-md-push-3 form-align">
                        <div className={fgClasses} id="agency">
                            <label htmlFor="agency2" className="control-label">Host Name </label>
                            <button type="button" id="small-button1" title="Click here to add a host" onClick={this.openHostModal}><i className="fa fa-plus fa-xs"></i></button>
                            <ModalHostForm show={this.state.showHostModal} availableData={this.state.availableHost} key={this.state.availableHost} hide={this.closeHostModal} edit={true} handleSaveHost={this.handleSaveHost}{...this.props} />
                            <select id="main_host" name="main_host" ref="host_selection" className="form-control" onChange={this.getHostSelect}>
                                <option value="-1">Select a Host</option>
                                {availHostOptions}
                            </select>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="col-sm-12 col-md-4 col-md-push-3 form-align">
                        <div className={fgClasses} id="agency">
                            <label htmlFor="agency3" className="control-label">Sub Name </label>
                            <button type="button" id="small-button2" title="Click here to add sub host" onClick={this.openSubModal}><i className="fa fa-plus fa-xs"></i></button>
                            <ModalSubForm show={this.state.showSubModal} availableData={this.state.availableHost} key={this.state.availableHost} hide={this.closeSubModal} edit={true} handleSaveSub={this.handleSaveSub}{...this.props} />
                            <select id="sub_host" name="sub_host" className="form-control">
                                {locationDropDown}
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default HostAgency;
