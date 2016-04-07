'use strict';

var StateDropDown = React.createClass({
    displayName: 'StateDropDown',

    getInitialState: function getInitialState() {
        return { hasError: false };
    },
    setError: function setError(status) {
        this.setState({ hasError: status });
    },
    render: function render() {
        var fgClasses = classNames({
            'form-group': true,
            'has-error': this.state.hasError
        });

        var states = this.props.states;

        if (this.props.formStyle === undefined || this.props.formStyle === 'vertical') {
            var output = React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-sm-12 col-md-4 col-md-push-3' },
                    React.createElement(
                        'div',
                        { className: fgClasses, id: 'state' },
                        React.createElement(
                            'label',
                            { htmlFor: 'state', className: 'control-label' },
                            'State'
                        ),
                        React.createElement(
                            'select',
                            { id: 'state', name: 'state', className: 'form-control' },
                            this.props.states.map(function (data) {
                                return React.createElement(StateList, { key: data.abbr, ref: 'state',
                                    sAbbr: data.abbr,
                                    stateName: data.full_name,
                                    active: data.active });
                            })
                        )
                    )
                )
            );
        }

        if (this.props.formStyle === 'horizontal') {
            var output = React.createElement(
                'div',
                { 'class': 'form-group' },
                React.createElement(
                    'label',
                    { htmlFor: 'state', className: 'col-lg-3 control-label' },
                    'State'
                ),
                React.createElement(
                    'div',
                    { className: 'col-lg-8' },
                    React.createElement(
                        'select',
                        { id: 'state', name: 'state', className: 'form-control' },
                        Object.keys(states).map(function (key) {
                            return React.createElement(
                                'option',
                                { key: key, value: key },
                                states[key]
                            );
                        })
                    )
                )
            );
        }

        return output;
    }
});

var StateList = React.createClass({
    displayName: 'StateList',

    // Disables/Enables the state in the dropdown
    render: function render() {
        if (this.props.active == 1) {
            var optionSelect = React.createElement(
                'option',
                { value: this.props.sAbbr },
                this.props.stateName
            );
        } else {
            var optionSelect = React.createElement(
                'option',
                { value: this.props.sAbbr, disabled: true },
                this.props.stateName
            );
        }
        return optionSelect;
    }
});
//# sourceMappingURL=StateDropDown.js.map
