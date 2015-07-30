// Student Search Parent Component
var StudentSearch = React.createClass({
    getInitialState: function() {
        return {student: null, studentFound: false};
    },
    // Performs a search and handles the response
    doSearch: function(searchString) {
        $.ajax({
            url: 'index.php?module=intern&action=GetSearchSuggestions',
            dataType: 'json',
            data: {searchString: searchString},
            success: function(data) {
                console.log(data);
                console.log(data.length);

                if(data.length == 1) {
                    this.setState({student:data[0], studentFound: true});
                } else {
                    // TODO
                }
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }.bind(this)
        });
    },
    // Clears results from current state, resets for next search
    resetPreview: function() {
        this.setState({student: null, studentFound: false});
    },
    render: function() {
        var cx = React.addons.classSet;
        var fgClasses = cx({
            'form-group': true,
            'has-success': this.state.studentFound,
            'has-feedback': this.state.studentFound
        });
        return (
            <div className="col-sm-12 col-md-10 col-md-push-1">
                <div className={fgClasses} id="studentId">
                    <label htmlFor="studentId2" className="sr-only">Banner ID, User name, or Full Name</label>
                    <SearchBox onSelect={this.doSearch} onReset={this.resetPreview}/>
                    {this.state.studentFound ? <span className="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span> : null }
                    {this.state.studentFound ? <span id="inputSuccess2Status" className="sr-only">(success)</span> : null }
                </div>

                {this.state.studentFound ? <StudentPreview student={this.state.student}/> : null }
            </div>
        );
    }
});

var SearchBox = React.createClass({
    componentDidMount: function() {

    	var searchSuggestions = new Bloodhound({
            datumTokenizer: function(datum){
                var nameTokens      = Bloodhound.tokenizers.obj.whitespace('name');
                var studentIdTokens = Bloodhound.tokenizers.obj.whitespace('studentId');
                var usernameTokens  = Bloodhound.tokenizers.obj.whitespace('email');

                return nameTokens.concat(studentIdTokens).concat(usernameToekns);
            },
    		queryTokenizer: Bloodhound.tokenizers.whitespace,
    		remote: {
                url: 'index.php?module=intern&action=GetSearchSuggestions&searchString=%QUERY',
                wildcard: '%QUERY'
            }
    	});

        var element = this.getDOMNode();
        $(element).typeahead({
            minLength: 3,
            highlight: true,
            hint: true
        },
        {
        	name: 'students',
        	display: 'studentId',
        	source: searchSuggestions.ttAdapter(),
            limit: 15,
        	templates: {
        		suggestion: function(row) {
        			return ('<p>'+row.name+' &middot; ' + row.studentId + '</p>');
        		}
        	}
        });

        // Event handler for selecting a suggestion
        var handleSearch = this.props.onSelect;
        $(element).bind('typeahead:select', function(obj, datum, name) {
            // Redirect to the student profile the user selected
            handleSearch(datum.studentId);
        });

        // Event handler for enter key.. Search with whatever the person put in the box
        var handleReset = this.props.onReset;
        $(element).keydown(function(e){

            // Look for the enter key
            if(e.keyCode == 13) {
                // Prevent default to keep the form from being submitted on enter
                e.preventDefault();
                return;
            }

            // Ignore the tab key
            if(e.keyCode == 9){
                return;
            }

            // For any other key, reset the search results because the input box has changed
            handleReset();
        });
    },
    componentWillUnmount: function() {
        var element = this.getDOMNode();
        $(element).typeahead('destory');
    },
    render: function() {
        return (
            <input type="search" name="studentId" id="studentSearch" className="form-control typeahead input-lg" placeholder="Banner ID, User name, or Full Name" ref="searchString" autoComplete="off" autofocus/>
        );
    }
});

// Student Preview
var StudentPreview = React.createClass({
    render: function() {
        return (
            <div>
                <span className="lead"> {this.props.student.name}</span><br />
                <i className="fa fa-credit-card"></i> {this.props.student.studentId}<br />
                <i className="fa fa-envelope"></i> {this.props.student.email}<br />
                <i className="fa fa-graduation-cap"></i> {this.props.student.major}
            </div>
        );
    }
});

React.render(
    <StudentSearch />,
    document.getElementById('searchform')
);


/*********
 * Terms *
 *********/
var TermBlock = React.createClass({
    getInitialState: function() {
        return ({terms: null});
    },
    componentWillMount: function() {
        $.ajax({
            url: 'index.php?module=intern&action=GetAvailableTerms',
            dataType: 'json',
            success: function(data) {
                this.setState({terms: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }.bind(this)
        });
    },
    render: function() {
        var terms = this.state.terms;

        if(terms === null){
            return (<div></div>);
        }

        return (
            <div className="row">
                <div className="col-sm-12 col-md-6 col-md-push-3">
                    <div className="form-group" id="term">
                    <label htmlFor="term" className="control-label">Term</label><br />
                        <div className="btn-group" data-toggle="buttons">

                            {Object.keys(terms).map(function(key) {
                                return (
                                    <label className="btn btn-default" key={key}>
                                        <input type="radio" name="term" key={key} value={key} />{terms[key]}
                                    </label>
                                );
                            })}

                        </div>
                    </div>
                </div>
            </div>

        );
    }
});

React.render(<TermBlock />, document.getElementById('termBlock'));

/*************
 * Locations *
 *************/
var LocationBlock = React.createClass({
    getInitialState: function() {
        return {domestic: null, international: null, availableStates: null, availableCountries: null};
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
        console.log('clicked domestic');
        this.setState({domestic: true, international: false});
    },
    international: function() {
        console.log('clicked international');
        this.setState({domestic: false, international: true});
    },
    render: function () {
        var dropdown;

        if(this.state.domestic === null) {
            dropdown = '';
        } else if (this.state.domestic) {
            dropdown = <StateDropDown states={this.state.availableStates}/>;
        } else {
            dropdown = <InternationalDropDown countries={this.state.availableCountries}/>;
        }
        return (
            <div>
                <div className="row">
                    <div className="col-sm-12 col-md-6 col-md-push-3">
                        <div className="form-group" id="location">
                            <label htmlFor="location" className="control-label">Location</label> <br />
                            <div className="btn-group" data-toggle="buttons">
                                <label className="btn btn-default" onClick={this.domestic}>
                                    <input type="radio" name="location" defaultValue="domestic" />Domestic
                                </label>
                                <label className="btn btn-default" onClick={this.international}>
                                    <input type="radio" name="location" defaultValue="international" />International
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {dropdown}
            </div>
        );
    }
});

var StateDropDown = React.createClass({
    render: function() {
        var states = this.props.states;
        return (
            <div className="row">
                <div className="col-sm-12 col-md-4 col-md-push-3">
                    <div className="form-group" id="state">
                        <label htmlFor="state" className="control-label">State</label>
                        <select id="state" name="state" className="form-control">
                            {Object.keys(states).map(function(key) {
                                return <option key={key} value={key}>{states[key]}</option>;
                            })}
                        </select>
                    </div>
                </div>
            </div>
        );
    }
});


var InternationalDropDown = React.createClass({
    render: function() {
        var countries = this.props.countries;
        return (
            <div className="row">
                <div className="col-sm-12 col-md-4 col-md-push-3">
                    <div className="form-group" id="country">
                        <label htmlFor="country" className="control-label">Country</label>
                        <select id="country" name="country" className="form-control">
                            {Object.keys(countries).map(function(key) {
                                return <option key={key} value={key}>{countries[key]}</option>;
                            })}
                        </select>
                    </div>
                </div>
            </div>
        );
    }
});

React.render(
    <LocationBlock />,
    document.getElementById('locationBlock')
);


/***********************
 * Department Dropdown *
 ***********************/
var Department = React.createClass({
    getInitialState: function() {
        return {departments: null};
    },
    componentWillMount: function() {
        $.ajax({
            url: 'index.php?module=intern&action=GetDepartments',
            dataType: 'json',
            success: function(data) {
                this.setState({departments: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }.bind(this)
        });
    },
    render: function() {
        var departments = this.state.departments;
        if(departments === null) {
            return (<div></div>);
        }

        return (
            <div className="row">
                <div className="col-sm-12 col-md-4 col-md-push-3">
                    <div className="form-group" id="department">
                        <label htmlFor="department2" className="control-label">Department</label>
                        <select id="department2" name="department" className="form-control" defaultValue="-1">
                            {Object.keys(departments).map(function(key) {
                                return <option key={key} value={key}>{departments[key]}</option>;
                            })}
                        </select>
                    </div>
                </div>
            </div>
        );
    }
});

React.render(<Department />, document.getElementById('department'));


/*********************
 * Host Agency Field *
 *********************/
var HostAgency = React.createClass({
    render: function() {
        return (
            <div className="row">
                <div className="col-sm-12 col-md-4 col-md-push-3">
                    <div className="form-group" id="agency">
                        <label htmlFor="agency2" className="control-label">Host Agency</label>
                        <input type="text" id="agency2" name="agency" className="form-control" placeholder="Acme, Inc." />
                    </div>
                </div>
            </div>
        );
    }
});

React.render(<HostAgency />, document.getElementById('hostAgency'));


/*****************
 * Submit Button *
 *****************/
 var CreateInternshipButton = React.createClass({
    render: function() {
        return (
            <div className="row">
                <div className="col-sm-12 col-md-6 col-md-push-3">
                    <button type="submit" className="btn btn-lg btn-primary pull-right" id="create-btn">Create Internship</button>
                </div>
            </div>
        );
    }
 });

 React.render(
     <CreateInternshipButton />, document.getElementById('submitButton')
 );
