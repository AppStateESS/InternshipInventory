import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';


class TerminateButton extends React.Component {
    constructor(props){
        super(props);

        this.clicked = this.clicked.bind(this);
    }
    clicked() {
        this.props.clicked();
    }
    render() {
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
}


class TerminateBox extends React.Component {
    constructor(props) {
      super(props);
      this.state = {agreement: null};

      this.getData = this.getData.bind(this);
      this.clicked = this.clicked.bind(this);
    }
    componentWillMount(){
        this.getData();
    }
    getData(){
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
    }
    clicked(){
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
    }
    render() {

        if(this.state.agreement == null){
            return (<div></div>);
        }

        return(
            <div>
                <TerminateButton clicked={this.clicked} terminated={this.state.agreement.terminated} />
            </div>
        );
    }
}


ReactDOM.render(<TerminateBox affiliationId={window.aaId}/>,
    document.getElementById('terminate')
);
