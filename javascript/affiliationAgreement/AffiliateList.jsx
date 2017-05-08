import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group'

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
        var color = null;

        var a = new Date(this.props.end_date * 1000);
        var months = ['1','2','3','4','5','6','7','8','9','10','11','12'];
        var year = a.getFullYear();
        var month = months[a.getMonth()];
        var date = a.getDate();
        var dateForm = month + '/' + date + '/' + year;
        var expiration = a - a.getTime();

        if(this.props.auto_renew){
          color = "green";
        }else if(expiration < 0){
          color = "red";
        } else if(expiration < 7884000) {
          color = "orange";
        } else {
          color = "green";
        }

        return (
            <tr id={this.props.id} style={{color: {color}}} onClick={this.onShowAffiliate} key={this.props.id}>

                <td>{this.props.name}</td>
                <td>{dateForm}</td>

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
            textData: ""
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
                //this.searchListByName();
                //this.searchListByDept();
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
    searchListByName: function(e) {
        var name = null;

        try {
            // Saves the name that the user is looking for.
            name = e.target.value.toLowerCase();
            this.setState({searchName: name});
        }
        catch (err) {
            name = this.state.searchName;
        }

        var filtered = [];

        // Looks for the name by filtering the mainData
        for (var i = 0; i < this.state.mainData.length; i++) {
            var item = this.state.mainData[i];

            // Make the item, name lowercase for easier searching
            if (item.name.toLowerCase().includes(name)) {
                filtered.push(item);
            }
        }

        this.setState({displayData: filtered});
    },
    searchListByDept: function(e) {
        var dept = null;

        try {
            // Saves the dept that the user is looking for.
            dept = e.target.value;
        } catch (err) {
            dept = this.state.searchDept;
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
    render: function() {
        var AffiliateData = null;

        // document.getElementById(this.props.id).style.color={this.color};

        if (this.state.mainData != null) {
            AffiliateData = this.state.displayData.map(function (affil) {
                return (
                    <ShowAffiliate key={affil.id}
                        name={affil.name}
                        end_date={affil.end_date}
                        id={affil.id} />
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
                    <div className="col-md-3">
                        <div className="input-group">
                            <label>Search by Name</label>
                            <input type="text" className="form-control" placeholder="Search for..." onChange={this.searchListByName} />
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
                </div>
                <div className="row">
                    <div className="col-md-12">
                        <table className="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Expiration Date</th>
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
