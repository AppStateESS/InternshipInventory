var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;

var LocationSelector = React.createClass({
    getInitialState: function() {
        return ({
            domestic: false,
            international: false,
            availableStates: null,
            availableCountries: null,
            hasError: false
            });
    },
    componentDidMount: function() {
        // Fetch list of available states
        $.ajax({
            url: 'index.php?module=intern&action=GetAvailableStates',
            dataType: 'json',
            success: function(data) {
                this.setState({availableStates: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }.bind(this)
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
            }.bind(this)
        });
    },
    domestic: function() {
        this.setState({domestic: true, international: false});
    },
    international: function() {
        this.setState({domestic: false, international: true});
    },
    anyLocation: function() {
        this.setState({domestic: false, international: false});
    },
    render: function () {
        var fgClasses = classNames({
                        'form-group': true,
                        'has-error': this.state.hasError
                    });

        var dropdown;
        if(!this.state.domestic && !this.state.international) {
            dropdown = '';
        } else if (this.state.domestic) {
            dropdown = <StateDropDown key="states" ref="state" states={this.state.availableStates} formStyle='horizontal'/>;
        } else {
            dropdown = <InternationalDropDown key="countries" ref="country" countries={this.state.availableCountries} formStyle='horizontal'/>;
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
});

ReactDOM.render(
    <LocationSelector />, document.getElementById('LocationSelector')
);
