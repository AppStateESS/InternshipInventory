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

        if(this.props.formStyle === 'horizontal'){
            var output = (
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
});
