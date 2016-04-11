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

        if (this.props.formStyle === 'horizontal') {
            var output = React.createElement(
                'div',
                { className: 'form-group' },
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
                                states[key].full_name
                            );
                        })
                    )
                )
            );
        }

        return output;
    }
});
//# sourceMappingURL=StateDropDown.js.map
