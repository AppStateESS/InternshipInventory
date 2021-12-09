import React from 'react';
import ReactDOM from 'react-dom';
import classNames from 'classnames';
import {Button, Modal} from 'react-bootstrap';
import $ from 'jquery';
import '../custom.css'

class LocationBlock extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            availableHost: null,
            availableSub: null,
            hostSelect: null,
            subSelect: null,
            domestic: null};
        this.getHostSelect = this.getHostSelect.bind(this)
        this.getRecordData = this.getRecordData.bind(this);
        this.getSubData = this.getSubData.bind(this);
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
        this.getRecordData()
    }
    getHostSelect(e){
        //holds main host selection for getting the sub host
        this.setState({hostSelect: e.target.value}, this.getSubData)
    }
    async getRecordData(){
        let hostid
        // Fetch host and sub for record
        await $.ajax({
            url: 'index.php?module=intern&action=SubRest&internId=' + this.props.internshipId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                hostid=data[0].host_id
                this.setState({hostSelect: data[0].host_id, subSelect: data[0].host_sub_id});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });
        $.ajax({
            url: 'index.php?module=intern&action=SubRest&change=' + hostid,
            type: 'GET',
            dataType: 'json',
            success: function(datas) {
                this.setState({availableSub: datas});
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('e')
                console.error(status, err.toString());
            }
        });
    }
    getSubData(){
        // Fetch list of available sub by main host
        $.ajax({
            url: 'index.php?module=intern&action=SubRest&change=' + this.state.hostSelect,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({availableSub: data});
            }.bind(this),
            error: function(xhr, status, err) {
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
        if (this.state.availableSub != null) {
            locationDropDown = this.state.availableSub.map(function (availS) {
                return (
                    <option key={availS.id} value={availS.id}>{availS.sub_name}</option>
                );
            });
        } else {
            locationDropDown = "";
        }

        return (
            <div className="row">
                <div className="form-group">
                    <label htmlFor="agency2" className="col-lg-3 control-label">Host Name </label>
                    <div className="col-lg-6"><select id="main_host" name="main_host" ref="host_selection" className="form-control" onChange={this.getHostSelect} value={this.state.hostSelect}>
                        {availHostOptions}
                    </select></div>
                </div>
                <div className="form-group">
                    <label htmlFor="agency3" className="col-lg-3 control-label">Sub Name </label>
                    <div className="col-lg-6"><select id="sub_host" name="sub_host" className="form-control" value={this.state.subSelect}>
                        {locationDropDown}
                    </select></div>
                </div>
            </div>
        );
    }
}

ReactDOM.render(
    <LocationBlock internshipId={window.internshipId}/>,
    document.getElementById('sub-list')
);
