'use strict';

var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;

var LocationSelector = React.createClass({
    displayName: 'LocationSelector',

    getInitialState: function getInitialState() {
        return {
            domestic: false,
            international: false,
            availableStates: null,
            availableCountries: null,
            hasError: false
        };
    },
    componentDidMount: function componentDidMount() {
        // Fetch list of available states
        $.ajax({
            url: 'index.php?module=intern&action=GetStates',
            dataType: 'json',
            success: (function (data) {
                this.setState({ availableStates: data });
            }).bind(this),
            error: (function (xhr, status, err) {
                console.error(status, err.toString());
            }).bind(this)
        });

        // Fetch list of available countries
        $.ajax({
            url: 'index.php?module=intern&action=GetAvailableCountries',
            dataType: 'json',
            success: (function (data) {
                this.setState({ availableCountries: data });
            }).bind(this),
            error: (function (xhr, status, err) {
                console.error(status, err.toString());
            }).bind(this)
        });
    },
    domestic: function domestic() {
        this.setState({ domestic: true, international: false });
    },
    international: function international() {
        this.setState({ domestic: false, international: true });
    },
    anyLocation: function anyLocation() {
        this.setState({ domestic: false, international: false });
    },
    render: function render() {
        var fgClasses = classNames({
            'form-group': true,
            'has-error': this.state.hasError
        });

        var dropdown;
        if (!this.state.domestic && !this.state.international) {
            dropdown = '';
        } else if (this.state.domestic) {
            dropdown = React.createElement(StateDropDown, { key: 'states', ref: 'state', states: this.state.availableStates, formStyle: 'horizontal' });
        } else {
            dropdown = React.createElement(InternationalDropDown, { key: 'countries', ref: 'country', countries: this.state.availableCountries, formStyle: 'horizontal' });
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

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'form-group' },
                React.createElement(
                    'label',
                    { className: 'col-lg-3 control-label', htmlFor: 'location' },
                    'Location'
                ),
                React.createElement(
                    'div',
                    { className: 'col-lg-8' },
                    React.createElement(
                        'div',
                        { className: 'btn-group' },
                        React.createElement(
                            'label',
                            { className: anyLabelClass },
                            'Any Location',
                            React.createElement('input', { type: 'radio', name: 'location', value: '-1', style: { position: "absolute", clip: "rect(0, 0, 0, 0)" }, onClick: this.anyLocation })
                        ),
                        React.createElement(
                            'label',
                            { className: domesticLabelClass },
                            'Domestic',
                            React.createElement('input', { type: 'radio', name: 'location', value: 'domestic', style: { position: "absolute", clip: "rect(0, 0, 0, 0)" }, onClick: this.domestic })
                        ),
                        React.createElement(
                            'label',
                            { className: internationalLabelClass },
                            'International',
                            React.createElement('input', { type: 'radio', name: 'location', value: 'internat', style: { position: "absolute", clip: "rect(0, 0, 0, 0)" }, onClick: this.international })
                        )
                    )
                )
            ),
            React.createElement(
                ReactCSSTransitionGroup,
                { transitionName: 'example', transitionLeave: false, transitionEnterTimeout: 500 },
                dropdown
            )
        );
    }
});

ReactDOM.render(React.createElement(LocationSelector, null), document.getElementById('LocationSelector'));
//# sourceMappingURL=search.js.map
