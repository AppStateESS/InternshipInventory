import React from 'react';
import $ from 'jquery';
import classNames from 'classnames';

/***********************
 * Department Dropdown *
 ***********************/
class Department extends React.Component {
    constructor(props) {
      super(props);

      this.state = {departments: null, hasError: false};
    }
    setError(status){
        this.setState({hasError: status});
    }
    componentWillMount() {
        $.ajax({
            url: 'index.php?module=intern&action=GetDepartments',
            dataType: 'json',
            success: function(data) {
                this.setState({departments: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });
    }
    render() {
        var departments = this.state.departments;
        if(departments === null) {
            return (<div></div>);
        }

        var fgClasses = classNames({
                        'form-group': true,
                        'has-error': this.state.hasError
                    });

        return (
            <div className="row">
                <div className="col-sm-12 col-md-4 col-md-push-3">
                    <div className={fgClasses} id="department">
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
}

export default Department;
