
var EditInternshipInterface = React.createClass({
    getInitialState: function() {
        return {
            internData: null,
            departmentData: null,
            facultyData: null,
            stateData: null
        };
    },
    componentWillMount: function(){
        this.getInternData();
        this.getStates();
        this.getDepartmentData();
    },
    getInternData: function(){
        // Grabs the internship data
        $.ajax({
            url: 'index.php?module=intern&action=editInternshipRest&internshipId='+internshipId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
              console.log(data);
                this.setState({internData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to load intern data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    getStates: function(){
        // Grabs the State data
        $.ajax({
            url: 'index.php?module=intern&action=stateRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({stateData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to load state data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    //getFacultyListForDept&department
    getFacultyData: function(deptNum){
        // Grabs the State data
        $.ajax({
            url: 'index.php?module=intern&action=getFacultyListForDept&department='+deptNum,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data != '')
                {
                  data.unshift({first_name: "None", last_name: "", id: "-1"});
                  this.setState({facultyData: data});
                } else {
                  this.setState({facultyData: null});
                }
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to load faculty data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    getDepartmentData: function(){
        // Grabs the State data
        $.ajax({
            url: 'index.php?module=intern&action=facultyDeptRest',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                this.setState({departmentData: data});
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Failed to load department data.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    render: function() {
        if(this.state.internData != null){
            var interface = <div>
                              <h1>
                                  <i className="fa fa-edit"></i> Edit Internship
                              </h1>

                              <form className="form-horizontal">

                                <div className="form-group">
                                  <div className="col-lg-1 col-lg-offset-8">
                                    <button type="submit" className="btn btn-primary" id="{SUBMIT_ID}">Save</button>
                                  </div>

                                  <div className="col-lg-1">
                                    <a href="{DELETE_URL}" className="btn btn-danger-hover" onclick="return confirm('Are you sure you want to delete this internship?');">Delete</a>                             
                                  </div>

                                  <div className="col-lg-1 col-lg-offset-1">
                                    <button type="button" id="contract-button" className="btn btn-default pull-right generateContract"><i className="fa fa-file"></i> Generate Contract</button>
                                  </div>
                                </div>

                               
                                  <div className="row">
                                    <div className="col-lg-6">

                                      <StudentInformation intern = {this.state.internData.intern}
                                                  student = {this.state.internData.student}
                                                  states = {this.state.stateData} />
                                      <EmgContactList />

                                    </div>

                                    <div className="col-lg-6">
                                      <InternStatus workflow = {this.state.internData.wfState}
                                                    intern = {this.state.internData.intern} />

                                      <FacultyInterface facultyData    = {this.state.facultyData} 
                                                        departmentData = {this.state.departmentData}
                                                        deptNumber     = {this.state.internData.intern.department_id}
                                                        getFacultyData = {this.getFacultyData}
                                                        facultyID      = {this.state.internData.intern.faculty_id} />
                                    </div>
                                  </div>
                              
                              </form>
                            </div>
        }else{
            var interface = <p className="text-muted" style={{position:"absolute", top:"50%", left:"50%"}}>
                                <i className="fa fa-spinner fa-2x fa-spin"></i> Loading Internship...
                            </p>;
        }
        return (
            <div>
              {interface} 
            </div>
        );
    }
});

var StudentInformation = React.createClass({
    render: function() {
        var intern = this.props.intern;
        var student = this.props.student;
        var stateData = '';

        if(this.props.states != null){
            stateData = this.props.states.map(function (state) {
                  return (
                          <StateDropDown  key={state.abbr}
                                          sAbbr={state.abbr}
                                          stateName={state.full_name}
                                          active={state.active}
                                          stuState={intern.student_state} />
                      );
              }.bind(this));
        }
        
        return (
            <fieldset>
              <legend>Student</legend>
                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_MIDDLE_NAME_ID}">Banner Id</label>
                  <div id="bannerid" className="col-lg-6"><p className="form-control-static">{intern.banner}</p></div>
                </div>

                <div className="form-group required">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_MIDDLE_NAME_ID}">First Name</label>
                  <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.first_name} /></div>
                </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_MIDDLE_NAME_ID}">Middle Name/Initial</label>
                <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.middle_name} /></div>
              </div>

              <div className="form-group required">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_LAST_NAME_ID}">Last Name</label>
                <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.last_name} /></div>
              </div>

              <div className="form-group required">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_EMAIL_ID}">ASU Email</label>
                <div className="col-lg-6">
                  <div className="input-group">
                    <input type="text" className="form-control" defaultValue={intern.email} /><span className="input-group-addon">@appstate.edu</span>
                  </div>
                </div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="birthdate">Birth date</label>
                <div id="birthdate" className="col-lg-6"><p className="form-control-static">{intern.birth_date}</p></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_ADDRESS_ID}">Address</label>
                <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.student_address} /></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_CITY_ID}">City</label>
                <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.student_city} /></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_STATE_ID}">State</label>
                <div className="col-lg-6">
                  <select className="form-control" onChange={this.handleDrop}>{stateData}</select></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_ZIP_ID}">Zip Code</label>
                <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.student_zip} /></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_PHONE_ID}">Phone</label>
                <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.phone} /></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_GPA_ID}">GPA</label>
                <div className="col-lg-6"><p className="form-control-static">{intern.gpa}</p></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="campus">Campus</label>
                <div id="campus" className="col-lg-6"><p className="form-control-static">{intern.campus}</p></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="level">Level</label>
                <div id="level" className="col-lg-6"><p className="form-control-static">{intern.level}</p></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{UGRAD_MAJOR_ID}{GRAD_MAJOR_ID}">Major / Program</label>

                <div className="col-lg-8">
                  <div className="btn-group-vertical" data-toggle="buttons" role="group" aria-label="major selector">
    
                    {student.majors_repeat.map(function (major) {
                        return (
                                <MajorSelector  key={major.code}
                                                active={major.active}
                                                checked={major.checked}
                                                code={major.code}
                                                desc={major.desc} />
                            );
                    })}
             
                  </div>
                </div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="gradDate">Graduation Date</label>
                <div id="gradDate" className="col-lg-6"><p className="form-control-static">{student.grad_date}</p></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="credit-hours">Credit Hours</label>
                <div id="credit-hours" className="col-lg-6"><p className="form-control-static">{student.enrolled_credit_hours}</p></div>
              </div>
            </fieldset>
        );
    }
});

