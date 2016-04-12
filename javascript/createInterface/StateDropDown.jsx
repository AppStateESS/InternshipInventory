var StateDropDown = React.createClass({
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

        var states = this.props.states;

        if(this.props.formStyle === undefined || this.props.formStyle === 'vertical'){
            var output = (
                <div className="row">
                    <div className="col-sm-12 col-md-4 col-md-push-3">
                        <div className={fgClasses} id="state">
                            <label htmlFor="state" className="control-label">State</label>
                            <select id="state" name="state" className="form-control">
                                {Object.keys(states).map(function(key) {
                                    if(states[key].active == 1){
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
        }

        return output;
    }
});
