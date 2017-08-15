import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import Dropzone from 'react-dropzone';
import $ from 'jquery';
import classNames from 'classnames';

// Checks to see if the affiliation agreement has expired
class AffiliationList extends Component{
    render() {
        let optionSelect = null;
        // Date format in jsx is in milliseconds, date saved to agreement is in seconds
        let date = Math.round(new Date().getTime()/1000);
        // Checks for terminated first since it could also be expired, but knowing if it was terminated is more important
        if(this.props.terminate){
            optionSelect = 	<option value={this.props.id} disabled>{this.props.name} *Terminated*</option>
        } else if(date > this.props.end && this.props.arenew !== 1){
            optionSelect = 	<option value={this.props.id} disabled>{this.props.name} *Expired*</option>
        } else if(date < this.props.start){
            let startD = new Date(this.props.start*1000).toLocaleDateString();
            optionSelect = 	<option value={this.props.id}>{this.props.name} *Starts {startD}*</option>
        } else {
            optionSelect = 	<option value={this.props.id}>{this.props.name}</option>
        }
        return (optionSelect);
    }
}

class AffiliationSelected extends Component{
    constructor(props) {
        super(props)
        this.state = {showContract: false,
            showAffil: false,
            affilData: null,
            selected: this.props.selected}
        this.handleDrop = this.handleDrop.bind(this);
        this.getData = this.getData.bind(this);
    }
    componentDidMount(){
        this.getData();
    }
    handleDrop(e) {
        // saves the users selected affiliation
        let choose = e.target.value;
        $.ajax({
            url: 'index.php?module=intern&action=agreementType&affilId='+choose+'&internId='+ this.props.internshipId,
            type:'PUT',
            success: function() {
                this.setState({selected: choose});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to save affiliation data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    getData(){
        // Grabs the list of affiliations asc by name
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateListRest&NameASC=yes',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                data.unshift({name: "Select an agreement", id: "-1"});
                this.setState({affilData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to load affiliation data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    render() {
        let aData = null;
        if (this.state.affilData !== null) {
			aData = this.state.affilData.map(function (data) {
			return (
					<AffiliationList key={data.id}
						name={data.name}
                        id={data.id}
                        end={data.end_date}
                        start={data.begin_date}
                        arenew={data.auto_renew}
                        terminate={data.terminated}/>
				);
			});
		} else {
			aData = "";
		}
        return (
            <div>
                <label>Affiliation Agreements:</label>
                <select className="form-control" onChange={this.handleDrop} value={this.state.selected}>
                    {aData}
                </select>
            </div>
        )
    }
}

class ContractSelected extends Component{
    constructor(props) {
        super(props)
        this.state = {show: false,
            currentFiles: []}
        this.addFiles = this.addFiles.bind(this);
        this.componentDidMount = this.componentDidMount.bind(this);
        this.deleteFile = this.deleteFile.bind(this);
    }
    componentDidMount(){
        $.ajax({
            url: 'index.php?module=intern&action=documentRest&type=contract&internship_id=' + this.props.internshipId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                this.setState({currentFiles: data});
            }.bind(this)
        });
    }
    addFiles(files){
        let currentfiles = [];
        // Though there are not multiple files, data has to be sent as FormData
        $.each(files, function (key, value) {
            let formData = new FormData()
            formData.append(key, value);
            $.ajax({
                url: 'index.php?module=intern&action=documentRest&type=contract&key='+key+'&internship_id=' + this.props.internshipId,
                type: 'POST',
                enctype: 'multipart/form-data',
                data: formData,
                cache: false,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (stat) {
                    currentfiles = this.state.currentFiles
                    if (stat.message === "") {
                        currentfiles.push(stat);
                    } else {
                        alert(stat.message)
                        return
                    }
                    this.setState({currentFiles: currentfiles})
                }.bind(this),
                error: function(xhr, status, err) {
                    alert("Contract failed to save.")
                    console.error(this.props.url, status, err.toString());
                }.bind(this)
            })
        }.bind(this))
    }
    deleteFile(file) {
        $.ajax({
            url: 'index.php?module=intern&action=documentRest&type=contract&docId='+file.id+'&internship_id=' + this.props.internshipId,
            method: 'DELETE',
            success: function (data) {
                let files = this.state.currentFiles
                files.splice(0, 1)
                this.setState({currentFiles: files})
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Unable to delete contract. Contract does not exist.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        })
    }
    render(){
        let list
        let DropZone = <div></div>
        // If there is a file uploaded it shows the file, else it shows dropzone so you can upload one
        if (this.state.currentFiles.length > 0) {
            list = this.state.currentFiles.map(function(f){
                let url = "index.php?module=intern&action=documentRest&type=contract&docId="+f.id+"&internship_id="+this.props.internshipId;
                return(<li className="list-group-item" key={f.id}><i className="fa fa-file"></i> <a href={url} >{f.name}</a> &nbsp;
                <button type="button" className="close" onClick={this.deleteFile.bind(this, f)}><span aria-hidden="true"><i className='fa fa-trash-o'></i></span></button> </li>
            )}.bind(this));
        } else{
            DropZone = <div className="dropzone text-center pointer">
                <Dropzone multiple={false} ref="dropzone" style={{width: 'auto', height: 'auto', border: '2px dashed gray'}} onDrop={this.addFiles}>
                    <div style={{paddingTop: '1%'}}>
                        <i className="fa fa-file"></i><br/>
                        <p>Click to browse or drag contract here.</p>
                    </div>
                </Dropzone>
            </div>
        }
        return(
            <section>
                <div>
                    {DropZone}
                    <div>
                        <label>Added Contract:</label>
                        <ul className="list-group">
                            {list}
                        </ul>
                    </div>
                </div>
            </section>
        )
    }
}

class ContractAffiliation extends Component{
    constructor(props) {
        super(props)
        this.state = {showContract: true,
            showAffil: false,
            agreementType: null,
            affilSelect: null};
        this.getData = this.getData.bind(this);
        this.setType = this.setType.bind(this);
        this.onAffilationSelected = this.onAffilationSelected.bind(this);
        this.onContractSelect = this.onContractSelect.bind(this);
    }
    componentDidMount(){
        this.getData();
    }
    getData(){
        // Grabs the affiliation data, if none then sets it to contract
        $.ajax({
            url: 'index.php?module=intern&action=agreementType&internId='+ this.props.internshipId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({agreementType: data[0].contract_type, affilSelect: data[0].affiliation_agreement_id});
                if(data[0].contract_type == null){
                    this.setType('contract', null);
                }
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to load type information.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    setType(type){
        // Sets the type of agreement selected
        $.ajax({
            url: 'index.php?module=intern&action=agreementType&agreeType='+ type +'&internId='+ this.props.internshipId,
            type: 'PUT',
            success: function() {
                this.setState({agreementType: type});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to save type.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    onAffilationSelected(){
        this.setState({showAffil: true,
                    showContract: false});
        this.setType('affiliation');
    }
    onContractSelect(){
        this.setState({showContract: true,
                    showAffil: false});
        this.setType('contract');
    }
    render() {
        let selection;
        // Preselects the contract button for new internships
        if(this.state.showContract && this.state.agreementType !== 'affiliation'){
            selection = <ContractSelected show={this.state.showContract} internshipId={this.props.internshipId}/>
        } else {
            selection = <AffiliationSelected show={this.state.showAffil} selected={this.state.affilSelect} internshipId={this.props.internshipId}/>
        }
        // Class for contract button active
        let contractActive = classNames({
            'btn': true,
            'btn-default': true,
            'active': this.state.showContract && this.state.agreementType !== 'affiliation'
        });
        // Class for affiliation button active
        let affiliationActive = classNames({
            'btn': true,
            'btn-default': true,
            'active': this.state.showContract && this.state.agreementType === 'affiliation'
        });
        return (
            <div>
                <div className="row">
                    <div className="col-lg-6">
                        <div className="btn-group" data-toggle="buttons" role="group">
                            <label className={contractActive} onClick={this.onContractSelect}>
                                <input type="radio" name="option" /> Contract
                            </label>
                            <label className={affiliationActive} onClick={this.onAffilationSelected}>
                                <input type="radio" name="option" /> Affiliation Agreement
                            </label>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="col-lg-12">
                    <br />{selection}<br />
                    </div>
                </div>
            </div>
        )
    }
}

ReactDOM.render(
    <ContractAffiliation internshipId={window.internshipId}/>,
    document.getElementById('contract-affiliation')
);
