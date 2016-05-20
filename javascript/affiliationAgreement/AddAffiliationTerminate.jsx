
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


var TerminateButton = React.createClass({
    clicked: function() {
        this.props.clicked();
    },
    render:function() {
        if(this.props.terminated == 0){
            var btnClass = "btn btn-danger pull-right";
            var btnText = "Terminate ";
            var btnAwesome = "fa fa-times";
        }else{
            var btnClass = "btn btn-info pull-right";
            var btnText = "Reinstate ";
            var btnAwesome = "fa fa-recycle";
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

React.render(<TerminateBox affiliationId={aaId}/>,
    document.getElementById('terminate')
);
