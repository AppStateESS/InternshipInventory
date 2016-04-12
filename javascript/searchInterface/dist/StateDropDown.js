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

<<<<<<< HEAD
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
                                return React.createElement(
                                    'option',
                                    { key: key, value: key },
                                    states[key]
                                );
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
=======
        if (this.props.formStyle === 'horizontal') {
            var output = React.createElement(
                'div',
                { className: 'form-group' },
>>>>>>> f0fddb38c33b14c0a576037e714f7c0533bc8447
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
<<<<<<< HEAD
                                states[key]
=======
                                states[key].full_name
>>>>>>> f0fddb38c33b14c0a576037e714f7c0533bc8447
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
