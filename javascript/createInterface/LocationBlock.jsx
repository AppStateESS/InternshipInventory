import React from 'react';
import $ from 'jquery';
import classNames from 'classnames';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group';

import InternationalDropDown from './InternationalDropDown.jsx';
import StateDropDown from './StateDropDown.jsx';

/*************
 * Locations *
 *************/
var LocationBlock = React.createClass({
    getInitialState: function() {
        return ({
            domestic: null,
            international: null,
            availableStates: null,
            availableCountries: null,
            hasError: false
            });
    },
    componentDidMount: function() {
        // Fetch list of states
        $.ajax({
            url: 'index.php?module=intern&action=GetStates',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({availableStates: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });

        // Fetch list of available countries
        $.ajax({
            url: 'index.php?module=intern&action=GetAvailableCountries',
            dataType: 'json',
            success: function(data) {
                this.setState({availableCountries: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });
    },
    domestic: function() {
        this.setState({domestic: true, international: false});
    },
    international: function() {
        this.setState({domestic: false, international: true});
    },
    setError: function(status){
        this.setState({hasError: status});
    },
    render: function () {
        var fgClasses = classNames({
                        'form-group': true,
                        'has-error': this.state.hasError
                    });

        var dropdown;
        if(this.state.domestic === null) {
            dropdown = '';
        } else if (this.state.domestic) {
            dropdown = <StateDropDown key="states" ref="state" states={this.state.availableStates}/>;
        } else {
            dropdown = <InternationalDropDown key="countries" ref="country" countries={this.state.availableCountries}/>;
        }
        return (
            <div>
                <div className="row">
                    <div className="col-sm-12 col-md-6 col-md-push-3">
                        <div className={fgClasses} id="location">
                            <label htmlFor="location" className="control-label">Location</label> <br />
                            <div className="btn-group" data-toggle="buttons">
                                <label className="btn btn-default" onClick={this.domestic}>
                                    <input type="radio" name="location" defaultValue="domestic" />Domestic
                                </label>
                                <label className="btn btn-default" onClick={this.international}>
                                    <input type="radio" name="location" defaultValue="international" />International
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <ReactCSSTransitionGroup transitionName="example" transitionLeave={false} transitionEnterTimeout={500} >
                    {dropdown}
                </ReactCSSTransitionGroup>
            </div>
        );
    }
});

export default LocationBlock;
