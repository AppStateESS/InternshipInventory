// Adds the components involved in the adding of different Locations
// to the Add Affiliation form.


var LocationBox = React.createClass({
    getInitialState: function() {
        return {locs: [], usedlocs: []};
    },
    addloc: function(nameToAdd)
    {
        this.postData(nameToAdd);
    },
    removeLoc: function(loc)
    {
        this.deleteData(loc);
    },
    componentWillMount: function(){
        this.getData();
    },
    getData: function(){
        $.ajax({
            url: 'index.php?module=intern&action=stateRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Adds Select a State to the data array.
                data.unshift({full_name: "Select a State", abbr: "AA"});
                this.setState({locs: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("test failed")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateStateRest&affiliation_agreement_id='+aaId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({usedlocs: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("test failed")
                console.err(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    postData: function(state){
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateStateRest&affiliation_agreement_id='+aaId+'&state='+state,
            type: 'POST',
            success: function() {
                this.getData();
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to add to the database. " + err.toString())
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    deleteData: function(state){
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateStateRest&affiliation_agreement_id='+aaId+'&state='+state,
            type: 'DELETE',
            success: function() {
                this.getData();
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to remove from the database." + err.toString())
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    render: function() {
        return (
            <div className ="LocationBox">
                <LocationDropdown onAdd={this.addloc} data={this.state.locs} used={this.state.usedlocs}/>
                <p></p>
                <LocationList removeClick={this.removeLoc} data={this.state.usedlocs}/>
            </div>
        );
    }
});

var LocationDropdown = React.createClass({
    add: function() {
        var locToAdd = this.refs.locChoices.getDOMNode().value;
        this.props.onAdd(locToAdd);
    },
    render: function() {
        var options = this.props.data;
        var used = this.props.used;
        var selectOptions = this.props.data.map(function(node){
            if(used.indexOf(node.full_name) > 0){
                return <option key={node.abbr} value={node.abbr} disabled>{node.full_name}</option>
            }
            return (<option key={node.abbr} value={node.abbr}>{node.full_name}</option>);
        });
        return (
            <div className="LocationDropdown">
                <div className="form-group">
                    <select className="form-control" ref="locChoices">
                        {selectOptions}
                    </select>
                </div>
                <div className="form-group">
                    <button onClick={this.add} className="btn btn-success btn-md">Add</button>
                </div>
            </div>
        );
    }
});



var LocationList = React.createClass({
    render: function() {
        var removeClick = this.props.removeClick;
        var listNodes = this.props.data.map(function(panel){
            return (
                <LocationPanel key={panel} remove={removeClick} loc={panel}/>
        );
    });
    return (
        <ul className="list-group">
        {listNodes}
        </ul>
    );
}
});

var LocationPanel = React.createClass({
    remove: function() {
        this.props.remove(this.props.loc)
    },
    render: function() {
        return (
            <li className="list-group-item">
            {this.props.loc}
            <button onClick={this.remove} className="close">
            &times;
            </button>
            </li>
        );
    }
});



React.render(
    <LocationBox/>,
document.getElementById('locations')
);
