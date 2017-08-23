import React from 'react';
import classNames from 'classnames';

class InternationalDropDown extends React.Component {
    constructor(props){
      super(props);

      this.state = {hasError: false};
    }
    setError(status){
        this.setState({hasError: status});
    }
    render() {
        var fgClasses = classNames({
                        'form-group': true,
                        'has-error': this.state.hasError
                    });

        var countries = this.props.countries;

        var output = null;

        if(this.props.formStyle === 'vertical' || this.props.formStyle === undefined){
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
        } else if(this.props.formStyle === 'horizontal') {
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
}

export default InternationalDropDown;
