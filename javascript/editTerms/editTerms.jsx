import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group'

//got from editAdmin.jsx
class ErrorMessagesBlock extends React.Component {
    render() {
        if (this.props.errors == null) {
            return '';
        }

        var errors = this.props.errors;

        return (
          <div className="row">
              <div className="col-sm-12 col-md-6 col-md-push-3">
                  <div className="alert alert-warning" role="alert">
                      <p><i className="fa fa-exclamation-circle fa-2x"></i> Warning: {errors}</p>

                  </div>
              </div>
          </div>
        );
    }
}
