import React from 'react';
import ReactDOM from 'react-dom';
import Dropzone from 'react-dropzone';
import $ from 'jquery';

var AffiliationList = React.createClass({
  render: function() {
    return (
     	<option value={this.props.id}>{this.props.name}</option>
    )
  }
});

var AffiliationSelected = React.createClass({
    getInitialState: function() {
        return {showContract: false,
            showAffil: false,
            affilData: null,
            dropData: ""};
    },
    onAffilationSelected(){
        this.setState({showAffil: true});
    },
    componentWillMount: function(){
        this.getData();
    },
    handleDrop: function(e) {

        if(e.target.value !== -1){
            this.setState({dropData: this.state.dropData});
            console.log(this.state.dropData, "hit");
            $.ajax({
                url: 'index.php?module=intern&action=SaveInternship&internshipId='+this.props.internshipId+'&contract_type=affiliation&aff_agre_id='+this.state.dropData.id,
                dataType: 'json',
                success: function(data) {
                    this.getData();
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
            url: 'index.php?module=intern&action=AffiliateListRest&internshipId='+this.props.internshipId,
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
			aData = this.state.affilData.map(function (affilData) {
			return (
					<AffiliationList key={affilData.id}
						name={affilData.name}
						begin={affilData.begin_date}
                        end={affilData.end_date}/>
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

/*var ContractSelected = React.createClass({
    getInitialState: function() {
        return {showContract: false,
            showAffil: false};
    },
    onContractSelect(){
        this.setState({showContract: true});
    },
    getDefaultProps: function(){
        return{doc: []}
    },
    onDrop: function(doc){
        this.props.update(doc);
    },
    onOpenClick: function(){
        this.refs.dropzone.open();
    },
    onSave: function(){
        this.setType('contract');
    },
    render: function() {
        var doc;
        if(this.props.doc.length > 0){

        } else {
            doc = (
                <div className="clickme">
                    <i class="fa fa-file"></i><br/>
                    <p>Click or drag document here.</p>
                </div>
            );
        }
        return (
            <div>
                <div className="row">
                    <Dropzone ref="dropzone" onDrop={this.onDrop} className="dropzone text-center">
                        {doc}
                    </Dropzone>
                </div>
            </div>
        );
    }
});*/

var ContractAffiliation = React.createClass({
    getInitialState: function() {
        return {showContract: false,
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
    render: function() {
        return (
            <div className="row">
                <div className="col-lg-6">
                    <div class="btn-group" data-toggle="buttons" role="group">
                        <label class="btn btn-primary active">
                            <input type="radio" name="option" autocomplete="off" checked onChange={this.onContractSelect}> Contract</input>
                        </label>
                        <label class="btn btn-primary">
                            <input type="radio" name="option" autocomplete="off" onChange={this.onAffilationSelected}> Affiliation Agreement</input>
                        </label>
                    </div>
                </div>
            </div>
        );
    }
});
//<ContractSelected show={this.state.showContract} />
//<AffiliationSelected show={this.state.showAffil} />

var Test = React.createClass({
    render: function() {
        return (
            <div>
                <p>Test Contract</p>
            </div>
        );
    }
});

ReactDOM.render(
    <AffiliationSelected internshipId={window.internshipId}/>,
    document.getElementById('contract-affiliation')
);
