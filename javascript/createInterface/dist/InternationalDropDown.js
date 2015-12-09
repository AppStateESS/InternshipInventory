'use strict';

var InternationalDropDown = React.createClass({
    displayName: 'InternationalDropDown',

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

        var countries = this.props.countries;

        if (this.props.formStyle === undefined || this.props.formStyle === 'vertical') {
            var output = React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-sm-12 col-md-4 col-md-push-3' },
                    React.createElement(
                        'div',
                        { className: fgClasses, id: 'country' },
                        React.createElement(
                            'label',
                            { htmlFor: 'country', className: 'control-label' },
                            'Country'
                        ),
                        React.createElement(
                            'select',
                            { id: 'country', name: 'country', className: 'form-control' },
                            Object.keys(countries).map(function (key) {
                                return React.createElement(
                                    'option',
                                    { key: key, value: key },
                                    countries[key]
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
                { className: 'form-group' },
                React.createElement(
                    'label',
                    { htmlFor: 'country', className: 'col-lg-3 control-label' },
                    'Country'
                ),
                React.createElement(
                    'div',
                    { className: 'col-lg-8' },
                    React.createElement(
                        'select',
                        { id: 'country', name: 'country', className: 'form-control' },
                        Object.keys(countries).map(function (key) {
                            return React.createElement(
                                'option',
                                { key: key, value: key },
                                countries[key]
                            );
                        })
                    )
                )
            );
        }

        return output;
    }
});
//# sourceMappingURL=InternationalDropDown.js.map
