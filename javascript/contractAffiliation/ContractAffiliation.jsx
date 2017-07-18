import React from 'react';
import ReactDOM from 'react-dom';
import Dropzone from 'react-dropzone';
import $ from 'jquery';
import {Button} from 'react-bootstrap';


var AffiliationSelected = React.createClass({
    render: function() {
        return (
            <div>
            </div>
        );
    }
});
var ContractSelected = React.createClass({
    handleClick(){
        
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
    render: function() {
        var doc;
        var docSrc = null;
        var docName;
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
            <div className="row">
                <Dropzone ref="dropzone" onDrop={this.onDrop} className="dropzone text-center">
                    {doc}
                </Dropzone>
            </div>
            <div className="row">
                <div class="col-lg-9">
                  <ul class="list-group">
                    <!-- BEGIN docs -->
                    <li class="list-group-item"><i class="fa fa-file"></i> {DOWNLOAD} &nbsp;{DELETE}</li>
                    <!-- END docs -->
                  </ul>
                </div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-default btn-sm" onClick={this.handleClick}><i class="fa fa-upload"></i>dgettext('filecabinet', 'Add document')</button>
                </div>
            </div>
        );
    }
});
var ContractAffiliation = React.createClass({
    render: function() {
        return (
            <div>
                <div className="row">
                    <div className="col-lg-12 col-lg-offset-9">
                        <div class="btn-group" data-toggle="buttons" className="form-group">
                            <label class="btn btn-primary active">
                                <input type="radio" name="options" id="contractSelect" autocomplete="off" checked> Contract</input>
                            </label>
                            <label class="btn btn-primary">
                                <input type="radio" name="options" id="affilSelect" autocomplete="off"> Affiliation Agreement</input>
                            </label>
                        </div>
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
