import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group'

//special host admin page
//see if need to pull out later since copy
class ErrorMessagesBlock extends React.Component {
    render() {
        if(this.props.errors === null){
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

class HostList extends React.Component {
  render() {
    return (
        <h1> Host List </h1>
    )
  }
}

class ShowHost extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
			mainData: null,
			errorWarning: null,
            messageType: null,
		};

        this.handleDecline = this.handleDecline.bind(this);
        this.handleAdd = this.handleAdd.bind(this);
        this.searchList = this.searchList.bind(this);
        this.getHostData = this.getHostData.bind(this);
        this.getConditions = this.getConditions.bind(this);
	}
  render() {
      var errors;
      if(this.state.errorWarning == null){
          errors = '';
      } else {
          errors = <ErrorMessagesBlock key="errorSet" errors={this.state.errorWarning} messageType={this.state.messageType} />
      }
    return (
        <div className="host">
            <ReactCSSTransitionGroup transitionName="example" transitionEnterTimeout={500} transitionLeaveTimeout={500}>
                {errors}
            </ReactCSSTransitionGroup>
            <h1> Special Host </h1>
        </div>
    );
  }
}

ReactDOM.render(<ShowHost />, document.getElementById('special_host'));
