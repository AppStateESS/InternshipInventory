import React from 'react';
import classNames from 'classnames';

class StateDropDown extends React.Component {
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

        var states = this.props.states;

        var output = null;

        if(this.props.formStyle === undefined || this.props.formStyle === 'vertical'){
            output = (
                <div className="row">
                    <div className="col-sm-12 col-md-4 col-md-push-3">
                        <div className={fgClasses} id="state">
                            <label htmlFor="state" className="control-label">State</label>
                            <select id="state" name="state" className="form-control">
                                {Object.keys(states).map(function(key) {
                                    if(states[key].active === 1){
                                        return <option key={key} value={key}>{states[key].full_name}</option>;
                                    } else {
                                        return <option key={key} value={key} disabled style={{textDecoration:"line-through", color: "#FFF", backgroundColor: "#777"}}>{states[key].full_name}</option>;
                                    }

                                })}
                            </select>
                        </div>
                    </div>
                </div>
            );
        } else if (this.props.formStyle === 'horizontal'){
            output = (
                <div className="form-group">
                    <label htmlFor="state" className="col-lg-3 control-label">State</label>
                    <div className="col-lg-8">
                        <select id="state" name="state" className="form-control">
                            {Object.keys(states).map(function(key) {
                                return <option key={key} value={key}>{states[key].full_name}</option>;
                            })}
                        </select>
                    </div>
                </div>
            );
        }

        return output;
    }
}

export default StateDropDown;
