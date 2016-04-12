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
                            Object.keys(states).map(function (key) {
                                if (states[key].active == 1) {
                                    return React.createElement(
                                        'option',
                                        { key: key, value: key },
                                        states[key].full_name
                                    );
                                } else {
                                    return React.createElement(
                                        'option',
                                        { key: key, value: key, disabled: true },
                                        states[key].full_name
                                    );
                                }
                            })
                        )
                    )
                )
            );
        }

        return output;
    }
});
//# sourceMappingURL=StateDropDown.js.map
