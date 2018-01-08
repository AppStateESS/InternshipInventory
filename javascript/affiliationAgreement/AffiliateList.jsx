import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group'
import classNames from 'classnames';

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

class DepartmentList extends React.Component {
    render() {
        return (
            <option value={this.props.id}>{this.props.name}</option>
        )
    }
}

class ShowAffiliate extends React.Component {
    constructor(props){
        super(props);

        this.handleChange = this.handleChange.bind(this);
        this.onShowAffiliate = this.onShowAffiliate.bind(this);
    }
    handleChange() {
        this.props.onShowAffiliate(this.props.id);
    }
    onShowAffiliate() {
        window.location =  "index.php?module=intern&action=showAffiliateEditView&affiliation_agreement_id=" + this.props.id;
    }
    render(){
        var b = new Date().getTime();
        var a = new Date(this.props.end_date * 1000);
        var year = a.getFullYear();
        var month = a.getMonth() + 1;
        var date = a.getDate();
        var dateForm = month + '/' + date + '/' + year;
        var active = 'Active';

        var green = false, yellow = false, red = false;

        var expiration = (a - b)/1000;

        if(this.props.auto_renew){
            green = true;
            active = 'Active (auto-renewed)';
        }else if(expiration < 0){
            red = true;
            active = 'Expired';
        } else if(expiration < 7884000) {
            yellow = true;
        } else {
            green = true;
        }

        var alertClass = classNames({
            'alert-danger': red,
            'alert-success': green,
            'alert-warning': yellow
        });

        return (
            <tr role="button" id={this.props.id} className={alertClass} onClick={this.onShowAffiliate} key={this.props.id}>

                <td>{this.props.name}</td>
                <td>{dateForm}</td>
                <td>{active}</td>

            </tr>
        );
    }
}

