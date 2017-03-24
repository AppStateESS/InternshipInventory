import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';

// Components for adding states (locations) to an Affiliation Agreement
// on the Edit Affiliation interface.

var LocationItem = React.createClass({
    remove: function() {
        this.props.remove(this.props.location)
    },
    render: function() {
        return (
            <li className="list-group-item">
                {this.props.location.full_name}
                <button onClick={this.remove} className="close">&times;</button>
            </li>
        );
    }
});


var LocationList = React.createClass({
    render: function() {
        var listNodes = this.props.locations.map(function(location){
            return (
                <LocationItem key={location.abbr} remove={this.props.removeClick} location={location}/>
            );
        }.bind(this));

        return (
            <ul className="list-group">
                {listNodes}
            </ul>
        );
    }
});


var LocationDropdown = React.createClass({
    add: function() {
        var locToAdd = this.refs.locChoices.getDOMNode().value;
        this.props.onAdd(locToAdd);
    },
    render: function() {
        var options = this.props.locations;
        // Adds Select a State to the data array.
        options.unshift({full_name: "Select a State", abbr: "-1"});

        var selectOptions = options.map(function(location){

            // Check if this location is in the set of used locations
            var usedIndex = this.props.usedLocations.findIndex(function(element, index, arr){
                if(location.abbr === element.abbr){
                    return true;
                } else {
                    return false;
                }
            });

            // If the location has been used (findIndex returns non-negative), then disable the location in the dropdown list
            if(usedIndex > -1){
                return <option key={location.abbr} value={location.abbr} disabled>{location.full_name}</option>
            }

            // Otherwise, return an enabled option
            return (<option key={location.abbr} value={location.abbr}>{location.full_name}</option>);
        }.bind(this));

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


var LocationBox = React.createClass({
    getInitialState: function() {
        return {locs: null, usedLocs: null};
    },
    addloc: function(nameToAdd) {
        this.postData(nameToAdd);
    },
    removeLoc: function(loc) {
        this.deleteData(loc);
    },
    componentWillMount: function() {
        // Get data on inital load
        this.getData();
    },
    getData: function() {
        // Get the full list of all states
        $.ajax({
            url: 'index.php?module=intern&action=stateRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({locs: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("There was an error loading location data for this agreement.");
                console.error(status, err.toString());
            }
        });

        // Get the list of states already added (used) on this agreement
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateStateRest&affiliation_agreement_id='+this.props.affiliationId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({usedLocs: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("There was an error loading location data for this agreement.")
                console.error(status, err.toString());
            }
        });
    },
    postData: function(state){
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateStateRest&affiliation_agreement_id='+this.props.affiliationId+'&state='+state,
            type: 'POST',
            success: function() {
                this.getData();
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to add to the database. " + err.toString())
                console.error(status, err.toString());
            }
        });
    },
    deleteData: function(state){
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateStateRest&affiliation_agreement_id='+this.props.affiliationId+'&state='+state.abbr,
            type: 'DELETE',
            success: function() {
                this.getData();
            }.bind(this),
            error: function(xhr, status, err) {
                alert("There was an error while trying to remove that location.");
                console.error(status, err.toString());
            }
        });
    },
    render: function() {
        // If we don't have location data yet, don't even bother rendering
        if(this.state.locs == null || this.state.usedLocs == null){
            return (<div></div>);
        }

        return (
            <div className="LocationBox">
                <LocationDropdown onAdd={this.addloc} locations={this.state.locs} usedLocations={this.state.usedLocs}/>
                <LocationList removeClick={this.removeLoc} locations={this.state.usedLocs}/>
            </div>
        );
    }
});


ReactDOM.render(<LocationBox affiliationId={window.aaId}/>,
    document.getElementById('locations')
);
