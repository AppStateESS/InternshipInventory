import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group'
import classNames from 'classnames';

var ErrorMessagesBlock = React.createClass({
    render: function() {
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
});

var DepartmentList = React.createClass({
    render: function() {
        return (
            <option value={this.props.id}>{this.props.name}</option>
        )
    }
});

var ShowAffiliate = React.createClass({
    handleChange: function() {
        this.props.onShowAffiliate(this.props.id);
    },
    onShowAffiliate: function() {
        window.location =  "index.php?module=intern&action=showAffiliateEditView&affiliation_agreement_id=" + this.props.id;
    },
    render: function(){
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
});

// Main module that calls several components to build
// the affiliate agreements list screen.
var AffiliateList = React.createClass({
    getInitialState: function() {
        return ({
            mainData: null,
            displayData: null,
            deptData: null,
            errorWarning: null,
            messageType: null,
            searchDept: null,
            searchName: '',
            textData: "",
            sortBy: '',
            showAll: null,
            showActive: null,
            showExpired: null,
        });
    },
    componentWillMount: function() {
        // Grabs department and affiliate agreement
        // data at start of execution.
        this.getData();
        this.getDept();
    },
    getData: function() {
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
    },
    getDept: function() {
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
    },
    onSearchListChange: function(e) {

        var name = null;

        // Saves the name that the user is looking for.
        name = e.target.value.toLowerCase();
        this.setState({searchName: name});

        this.updateDisplayData(name, this.state.sortBy, this.state.isToggleOn);

    },
    //Method for taking an array and searching it by name, returns array.
    searchListByName: function(data, nameToSearch) {
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

    },
    searchListByDept: function(e) {
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
    },
    // Returns sorted array to be used in createList function
    onSortByChange: function(e) {
        var sort = null;

        //Saves sorting option that was clicked.
        sort = e.target.value;
        this.setState({sortBy: sort});

        this.updateDisplayData(this.state.searchName, sort);

    },
    //Method for storing the selected sort order and setting sortBy state.
    sortBy: function(unsorted, typeOfSort) {
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

    },
    // Organizes the order of the sort/filter functions to update the data displayed.
    // searchName and sort are both states.
    /*onToggle: function(e) {
        //reads action
        var toggle = null;

        toggle = e.target.value;
        this.setState({toggleActive: !toggle});
        event.preventDefault();

        this.updateDisplayData(this.state.searchName, this.state.sortBy, toggle);
        //calls update display data*/


        /*if (toggleOption) {
            for (var i = 0; i < data.length; i++) {
                var item0 = data[i];

                if (item0.active === 'Active') {
                    filtered.push(item0);
                }
            }
            for (var j = 0; j < data.length; j++) {
                var item1 = data[j];

                if (item1.active === 'Active (auto-renewed)') {
                    filtered.push(item1);
                }
            }
            for (var x = 0; x < data.length; x++) {
                var item2 = data[x];

                if (item2.active === 'Expired') {
                    filtered.push(item2);
                }
            }
        }
        else {
            for (var a = 0; a < data.length; a++) {
                var item3 = data[a];

                if (item3.active === 'Expired') {
                    filtered.push(item3);
                }
            }
            for (var b = 0; b < data.length; b++) {
                var item4 = data[b];

                if (item4.active === 'Active (auto-renewed)') {
                    filtered.push(item4);
                }
            }
            for (var c = 0; c < data.length; c++) {
                var item5 = data[c];

                if (item5.active === 'Active') {
                    filtered.push(item5);
                }
            }
        }*/
    onShowAll: function() {
        this.setState({showAll: true, showActive: false, showExpired: false});

        this.updateDisplayData(this.state.searchName, this.state.sortBy, this.state.showAll,
                              this.state.showActive, this.state.showExpired);
    },
    onShowActive: function() {
        this.setState({showAll: false, showActive: true, showExpired: false});

        this.updateDisplayData(this.state.searchName, this.state.sortBy, this.state.showAll,
                              this.state.showActive, this.state.showExpired);
    },
    onShowExpired: function() {
        this.setState({showAll: false, showActive: false, showExpired: true});

        this.updateDisplayData(this.state.searchName, this.state.sortBy, this.state.showAll,
                              this.state.showActive, this.state.showExpired);
    },
    updateDisplayData: function(typedName, sort, allClicked, activeClicked, expiredClicked) {
        var filtered = [];

        /*if (allClicked) {
            filtered = this.
        }*/

        if (typedName !== null) {
            filtered = this.searchListByName(this.state.mainData, typedName);
        }
        else {
            filtered = this.state.mainData;
        }

        if (sort !== null) {
            filtered = this.sortBy(filtered, sort);
        }
        else {
            filtered = this.sortBy(filtered, 'sortByAZ');
        }

        this.setState({displayData: filtered});

    },
    render: function() {
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
                        <div className="btn-group" data-toggle="buttons">
                            <label className="btn btn-default" onClick={this.onShowAll}>
                                <input type="radio" value="all" />All
                            </label>
                            <label className="btn btn-default" onClick={this.onShowActive}>
                                <input type="radio" value="active" />Active
                            </label>
                            <label className="btn btn-default" onClick={this.onShowExpired}>
                                <input type="radio" value="expired"/>Expired
                            </label>
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
});

ReactDOM.render(
    <AffiliateList />,
    document.getElementById('AffiliateList')
);
