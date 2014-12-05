// Student Search Parent Component
var StudentSearch = React.createClass({
    getInitialState: function() {
        return {student: null, studentFound: false};
    },
    // Performs a search and handles the response
    handleSearch: function(e) {
        e.preventDefault();
        this.resetPreview();
        console.log('clicked search');
        console.log(this.refs.searchString.getDOMNode().value.trim());
        var searchString = this.refs.searchString.getDOMNode().value.trim();
        if(!searchString) {
            return;
        }
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
                    <SearchBox/>
                    {this.state.studentFound ? <span className="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span> : null }
                    {this.state.studentFound ? <span id="inputSuccess2Status" className="sr-only">(success)</span> : null }
                </div>
                
                <div className="form-group">
                    <button type="button" id="student-search-btn" onClick={this.handleSearch} className="btn btn-default pull-right">Search</button>
                </div>
                
                {this.state.studentFound ? <StudentPreview student={this.state.student}/> : null }
            </div>
        );
    }
});

var SearchBox = React.createClass({
    componentDidMount: function() {
    	
    	var bh = new Bloodhound({
    		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
    		queryTokenizer: Bloodhound.tokenizers.whitespace,
    		remote: 'index.php?module=intern&action=GetSearchSuggestions&searchString=%QUERY',
    		limit: 10,
    	});
    	bh.initialize();
    	
        var element = this.getDOMNode();
        $(element).typeahead({
            minLength: 3,
            highlight: true
        },
        {
        	name: 'students',
        	displayKey: 'studentId',
        	source: bh.ttAdapter(),
        	templates: {
        		suggestion: function(row) {
        			return ('<p>'+row.name+' &middot; ' + row.studentId + '</p>')
        		}
        	}
        });
        
        console.log(element);
    },
    componentWillUnmount: function() {
        var element = this.getDOMNode();
        $(element).typeahead('destory');
    },
    render: function() {
        return (
            <input type="search" name="studentId" className="form-control typeahead input-lg" placeholder="Banner ID, User name, or Full Name" ref="searchString" autoComplete="off" autofocus/>
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

/*
var ResultsList = React.createClass({
    render: function() {
        var resultNodes = this.props.results.map(function(student) {
                return (
                    <ResultRow key={student.studentId} student={student} />
                    );
            });
        return (
            <div className="resultsList">
                {resultNodes}
            </div>
        );
    }
});
*/

// Result Row
var ResultRow = React.createClass({
    render: function() {
        return (
            <div>
                {this.props.student.name} - {this.props.student.studentId} - {this.props.student.major}
            </div>
        );
    }
});

React.render(
    <StudentSearch />,
    document.getElementById('searchform')
);