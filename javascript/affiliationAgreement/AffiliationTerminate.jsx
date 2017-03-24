import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';


var TerminateButton = React.createClass({
    clicked: function() {
        this.props.clicked();
    },
    render:function() {
        var btnClass;
        var btnText;
        var btnAwesome;

        if(this.props.terminated === 0){
            btnClass = "btn btn-danger pull-right";
            btnText = "Terminate ";
            btnAwesome = "fa fa-times";
        }else{
            btnClass = "btn btn-info pull-right";
            btnText = "Reinstate ";
            btnAwesome = "fa fa-recycle";
        }

        return(
            <div className="terminateButton">
                <a onClick={this.clicked} className={btnClass}>
                    <i className={btnAwesome}></i> {btnText}
                </a>
            </div>
        );
    }
});


var TerminateBox = React.createClass({
    getInitialState: function() {
        return {agreement: null};
    },
    componentWillMount: function(){
        this.getData();
    },
    getData: function(){
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateRest&affiliation_agreement_id='+this.props.affiliationId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({agreement: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    clicked: function(){
        $.ajax({
            url:'index.php?module=intern&action=AffiliateRest&affiliation_agreement_id='+this.props.affiliationId,
            type: 'POST',
            success:function(){
                this.getData();
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    render: function() {

        if(this.state.agreement == null){
            return (<div></div>);
        }

        return(
            <div>
                <TerminateButton clicked={this.clicked} terminated={this.state.agreement.terminated} />
            </div>
        );
    }
});


ReactDOM.render(<TerminateBox affiliationId={window.aaId}/>,
    document.getElementById('terminate')
);