var StateDropDown = React.createClass({
    render: function() {
        var optionSelect = <div />
        if (this.props.active == 1 && this.props.stuState == this.props.sAbbr){
          optionSelect = <option value={this.props.sAbbr} defaultValue>{this.props.stateName}</option>
        }else if(this.props.active == 1){
          optionSelect = <option value={this.props.sAbbr}>{this.props.stateName}</option>
        }
        return (   
          optionSelect
        );
    }
});

var MajorSelector = React.createClass({
    render: function() {
        var setActive = (this.props.active == 'active') ? true : false;

        var activeButton = classNames({
           'btn'         : true,
           'btn-default' : true,
           'active'      : setActive
        });

        if (this.props.checked == 'checked'){
            var majorSelect = <label className={activeButton}>
                                <input type="radio" name="major_code" autoComplete="off" value="{this.props.code}"  defaultChecked /> {this.props.desc}
                              </label>
        }else{
            var majorSelect = <label className={activeButton}>
                                <input type="radio" name="major_code" autoComplete="off" value="{this.props.code}" /> {this.props.desc}
                              </label>
        }
        return (   
            majorSelect
        );
    }
});


var EmgContactList = React.createClass({
    render: function() {
        return (   
            <fieldset>
                <legend>Emergency Contacts</legend>
                <div className="row">
                    <div className="col-md-12">
                        <EmergencyContactList />
                    </div>
                </div>
            </fieldset> 
        );
    }
});