// Main module that calls several components to build
// the affiliate agreements list screen.
class AffiliateList extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            mainData: null,
            displayData: null,
            deptData: null,
            errorWarning: null,
            messageType: null,
            searchDept: null,
            searchName: '',
            textData: "",
            sortBy: '',
            showFilter: ''
        };

        this.getData = this.getData.bind(this);
        this.getDept = this.getDept.bind(this);
        this.onSearchListChange = this.onSearchListChange.bind(this);
        this.searchListByDept = this.searchListByDept.bind(this);
        this.onSortByChange = this.onSortByChange.bind(this);
        this.onShow = this.onShow.bind(this);
        this.updateDisplayData = this.updateDisplayData.bind(this);
    }
    componentWillMount() {
        // Grabs department and affiliate agreement
        // data at start of execution.
        this.getData();
        this.getDept();
    }
    getData() {
        $.ajax({
            url: 'index.php?module=intern&action=AffiliateListRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({mainData: data,
                                displayData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to grab displayed data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }
    getDept() {
        //Fetch list of departments
        $.ajax({
            url: 'index.php?module=intern&action=deptRest',
            action: 'GET',
            dataType: 'json',
            success: function(data) {
				data.unshift({name: "Select a department", id: "-1"});
				this.setState({deptData: data});
			}.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to grab deptartment data.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)
        });
    }
    onSearchListChange(e) {

        var name = null;

        // Saves the name that the user is looking for.
        name = e.target.value.toLowerCase();
        this.setState({searchName: name});

        this.updateDisplayData(name, this.state.sortBy, this.state.showFilter);

    }
    //Method for taking an array and searching it by name, returns array.
    searchListByName(data, nameToSearch) {
      var filtered = [];

      // Looks for the name by filtering the mainData
      for (var i = 0; i < data.length; i++) {
          var item = data[i];

          // Make the item, name lowercase for easier searching
          if (item.name.toLowerCase().includes(nameToSearch)) {
              filtered.push(item);
          }
      }
      return filtered;

    }
    searchListByDept(e) {
        var dept = null;

        try {
            // Saves the dept that the user is looking for.
            dept = e.target.value;
        } catch (err) {
            dept = this.state.searchDept;
        }
        if(dept === "-1"){
            this.getData();
            return;
        }

        $.ajax({
            type: 'GET',
            url: 'index.php?module=intern&action=AffiliateDeptAgreementRest',
            dataType: 'json',
            data: {dept : dept},
            success: function(data)
            {
                this.setState({searchDept: dept,
                                mainData: data,
                                displayData: data});
            }.bind(this),
			error: function(xhr, status, err) {
				alert("Failed to grab searched list.")
				console.error(this.props.url, status, err.toString());
			}.bind(this)
        });
    }
    // Returns sorted array to be used in createList function
    onSortByChange(e) {
        var sort = null;

        //Saves sorting option that was clicked.
        sort = e.target.value;
        this.setState({sortBy: sort});

        this.updateDisplayData(this.state.searchName, sort, this.state.showFilter);

    }
    //Method for storing the selected sort order and setting sortBy state.
    sortBy(unsorted, typeOfSort) {
      var sorted = [];

      // Different logic for different types of sorts,
      // all utilizing sort function.
      switch(typeOfSort) {
          case 'sortByAZ':

              sorted = unsorted.sort(function (a, b) {
                  if (a.name < b.name) return -1;
                  if (a.name > b.name) return 1;
                  return 0;
              });
              break;
          case 'sortByZA':

              sorted = unsorted.sort(function (a, b) {
                  if (a.name > b.name) return -1;
                  if (a.name < b.name) return 1;
                  return 0;
              });
              break;
          case 'SoonerToLater':

              sorted = unsorted.sort(function (a,b) {
                  if (a.end_date < b.end_date) return -1;
                  if (a.end_date > b.end_date) return 1;
                  return 0;
              });
              break;
          case 'LaterToSooner':

              sorted = unsorted.sort(function (a,b) {
                  if (a.end_date > b.end_date) return -1;
                  if (a.end_date < b.end_date) return 1;
                  return 0;
              });
              break;
          default:
              sorted = unsorted;
      }
      return sorted;

    }
    onShow(e) {
        var option = null;

        // Saves filter option.
        option = e.target.value;
        this.setState({showFilter: option});

        this.updateDisplayData(this.state.searchName, this.state.sortBy, option);

    }
    viewShowFilter(data, filter) {
        var filtered = [];

        for (var i = 0; i < data.length; i++) {
            var item = data[i];

            // Finding out if expired or not.
            var current = new Date().getTime();
            var itemDate = new Date(item.end_date * 1000);
            var expiration = (itemDate - current)/1000;

            if (filter === 'active') {
                if (item.auto_renew || expiration > 0) {
                    filtered.push(item);
                }
            }
            else if (filter ==='expired') {
                if (!item.auto_renew && expiration < 0) {
                    filtered.push(item);
                }
            }
            else {
                filtered.push(item);
            }
        }
        return filtered;

    }
    // Organizes the order of the sort/filter functions to update the data displayed.
    // searchName and sort are both states.
    updateDisplayData(typedName, sort, showFilter) {
        var filtered = [];

        // First filters data.
        if (showFilter !== null) {
            filtered = this.viewShowFilter(this.state.mainData, showFilter);
        } else {
            filtered = this.state.mainData;
        }

        // Second searches list for name.
        if (typedName !== null) {
            filtered = this.searchListByName(filtered, typedName);
        }

        // Third sorts list.
        if (sort !== null) {
            filtered = this.sortBy(filtered, sort);
        } else {
            filtered = this.sortBy(filtered, 'sortByAZ');
        }

        this.setState({displayData: filtered});

    }
    render() {

        var AffiliateData = null;
        if (this.state.mainData != null) {
            AffiliateData = this.state.displayData.map(function (affil) {
                return (
                    <ShowAffiliate key={affil.id}
                        name={affil.name}
                        end_date={affil.end_date}
                        id={affil.id}
                        auto_renew={affil.auto_renew} />
                );
            });
        } else {
            AffiliateData = <tr><td></td></tr>;
        }

        var dData = null;
		if (this.state.deptData != null) {
			dData = this.state.deptData.map(function (dept) {
			return (
					<DepartmentList key={dept.id}
						name={dept.name}
						id={dept.id} />
				);
			});
		} else {
			dData = "";
		}

        var errors;
        if (this.state.errorWarning == null){
            errors = '';
        } else {
            errors = <ErrorMessagesBlock key="errorSet" errors={this.state.errorWarning} messageType={this.state.messageType} />
        }

        return (
            <div className="affiliateList">

                <ReactCSSTransitionGroup transitionName="example" transitionEnterTimeout={500} transitionLeaveTimeout={500}>
                    {errors}
                </ReactCSSTransitionGroup>

                <div className="row">
                    <div className="col-md-4">
                        <h2>Affiliation Agreements</h2>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-3">
                        <a href="index.php?module=intern&action=addAgreementView" className="btn btn-md btn-success"><i className="fa fa-plus"></i> Add New Agreement </a>
                    </div>
                </div>
                <br></br>
                <div className="row">
                    <div className="col-md-3">
                        <div className="input-group">
                            <label>Search by Name</label>
                            <input type="text" className="form-control" placeholder="Search for..." onChange={this.onSearchListChange} />
                        </div>
                    </div>
                    <div className="col-md-3">
                        <div className="form-group">
                            <label>Search by Department</label>
                            <select className="form-control" onChange={this.searchListByDept}>
                                {dData}
                            </select>
                        </div>
                    </div>
                    <div className="col-md-3">
                        <div className="form-group">
                            <label>Sort By</label>
                            <select className="form-control" onChange={this.onSortByChange} value={this.state.value}>
                                <option value="-1">Select an option</option>
                                <option value="sortByAZ">Name: A-Z</option>
                                <option value="sortByZA">Name: Z-A</option>
                                <option value="SoonerToLater">Expiration Date: Sooner to Later</option>
                                <option value="LaterToSooner">Expiration Date: Later to Sooner</option>
                            </select>
                        </div>
                    </div>
                    <div className="col-md-3">
                        <label className="control-label">Filter</label> <br />
                        <div className="btn-group" data-toggle="buttons" onClick={this.onShow} value={this.state.value}>
                            <button className="btn btn-default" value="all">
                                <input  type="radio"/>All
                            </button>
                            <button className="btn btn-default" value="active">
                                <input type="radio"/>Active
                            </button>
                            <button className="btn btn-default" value="expired">
                                <input  type="radio"/>Expired
                            </button>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-12">
                        <table className="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Expiration Date</th>
                                    <th>Active/Expired</th>
                                </tr>
                            </thead>
                            <tbody>
                                {AffiliateData}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        );
    }
}

ReactDOM.render(
    <AffiliateList />,
    document.getElementById('AffiliateList')
);
