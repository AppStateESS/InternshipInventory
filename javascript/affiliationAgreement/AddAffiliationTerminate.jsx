
var TerminateBox = React.createClass({
    getInitialState: function() {
        return {terminate: 0};
    },
    componentWillMount: function(){
        this.getData();
    },
    getData: function(){
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateRest&affiliation_agreement_id='+aaId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({terminate: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    clicked: function(){
        $.ajax({
            url:'index.php?module=intern&action=AffiliateRest&affiliation_agreement_id='+aaId,
            type: 'POST',
            success:function(){
                this.getData();
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to Set Termination value")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    render: function() {
        return(
            <div>
            <TerminateButton clicked={this.clicked} data={this.state.terminate} />
        </div>
    );
}
});


var TerminateButton = React.createClass({
    clicked: function() {
        this.props.clicked();
    },
    render:function() {
        if(this.props.data == 0){
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

React.render(<TerminateBox/>,
    document.getElementById('terminate')
);
