import React from 'react';
import classNames from 'classnames';

var InternationalDropDown = React.createClass({
    getInitialState: function(){
        return ({hasError: false});
    },
    setError: function(status){
        this.setState({hasError: status});
    },
    render: function() {
        var fgClasses = classNames({
                        'form-group': true,
                        'has-error': this.state.hasError
                    });

        var countries = this.props.countries;

        var output = null;

        if(this.props.formStyle === undefined || this.props.formStyle === 'vertical'){
            output = (
                <div className="row">
                    <div className="col-sm-12 col-md-4 col-md-push-3">
                        <div className={fgClasses} id="country">
                            <label htmlFor="country" className="control-label">Country</label>
                            <select id="country" name="country" className="form-control">
                                {Object.keys(countries).map(function(key) {
                                    return <option key={key} value={key}>{countries[key]}</option>;
                                })}
                            </select>
                        </div>
                    </div>
                </div>
            );
        }

        if(this.props.formStyle === 'horizontal') {
            output = (
                <div className="form-group">
                    <label htmlFor="country" className="col-lg-3 control-label">Country</label>
                    <div className="col-lg-8">
                        <select id="country" name="country" className="form-control">
                            {Object.keys(countries).map(function(key) {
                                return <option key={key} value={key}>{countries[key]}</option>;
                            })}
                        </select>
                    </div>
                </div>
            );
        }

        return output;
    }
});

export default InternationalDropDown;