var InternStatus = React.createClass({
    render: function() {
        var status = this.props.workflow.status;
        var workflowAction = this.props.workflow.workflowAction;
        var allow = this.props.workflow.allow;

// **************
// NOT DONE!!!  *
// **************

        return (   
            <fieldset>
              <legend>Status</legend>
              <p>
                Current Status: <strong>{status}</strong>
              </p>
              <div className="panel panel-default">
                <div className="panel-heading">
                  <h4 className="panel-title">Next Status</h4>
                </div>
                <div className="panel-body">

                    {Object.keys(workflowAction).map(function(key) {
                        return( 
                            <div className="radio">
                                <label><input type="radio" name="workflowOption" value={key}/>{workflowAction[key]}</label>
                            </div>)
                    })}
                    
                </div>
              </div>
              <div className="form-group">
                <div className="col-lg-10">
                  <div className="checkbox">
                    { allow ? <label><input type="checkbox" value="" />Certified by Office of International Education and Development</label>
                            : <label><input type="checkbox" value="" disabled />Certified by Office of International Education and Development</label>
                    }

                  </div>
                </div>
              </div>
            </fieldset>
        );
    }
});

var FacultyInterface = React.createClass({
    getInitialState: function(){
        return {showDetails: false, facultyID: null};
    },
  //Query for list of departments for first drop down
  //Query based on chosen department for second drop down
  //Use second dropdown information for details page.
    componentWillMount: function() {
        this.getFacultyData();
    },
    getFacultyData: function() {
        if (this.props.deptNumber !== '')
        {
          this.setState({facultyID: null, showDetails: true}, this.props.getFacultyData(this.props.deptNumber));
        } else {
          this.setState({facultyID: null, showDetails: false});
        }
    },
    setFacultyID: function(num) {
        this.setState({facultyID: num, showDetails: true});
    },
    hideDetailInfo: function() {
        this.setState({facultyID: null,showDetails: false});
    },
    render: function() {

        if (this.props.departmentData == null){
            return (<div />)
        }
        var facultyDetail = null;
        var facultyData = this.props.facultyData;
        var facultyID   = (this.state.facultyID != null) ? this.state.facultyID :this.props.facultyID;
        var dept        = this.props.departmentData;
        var deptNum     = this.props.deptNumber;

        //FIX DEPT NAME
        // WORK HERE TUESDAY - GETTING CHANGE BUTTON WORKING
        if(facultyData != null){
            facultyDetail = facultyData.map(function (faculty) {
                if(facultyID == faculty.id)
                  return (<FacultyDetail key={faculty.id} 
                                         username   = {faculty.username} 
                                         phone      = {faculty.phone} 
                                         fax        = {faculty.fax} 
                                         address1   = {faculty.street_address1}
                                         address2   = {faculty.street_address2}
                                         city       = {faculty.city}
                                         state      = {faculty.state}
                                         zip        = {faculty.zip} 
                                         fname      = {faculty.first_name}
                                         lname      = {faculty.last_name}
                                         deptname   = {dept[deptNum].name}
                                         hideDetailInfo = {this.hideDetailInfo} />);
            }.bind(this));
        }     //this.props.facultyID == null
        return (
            <fieldset>
              <legend>Faculty Advisor</legend>
              {(this.state.showDetails) ? facultyDetail 
                                        : <FacultyDropDown facultyData    = {facultyData} 
                                                           departmentData = {this.props.departmentData}
                                                           getFacultyData = {this.props.getFacultyData}
                                                           deptNumber     = {this.props.deptNumber}
                                                           setFacultyID   = {this.setFacultyID} />}
            </fieldset>
        );
    }

});

