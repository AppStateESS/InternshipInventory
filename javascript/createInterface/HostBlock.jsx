import React from 'react';
import classNames from 'classnames';
import {Button, Modal} from 'react-bootstrap';
import LocationBlock from './LocationBlock.jsx';

// on add internship page
class ModalHostForm extends React.Component {
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

        this.setState({showError: false,
                       warningMsg: ""});
        var host = {id: this.props.id,
                    name: this.refs.host_name.value};

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
    render() {
        // Create red asterisk for a required field
        var require = <span style={{color: '#FB0000'}}> *</span>;
        return (
            <Modal show={this.props.show} onHide={this.handleExit} backdrop='static'>
                <Modal.Header closeButton>
                  <Modal.Title>Request Host</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <form className="form-horizontal">
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Host Name {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-name" ref="host_name" defaultValue={this.props.name}/></div>
                        </div>
                    </form>
                </Modal.Body>
                <Modal.Footer>
                    <Button onClick={this.handleSave}>Request Host</Button>
                    <Button onClick={this.handleExit}>Close</Button>
                </Modal.Footer>
            </Modal>
        );
    }
}
class ModalSubForm extends React.Component {
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

        this.setState({showError: false,
                       warningMsg: ""});
        var host = {id: this.props.id,
                    name: this.refs.host_name.value};

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
                  <Modal.Title>Request Sub</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <form className="form-horizontal">
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Sub Name {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-name" ref="host_name" defaultValue={this.props.name}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Address {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-address" ref="host_address" defaultValue={this.props.address}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">City</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-city" ref="host_city" defaultValue={this.props.city}/></div>
                        </div>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Province (International)</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-province" ref="host_province" defaultValue={this.props.province}/></div>
                        </div>
                        <LocationBlock ref="locationBlock"/>
                        <div className="form-group">
                            <label className="col-lg-3 control-label">Zip/Postal {require}</label>
                            <div className="col-lg-9"><input  type="text" className="form-control" id="host-zip" ref="host_zip" defaultValue={this.props.zip}/></div>
                        </div>
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
            showSubModal: false};
        this.closeHostModal = this.closeHostModal.bind(this);
        this.openHostModal = this.openHostModal.bind(this);
        this.handleSaveHost = this.handleSaveHost.bind(this);
        this.closeSubModal = this.closeSubModal.bind(this);
        this.openSubModal = this.openSubModal.bind(this);
        this.handleSaveSub = this.handleSaveSub.bind(this);
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
        this.closeHostModal(); // Close the modal box
        this.props.handleSave(host); // Call parent's handleSave method
    }
    closeSubModal() {
        this.setState({ showSubModal: false });
    }
    openSubModal() {
        this.setState({ showSubModal: true });
    }
    handleSaveSub(sub){
        this.closeSubModal(); // Close the modal box
        this.props.handleSave(sub); // Call parent's handleSave method
    }
    render() {
        var fgClasses = classNames({
                        'form-group': true,
                        'has-error': this.state.hasError
                    });
        return (
            <div className="row">
                <div className="col-sm-12 col-md-4 col-md-push-3">
                    <div className={fgClasses} id="agency">
                        <label htmlFor="agency2" className="control-label">Host Name </label><button type="button" onClick={this.openHostModal}><i className="fa fa-plus"></i></button>
                        <ModalHostForm show={this.state.showHostModal} hide={this.closeHostModal} edit={true} handleSaveHost={this.handleSaveHost}{...this.props} />
                        <input type="text" id="agency2" name="agency2" className="form-control" placeholder="Acme, Inc." />
                        <label htmlFor="agency3" className="control-label">Sub Name </label><button type="button" onClick={this.openSubModal}><i className="fa fa-plus"></i></button>
                        <ModalSubForm show={this.state.showSubModal} hide={this.closeSubModal} edit={true} handleSaveSub={this.handleSaveSub}{...this.props} />
                        <input type="text" id="agency3" name="agency3" className="form-control" placeholder="AI Boone" />
                    </div>
                </div>
            </div>
        );
    }
}

export default HostAgency;
