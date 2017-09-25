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

        var green = false, yellow = false, red = false;

        var expiration = (a - b)/1000;

        if(this.props.auto_renew){
            green = true;
        }else if(expiration < 0){
            red = true;
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
            textData: ""
        };

        this.searchListByName = this.searchListByName.bind(this);
        this.searchListByDept = this.searchListByDept.bind(this);
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
    searchListByName(e) {
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
                        <table className="table">
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
}

ReactDOM.render(
    <AffiliateList />,
    document.getElementById('AffiliateList')
);
