import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import classNames from 'classnames';

import InternationalDropDown from '../createInterface/InternationalDropDown.jsx';
import StateDropDown from '../createInterface/StateDropDown.jsx';

import ReactCSSTransitionGroup from 'react-addons-css-transition-group';

class LocationSelector extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            domestic: false,
            international: false,
            availableStates: null,
            availableCountries: null,
            hasError: false
            };

        this.domestic = this.domestic.bind(this);
        this.international = this.international.bind(this);
        this.anyLocation = this.anyLocation.bind(this);
    }
    componentDidMount() {
        // Fetch list of available states
        $.ajax({
            url: 'index.php?module=intern&action=GetStates',
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
    }
    domestic() {
        this.setState({domestic: true, international: false});
    }
    international() {
        this.setState({domestic: false, international: true});
    }
    anyLocation() {
        this.setState({domestic: false, international: false});
    }
    render() {

        var dropdown;
        if(!this.state.domestic && !this.state.international) {
            dropdown = '';
        } else if (this.state.domestic) {
            dropdown = <StateDropDown key="states" ref={(element) => {this.stateDropDown = element}} states={this.state.availableStates} formStyle='horizontal'/>;
        } else {
            dropdown = <InternationalDropDown key="countries" ref={(element) => {this.countryDropDown = element}} countries={this.state.availableCountries} formStyle='horizontal'/>;
        }

        var anyLabelClass = classNames({
            'btn': true,
            'btn-default': true,
            'active': !this.state.domestic && !this.state.international
        });

        var domesticLabelClass = classNames({
            'btn': true,
            'btn-default': true,
            'active': this.state.domestic
        });

        var internationalLabelClass = classNames({
            'btn': true,
            'btn-default': true,
            'active': this.state.international
        });

        return (
            <div>
                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="location">Location</label>
                  <div className="col-lg-8">
                      <div className="btn-group">
                        <label className={anyLabelClass}>Any Location
                          <input type="radio" name="location" value="-1" style={{position: "absolute", clip: "rect(0, 0, 0, 0)"}}  onClick={this.anyLocation} />
                        </label>
                        <label className={domesticLabelClass}>Domestic
                          <input type="radio" name="location" value="domestic" style={{position: "absolute", clip: "rect(0, 0, 0, 0)"}} onClick={this.domestic}/>
                        </label>
                        <label className={internationalLabelClass}>International
                          <input type="radio" name="location" value="internat" style={{position: "absolute", clip: "rect(0, 0, 0, 0)"}} onClick={this.international} />
                        </label>
                      </div>
                  </div>
                </div>

                <ReactCSSTransitionGroup transitionName="example" transitionLeave={false} transitionEnterTimeout={500}>
                    {dropdown}
                </ReactCSSTransitionGroup>
            </div>
        );
    }
}

ReactDOM.render(
    <LocationSelector />, document.getElementById('LocationSelector')
);
