import React from 'react';
import ReactDOM from 'react-dom';
import Dropzone from 'react-dropzone';
import $ from 'jquery';

var AffiliationList = React.createClass({
    render: function() {
        var optionSelect = null;
        var date = Math.round(new Date().getTime()/1000);
        console.log(date, this.props.end_date);
        if(date > this.props.end_date){
            optionSelect = 	<option value={this.props.id} disabled>{this.props.name} Expried</option>
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
            dropData: ""};
    },
    componentWillMount: function(){
        this.getData();
    },
    handleDrop: function(e) {
        if(e.target.value !== -1){
            console.log("hit", "Hit");
            $.ajax({
                url: 'index.php?module=intern&action=SaveInternship&internshipId='+this.props.internshipId+'&contract_type=affiliation&aff_agre_id='+this.state.dropData.id,
                type:'PUT',
                dataType: 'json',
                success: function(data) {

                }.bind(this),
                error: function(xhr, status, err) {
                    alert("Failed to save affiliation data.")
                    console.error(this.props.url, status, err.toString());
                }.bind(this)
            });
        }
    },
    getData: function(){
        // Grabs the affiliation data
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateListRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log(data[0]);
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
            console.log(this.state.affilData[0])
			aData = this.state.affilData.map(function (data) {
			return (
					<AffiliationList key={data.id}
						name={data.name}
						begin={data.begin_date}
                        end={data.end_date}/>
				);
			});
		} else {
			aData = "";
		}
        return (
            <div>
                <label>Affiliation Agreements:</label>
                <select className="form-control" onChange={this.handleDrop}>
                    {aData}
                </select>
            </div>
        );
    }
});

var ContractSelected = React.createClass({
    getDefaultProps: function(){
        return{showContract: false,
            showAffil: false,
            doc: []}
    },
    onDrop: function(doc){
        this.props.update(doc);
    },
    onOpenClick: function(){
        this.refs.dropzone.open();
    },
    onSave: function(){
        //this.setType('contract');
    },
    render: function() {
        var doc;
        if(this.props.doc.length > 0){
            doc = this.props.files.map(f => <li>{f.name}</li>)
        } else {
            doc = (
                <div className="clickme">
                    <i className="fa fa-file"></i>
                    <p>Click or drag contract here.</p>
                </div>
            );
        }
        return (
            <div>
                <div className="dropzone text-center pointer">
                    <Dropzone style={{width: 'auto', height: 'auto', border: '2px dashed gray'}} ref="dropzone" accept="file/pdf, file/doc, file/odt" onDropAccepted={this.onDrop}>
                    {doc}
                </Dropzone>
                </div>
            </div>
        );
    }
});

var ContractAffiliation = React.createClass({
    getInitialState: function() {
        return {showContract: true,
            showAffil: false};
    },
    setType: function(type, id){
        //send ajax to set type & id
        if(id === null){
            //contract type set
        }else{
            //affiliation type set
        }
    },
    onAffilationSelected(){
        this.setState({showAffil: true,
                    showContract: false});
    },
    onContractSelect(){
        this.setState({showContract: true,
                    showAffil: false});
    },
    render: function() {
        var selection;
        if(this.state.showContract){
            selection = <ContractSelected show={this.state.showContract} />
        } else {
            selection = <AffiliationSelected show={this.state.showAffil} />
        }
        return (
            <div>
                <div className="row">
                    <div className="col-lg-6">
                        <div className="btn-group" data-toggle="buttons" role="group">
                            <label className="btn btn-default active" onClick={this.onContractSelect}>
                                <input type="radio" name="option" /> Contract
                            </label>
                            <label className="btn btn-default" onClick={this.onAffilationSelected}>
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