var FacultyDropDown = React.createClass({
    handleDeptDrop: function(e) {
        var deptNum = e.target.value;

        this.props.getFacultyData(deptNum);
    },
    handleFaculty: function(e) {
        var faculty = e.target.value;
        this.props.setFacultyID(faculty);

    },
    render: function() {
        var departments = this.props.departmentData;
        var facultyData = this.props.facultyData;
        var deptNumber  = this.props.deptNumber;

        if (this.props.facultyData == null){
            var ddFaculty =   <select className='form-control' disabled>
                                <option>No Advisors Available</option>
                              </select>
        } else {
            var ddFaculty =   <select className='form-control' onChange={this.handleFaculty}>
                                {Object.keys(facultyData).map(function(key) {
                                    return <option key={key} value={facultyData[key].id}>{facultyData[key].first_name+" "+facultyData[key].last_name}</option>;
                                })}
                              </select>
        }
        return(
            <div id="faculty_selector">
              <div className="form-group required">
                <label className="col-lg-3 control-label" for="{DEPARTMENT_ID}">Department</label>
                <div className="col-lg-8">
                  <select className="form-control" defaultValue={deptNumber} onChange={this.handleDeptDrop}> 
                    {Object.keys(departments).map(function(key) {                 
                          return <option key={departments[key].id} value={departments[key].id}>{departments[key].name}</option>;
                    })}
                  </select>
                </div>
              </div>
              <div className="form-group">
                <label className="col-lg-3 control-label" for="{FACULTY_ID}">Faculty Advisor / Instructor of Record</label>
                <div className="col-lg-8">
                    {ddFaculty}
                </div>
              </div>
            </div>
        );
    }
});

var FacultyDetail = React.createClass({
    handleClick: function() {
        this.props.hideDetailInfo();
    },
    render: function() {
        var name = this.props.fname + " " + this.props.lname + " - " + this.props.dept;


        // Format Faculty Email
        var emailInfo = "mailto:" + this.props.username + "@appstate.edu";
        var email = <a href={emailInfo}> {this.props.username + "@appstate.edu"} </a>
        
        // Format Faculty Phone
        var phone = '';
        if(this.props.phone !== ''){
            var phoneInfo = "tel:+1" + this.props.phone;
            phone = <a href={phoneInfo}> {this.props.phone} </a>;
        } else {
            phone = <small className="text-muted">Has not been set</small>;
        }

        // Format Faculty Fax
        var fax = '';
        if(this.props.fax !== ''){
            var faxInfo = "fax:+1" + this.props.fax;
            fax = <a href={faxInfo}> {this.props.fax} </a>;
        } else {
            fax = <small className="text-muted">Has not been set</small>;
        }

        // Format Faculty Address
        var address = '';
        if(this.props.address1 !== '' && this.props.address1 !== null){
            address += this.props.address1;

            if (this.props.address2 !== '') {
                address += "<br />" + this.props.address2;
            }
        } else {
            address = <small className="text-muted">Address has not been set</small>;
        }
        if(this.props.city !== '' && this.props.city !== null && this.props.state !== '' && this.props.state !== null){
            address += "<br />" + this.props.city + ", " + this.props.state;
        }
        if(this.props.zip !== '' && this.props.zip !== null) {
            address += " " + this.props.zip;
        }


        return (
            <div id="faculty_details">

                <div className="row">
                  <div id="faculty_change" className="col-lg-2">
                    <button type="button" id="faculty-change" className="btn btn-default btn-xs" onClick={this.handleClick}>
                      <i className="fa fa-chevron-left"></i> change
                    </button>
                  </div>
                  <div id="faculty_name" className="col-lg-10 lead">{name}</div>
                </div>

                <div className="row">
                  <div className="col-lg-5 col-lg-offset-2">

                    <div className="row">
                      <div className="col-lg-12">
                        <p>
                          <abbr title="Email address"><i className="fa fa-envelope"></i></abbr> &nbsp;
                          <span id="faculty_email"></span>{email}
                        </p>
                      </div>
                    </div>

                    <div className="row">
                      <div className="col-lg-12">
                        <p>
                          <abbr title="Phone"><i className="fa fa-phone"></i></abbr> &nbsp;
                          <span id="faculty_phone"></span>{phone}
                        </p>
                      </div>
                    </div>

                    <div className="row">
                      <div className="col-lg-12">
                        <p>
                          <abbr title="Fax"><i className="fa fa-print"></i></abbr> &nbsp;
                          <span id="faculty_fax"></span>{fax}
                        </p>
                      </div>
                    </div>
                  </div>

                  <div className="col-lg-5">
                    <abbr title="Address"><i className="fa fa-map-marker"></i></abbr> &nbsp;
                    <address id="faculty_address">{address}</address>
                  </div>
                </div>
              </div>
        );
    }
});

ReactDOM.render(
    <EditInternshipInterface />, 
    document.getElementById('editInternshipInterface')
);