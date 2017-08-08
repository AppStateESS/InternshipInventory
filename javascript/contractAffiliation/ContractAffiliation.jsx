import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import Dropzone from 'react-dropzone';
import $ from 'jquery';
import classNames from 'classnames';

// Checks to see if the affiliation agreement has expired
var AffiliationList = React.createClass({
    render: function() {
        var optionSelect = null;
        // Date format in jsx is in milliseconds, date saved to agreement is in seconds
        var date = Math.round(new Date().getTime()/1000);
        if(date > this.props.end && this.props.arenew !== 1){
            optionSelect = 	<option value={this.props.id} disabled>{this.props.name} *Expired*</option>
        } else if(date < this.props.start){
            var startD = new Date(this.props.start*1000).toLocaleDateString();
            optionSelect = 	<option value={this.props.id}>{this.props.name} *Starts {startD}*</option>
        } else {
            optionSelect = 	<option value={this.props.id}>{this.props.name}</option>
        }
        return (optionSelect);
    }
});

var AffiliationSelected = React.createClass({
    getInitialState: function() {
        return {showContract: false,
            showAffil: false,
            affilData: null,
            dropData: "",
            selected: this.props.selected};
    },
    componentWillMount: function(){
        this.getData();
    },
    handleDrop: function(e) {
        // saves the users selected affiliation
        if(e.target.value !== -1){
            var choose = e.target.value;
            $.ajax({
                url: 'index.php?module=intern&action=agreementType&affilId='+choose+'&internId='+window.internshipId,
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
    },
    getData: function(){
        // Grabs the list of affiliations
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
    },
    render: function() {
        var aData = null;
        if (this.state.affilData !== null) {
			aData = this.state.affilData.map(function (data) {
			return (
					<AffiliationList key={data.id}
						name={data.name}
                        id={data.id}
                        end={data.end_date}
                        start={data.begin_date}
                        arenew={data.auto_renew}/>
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
        );
    }
});
class ContractSelected extends Component{
    constructor(props) {
        super(props)
        this.state = {showDrop: true,
            currentFiles: []}
            this.addFiles = this.addFiles.bind(this);
        }
        componentDidMount(){
            $.ajax({
                url: 'index.php?module=intern&action=documentRest&type=contract&internship_id=' + this.props.internshipId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if(data !== null){
                        this.setState({currentFiles: data, showDrop: false});
                    }
                }.bind(this)
            });
        }
        addFiles(files){
            let currentfiles = [];
            let add = {'name':'','newName':''};
            $.each(files, function (key, value) {
                let formData = new FormData()
                formData.append('file', value);
                let data = value;
                $.ajax({
                    url: 'index.php?module=intern&action=documentRest&type=contract&internship_id=' + this.props.internshipId,
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    data: formData,
                    cache: false,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (stat) {
                        currentfiles = this.state.currentFiles
                        if (stat.name !== null) {
                            add['name'] = data.name;
                            add['store_name'] = stat.name;
                            currentfiles.push(add);
                        } else {
                            alert(stat)
                            return
                        }
                        this.setState({currentFiles: currentfiles})
                    }.bind(this),
                    error: function(xhr, status, err) {
                        alert("File failed to save.")
                        console.error(this.props.url, status, err.toString());
                    }.bind(this)
                })
            }.bind(this))
        }
        download(file){
            $.ajax({
                url: 'index.php?module=intern&action=documentRest&type=contract&name='+file.store_name+'&internship_id=' + this.props.internshipId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    window.open(data);
                },
                error: function(xhr, status, err) {
                    alert("Unable to download file.")
                    console.error(this.props.url, status, err.toString());
                }.bind(this)
            })
        }
        deleteFile(file) {
            // Find key of file in currentFiles
            let key;
            for(let i=0; i<this.state.currentFiles.length;i++){
                if(file.name === this.state.currentFiles[i]){
                    key = i;
                    break;
                }
            }
            $.ajax({
                url: 'index.php?module=intern&action=documentRest&type=contract&name='+file.store_name+'&internship_id=' + this.props.internshipId,
                method: 'DELETE',
                success: function (data) {
                    let files = this.state.currentFiles
                    files.splice(key, 1)
                    this.setState({currentFiles: files, showDrop: true})
                }.bind(this),
                error: function(xhr, status, err) {
                    alert("Unable to delete file.")
                    console.error(this.props.url, status, err.toString());
                }.bind(this)
            })
        }
        render(){
            let list
            if (this.state.currentFiles.length > 0) {
                list = this.state.currentFiles.map(f =>
                    <li className="list-group-item"><i className="fa fa-file"></i> <a onClick={this.download.bind(this, f)}>{f.name}</a> &nbsp;
                    <button type="button" className="close" onClick={this.deleteFile.bind(this, f)}><span aria-hidden="true"><i className='fa fa-trash-o close'></i></span></button> </li>
                );
            }
            return(
                <section>
                    <div>
                        <div className="dropzone text-center pointer" show={this.state.showDrop}>
                            <Dropzone ref="dropzone" style={{width: 'auto', height: 'auto', border: '2px dashed gray'}} onDrop={this.addFiles}>
                                <div style={{paddingTop: '1%'}}>
                                    <i className="fa fa-file"></i><br/>
                                    <p>Click to browse or drag file(s) here.</p>
                                </div>
                            </Dropzone>
                        </div>
                        <div>
                            <h4>Added Files:</h4>
                            <ul className="list-group">
                                {list}
                            </ul>
                        </div>
                    </div>
                </section>
            )
        }
}

var ContractAffiliation = React.createClass({
    getInitialState: function() {
        return {showContract: true,
            showAffil: false,
            agreementType: null,
            affilSelect: null};
    },
    componentWillMount: function(){
        this.getData();
    },
    getData: function(){
        // Grabs the affiliation data, if none then sets it to contract
        $.ajax({
            url: 'index.php?module=intern&action=agreementType&internId='+this.props.internshipId,
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
    },
    setType: function(type){
        // Sets the type of agreement selected
        $.ajax({
            url: 'index.php?module=intern&action=agreementType&agreeType='+type+'&internId='+this.props.internshipId,
            type: 'PUT',
            success: function() {
                this.setState({agreementType: type});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to save type.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    onAffilationSelected(){
        this.setState({showAffil: true,
                    showContract: false});
        this.setType('affiliation');
    },
    onContractSelect(){
        this.setState({showContract: true,
                    showAffil: false});
        this.setType('contract');
    },
    render: function() {
        var selection;
        if(this.state.showContract && this.state.agreementType !== 'affiliation'){
            selection = <ContractSelected show={this.state.showContract} />
        } else {
            selection = <AffiliationSelected show={this.state.showAffil} selected={this.state.affilSelect} />
        }
        // Class for contract button active
        var contractActive = classNames({
            'btn': true,
            'btn-default': true,
            'active': this.state.showContract && this.state.agreementType !== 'affiliation'
        });
        // Class for affiliation button active
        var affiliationActive = classNames({
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
        );
    }
});

ReactDOM.render(
    <ContractAffiliation internshipId={window.internshipId}/>,
    document.getElementById('contract-affiliation')
);
