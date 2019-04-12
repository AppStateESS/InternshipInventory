import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';

// on add internship page

// fuzzy search for host, add button to create new host
class SearchBox extends React.Component {
    constructor(props) {
      super(props);
      this.state = {dataError: null};
    }
    componentDidMount() {
    	var searchSuggestions = new Bloodhound({
            datumTokenizer: function(datum){
                var nameTokens      = Bloodhound.tokenizers.obj.whitespace('name');
                return nameTokens.concat(statusTokens);
            },
    		queryTokenizer: Bloodhound.tokenizers.whitespace,
    		remote: {
                url: 'index.php?module=intern&action=GetHostSuggestions&searchString=%QUERY',
                wildcard: '%QUERY'
            }
    	});
        var myComponent = this;
        var element = this.refs.typeahead;
        $(element).typeahead({
            minLength: 3,
            highlight: true,
            hint: true
        },
        {
        	name: 'hosts',
        	display: 'hostId',
        	source: searchSuggestions.ttAdapter(),
            limit: 15,
        	templates: {
        		suggestion: function(row) {
                    if(row.error === undefined){
                        return ('<p>'+row.name + ' &middot; ' + row.hostId + '</p>');
                    } else {
                        myComponent.setState({dataError: row.error});
                        return ('<p></p>');
                    }
        		}
        	}
        });
        // Event handler for selecting a suggestion
        var handleSearch = this.props.onSelect;
        $(element).bind('typeahead:select', function(obj, datum, name) {
            if(datum.error === undefined){
                handleSearch(datum.hostId);
            }
        });
        // Event handler for enter key.. Search with whatever the person put in the box
        var handleReset = this.props.onReset;
        var thisElement = this;
        $(element).keydown(function(e){

            // Look for the enter key
            if(e.keyCode === 13) {
                // Prevent default to keep the form from being submitted on enter
                e.preventDefault();
                return;
            }
            // Ignore the tab key
            if(e.keyCode === 9){
                return;
            }
            // For any other key, reset the search results because the input box has changed
            thisElement.setState({dataError: null});
            handleReset();
        });
        // Do a search onBlur too (in case user tabs away from the search field)
        $(element).blur(function(e){
            handleSearch($(element).typeahead('val'));
        });
    }
    componentWillUnmount() {
        var element = ReactDOM.findDOMNode(this);
        $(element).typeahead('destroy');
    }
    render() {
        var errorNotice = null;

        if(this.state.dataError !== null){
            errorNotice = <div style={{marginTop: "1em"}} className="alert alert-danger">
                                <p>{this.state.dataError}</p>
                            </div>
        }
        return (
            <div>
                <div>
                    <input type="search" name="hostName" id="hostSearch" className="form-control typeahead input-lg" placeholder="Acme, Inc." ref="typeahead" autoComplete="off"/>
                </div>
                {errorNotice}
            </div>
        );
    }
}

// Host Preview
class HostPreview extends React.Component {
    render() {
        return (
            <div>
                <span className="lead"> {this.props.host.name}</span>
                <i className="fa fa-credit-card"></i> {this.props.host.approve_flag}
            </div>
        );
    }
}

class HostAgencies extends React.Component {
    constructor(props) {
      super(props);

      this.state = {hasError: false};
    }
    setError(status){
        this.setState({hasError: status});
    }
    render() {
        var fgClasses = classNames({
                        'form-group': true,
                        'has-error': this.state.hasError
                    });
        return (
            <div className="row">
                <div className="col-sm-12 col-md-4 col-md-push-3">
                    <div className={fgClasses} id="agency">
                        <label htmlFor="agency2" className="control-label">Internship Host</label>
                        <input type="text" id="agency2" name="agency" className="form-control" placeholder="Acme, Inc." />
                    </div>
                </div>
            </div>
        );
    }
}

export default HostAgencies;
