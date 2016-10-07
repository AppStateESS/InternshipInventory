// !! internshipId is hardcoded as a global variable !!

var EditInternshipInterface = React.createClass({
    getInitialState: function() {
        return {
            internData: null,
            departmentData: null,
            facultyData: null,
            stateData: null,
            submitted: false
        };
    },
    componentWillMount: function(){
        this.getInternData();
        this.getStates();
        this.getDepartmentData();
    },
    saveInternship: function(e){
        e.preventDefault();
        var form = e.target;
        var thisComponent = this;

        this.setState({submitted: true}, function(){
            // After disabling submit buttons, use callback to validate the data
            if(!true){
                // If the data doesn't validate, wait a second before re-enabling the submit button
                // This makes sure the user sees the "Creating..." spinner, instead of it re-rendering
                // so fast that they don't think it did anything
                setTimeout(function(){
                    thisComponent.setState({submitted: false});
                    // thisComponent.refs.mainInterface.buildInternshipData(form);
                }, 1000);

                return;
            }

            setTimeout(function(){
                thisComponent.setState({submitted: false});
                var data = thisComponent.refs.mainInterface.buildInternshipData(form);
                console.log(data);
                $.ajax({
                    url: 'index.php?module=intern&action=editInternshipRest&internshipId='+internshipId,
                    type: 'POST',
                    processData: false,
                    dataType: 'json',
                    data: JSON.stringify(data),
                    success: function() {
                        console.log("success!");
                    }.bind(this),
                    error: function(xhr, status, err) {
                        alert("Failed to save intern data.")
                        console.error(this.props.url, status, err.toString());
                    }.bind(this)
                });
            }, 1000);
        });
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
            return <MainInterface internData      = {this.state.internData} 
                                  facultyData     = {this.state.facultyData}
                                  departmentData  = {this.state.departmentData}
                                  stateData       = {this.state.stateData}
                                  submitted       = {this.state.submitted}
                                  getFacultyData  = {this.getFacultyData} 
                                  saveInternship  = {this.saveInternship}
                                  ref             = "mainInterface"/>
        }else{
            return( <p className="text-muted" style={{position:"absolute", top:"50%", left:"50%"}}>
                        <i className="fa fa-spinner fa-2x fa-spin"></i> Loading Internship...
                    </p>
            );
        }
    }
});

var MainInterface = React.createClass({
    buildInternshipData: function(form) {
        var student = this.refs.student.grabStudentData();
        var status  = this.refs.status.grabStatusData(form);
        var faculty = this.refs.faculty.grabFacultyData();
        var term    = this.refs.term.grabCourseAndTerm();
        var type    = this.refs.type.grabTypeData(form);
        var host    = this.refs.host.buildHostData(form);

        var internship = {student:  student,
                          status:   status,
                          faculty:  faculty,
                          term:     term,
                          type:     type};


        var internData = {internship: internship,
                          host:       host};
        return internData;
        //Host Information
        //var status  = this.refs.student.grabStudentData();
    },
    render: function() {
        var internData     = this.props.internData;
        var stateData      = this.props.stateData;
        var facultyData    = this.props.facultyData;
        var departmentData = this.props.departmentData;
        var deleteURL      = "index.php?module=intern&action=DeleteInternship&internship_id=" + internshipId;
        return(
            <div>
              <h1>
                  <i className="fa fa-edit"></i> Edit Internship
              </h1>

              <form className="form-horizontal" onSubmit={this.props.saveInternship}>

                <div className="form-group">
                  <div className="col-lg-1 col-lg-offset-8">
                    <SaveInternshipButton submitted={this.props.submitted}/>
                  </div>

                  <div className="col-lg-1">
                    <a href="" className="btn btn-danger-hover" onclick="return confirm('Are you sure you want to delete this internship?');">Delete</a>                             
                  </div>

                  <div className="col-lg-1 col-lg-offset-1">
                    <button type="button" id="contract-button" className="btn btn-default pull-right generateContract"><i className="fa fa-file"></i> Generate Contract</button>
                  </div>
                </div>

               
                  <div className="row">
                    <div className="col-lg-6">
                      <StudentInformation intern  = {internData.intern}
                                          student = {internData.student}
                                          states  = {stateData}
                                          ref     = "student" />
                      <EmgContactList />
                    </div>

                    <div className="col-lg-6">
                      <InternStatus workflow  = {internData.wfState}
                                    intern    = {internData.intern}
                                    ref       = "status" />

                      <FacultyInterface facultyData    = {facultyData} 
                                        departmentData = {departmentData}
                                        deptNumber     = {internData.intern.department_id}
                                        getFacultyData = {this.props.getFacultyData}
                                        facultyID      = {internData.intern.faculty_id}
                                        ref            = "faculty" />

                      <CourseAndTerm intern   = {internData.intern}
                                     subjects = {internData.subjects}
                                     ref      = "term" />

                      <TypeInterface experience_type = {internData.experience_type}
                                     ref             = "type"/>
                    </div>
                  </div>
              
                  <div className="row">
                    <div className="col-lg-12">
                      <div className="form-group">
                        <div className="col-lg-1 col-lg-offset-9">
                          <button className="btn btn-success" id="{SUBMIT_ID}">Add Host</button>
                        </div>
                        <div className="col-lg-1 pull-right">
                          <SaveInternshipButton submitted={this.props.submitted}/>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div className="row">
                    <div className="col-lg-12">
                      {internData.agency.map(function (agency) {
                        return (
                                <HostInterface key      = {agency.id}
                                               hostData = {agency}
                                               intern   = {internData.intern}
                                               docs     = {internData.docs}
                                               states   = {stateData}
                                               ref      = "host" />
                            );
                      })}
                      
                    </div>
                  </div>

                  <div className="row">
                    <div className="col-lg-6">
                      <NoteBox />
                    </div>

                    <div className="col-lg-6">
                       <Contracts title="Extra Documents"/>

                    </div>
                  </div>

                  <ChangeLog noteData = {internData.notes}/>
              </form>
            </div>
        );
    }
});

var SaveInternshipButton = React.createClass({
    render: function() {
        var button = null;
        if(this.props.submitted) {
            button = <button type="submit" className="btn btn-primary" id="save-btn" disabled><i className="fa fa-spinner fa-spin"></i> Saving...</button>;
        } else {
            button = <button type="submit" className="btn btn-primary" id="save-btn" >Save</button>;
        }
        return (
            button
        );
    }
});

var StudentInformation = React.createClass({
    grabStudentData: function() {

        var student = { id:       this.props.intern.banner,
                        fname:    this.refs.fname.value, 
                        lname:    this.refs.lname.value,
                        mname:    this.refs.mname.value,
                        email:    this.refs.email.value,
                        address:  this.refs.address.value,
                        city:     this.refs.city.value,
                        state:    this.refs.state.value,
                        zip:      this.refs.zip.value,
                        phone:    this.refs.phone.value,
                        department: this.props.intern.department_id}

        return student;
    },
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
                  <div className="col-lg-6"><input type="text" className="form-control" ref="fname" defaultValue={intern.first_name} /></div>
                </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_MIDDLE_NAME_ID}">Middle Name/Initial</label>
                <div className="col-lg-6"><input type="text" className="form-control" ref="mname" defaultValue={intern.middle_name} /></div>
              </div>

              <div className="form-group required">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_LAST_NAME_ID}">Last Name</label>
                <div className="col-lg-6"><input type="text" className="form-control" ref="lname" defaultValue={intern.last_name} /></div>
              </div>

              <div className="form-group required">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_EMAIL_ID}">ASU Email</label>
                <div className="col-lg-6">
                  <div className="input-group">
                    <input type="text" className="form-control" ref="email" defaultValue={intern.email} /><span className="input-group-addon">@appstate.edu</span>
                  </div>
                </div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="birthdate">Birth date</label>
                <div id="birthdate" className="col-lg-6"><p className="form-control-static">{intern.birth_date}</p></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_ADDRESS_ID}">Address</label>
                <div className="col-lg-6"><input type="text" className="form-control" ref="address" defaultValue={intern.student_address} /></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_CITY_ID}">City</label>
                <div className="col-lg-6"><input type="text" className="form-control" ref="city" defaultValue={intern.student_city} /></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_STATE_ID}">State</label>
                <div className="col-lg-6">
                  <select className="form-control" ref="state">{stateData}</select></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_ZIP_ID}">Zip Code</label>
                <div className="col-lg-6"><input type="text" className="form-control" ref="zip" defaultValue={intern.student_zip} /></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_PHONE_ID}">Phone</label>
                <div className="col-lg-6"><input type="text" className="form-control" ref="phone" defaultValue={intern.phone} /></div>
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
                <div id="gradDate" className="col-lg-6"><p className="form-control-static">
                {student.grad_date == null ? <span className="text-muted"><em>Not Available</em></span>
                                           : student.grad_date
                }
                </p></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="credit-hours">Credit Hours</label>
                <div id="credit-hours" className="col-lg-6"><p className="form-control-static">
                {student.enrolled_credit_hours == null ? <span className="text-muted"><em>Not Available</em></span>
                                           : student.enrolled_credit_hours
                }
                </p></div>
              </div>
            </fieldset>
        );
    }
});

var StateDropDown = React.createClass({
    render: function() {
        if (this.props.active == 1 && this.props.stuState == this.props.sAbbr){
          return <option value={this.props.sAbbr} selected>{this.props.stateName}</option>
        }else {
          return <option value={this.props.sAbbr}>{this.props.stateName}</option>
        }
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
    grabStatusData: function(form) {
        var status = { status:  form.elements.workflowOption.value, 
                        oied:    form.elements.oiedCert.checked}

        return status;
    },
    render: function() {
        var status = this.props.workflow.status;
        var workflowAction = this.props.workflow.workflowAction;
        var oiedAllow = this.props.workflow.allow;

/****************
   NOT DONE!!!  *
 ****************/

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
                        if(key == "Intern\\WorkflowTransition\\LeaveTransition"){     
                          return(<div className="radio" key={key}>
                                    <label><input type="radio" name="workflowOption" value={key} defaultChecked/>{workflowAction[key]}</label>
                                 </div>)
                        } else {
                          return(<div className="radio" key={key}>
                                    <label><input type="radio" name="workflowOption" value={key} />{workflowAction[key]}</label>
                                 </div>)
                        }
                    })}
                    
                </div>
              </div>
              <div className="form-group">
                <div className="col-lg-10">
                  <div className="checkbox">
                    { oiedAllow ? <label><input type="checkbox" name="oiedCert" />Certified by Office of International Education and Development</label>
                                : <label><input type="checkbox" disabled />Certified by Office of International Education and Development</label>
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
        if (this.props.deptNumber !== '' && this.props.facultyID != null)
        {
          this.setState({facultyID: this.props.facultyID, showDetails: true}, this.props.getFacultyData(this.props.deptNumber));
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
    grabFacultyData: function() {
        var faculty = {faculty_id: this.state.facultyID};
        return faculty;
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
console.log(deptNum);
        //FIX DEPT NAME
        if(facultyData != null){
          //console.log("Made it")
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
    componentWillMount: function() {
        this.props.getFacultyData(this.props.deptNumber);
    },
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
        var name = this.props.fname + " " + this.props.lname + " - " + this.props.deptname;


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

var CourseAndTerm = React.createClass({
    grabCourseAndTerm: function(form) {
        var courseTerm = {termStart:    this.refs.startDate.value,
                          termEnd:      this.refs.endDate.value,
                          courseSubj:   this.refs.courseSubj.value,
                          courseNum:    this.refs.courseNum.value,
                          section:      this.refs.courseSect.value,
                          creditHours:  this.refs.courseCH.value,
                          title:        this.refs.courseTitle.value};
        return courseTerm;
    },
    render: function() {
        var intern = this.props.intern;
        var subjects = this.props.subjects;
        return(
          <div>
          <fieldset>
              <legend>Term & Course Information</legend>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="campus">Term</label>
                <div id="campus" className="col-lg-6"><p className="form-control-static">{intern.term}</p></div>
              </div>


              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{START_DATE_ID}">Term Start Date</label>
                <div className="col-lg-6"><input type="text" className="form-control" ref="startDate" defaultValue={intern.start_date} /></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{END_DATE_ID}">Term End Date</label>
                <div className="col-lg-6"><input type="text" className="form-control" ref="endDate" defaultValue={intern.end_date} /></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" htmlFor="{STUDENT_STATE_ID}">Course Subject</label>
                <div className="col-lg-6">
                  <select className="form-control" ref="courseSubj">
                  {Object.keys(subjects).map(function (key) {
                      if ((intern.course_subj === key) || (intern.course_subj === null && key == -1)){
                        return <option key={key} value={key} selected>{subjects[key]}</option>
                      }else {
                        return <option key={key} value={key} >{subjects[key]}</option>
                      }
                    }.bind(this))
                  }
                  </select>
                </div>
              </div>


              <div className="form-group">
                <label className="col-lg-3 control-label" for="{COURSE_NO_ID}">Course Number</label>
                <div className="col-lg-6"><input type="text" className="form-control" ref="courseNum" defaultValue={intern.course_no} /></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" for="{COURSE_SECT_ID}">Section</label>
                <div className="col-lg-6"><input type="text" className="form-control" ref="courseSect" defaultValue={intern.course_sect} /></div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" for="{CREDITS_ID}">Credit Hours</label>
                <div className="col-lg-6"><input type="text" className="form-control" ref="courseCH" defaultValue={intern.credits} />
                   <span className="help-block"><small className="text-muted">Decimal values will be rounded.</small></span>
                </div>
              </div>

              <div className="form-group">
                <label className="col-lg-3 control-label" for="{CREDITS_ID}">Title</label>
                <div className="col-lg-6"><input type="text" className="form-control" ref="courseTitle" defaultValue={intern.course_title} />
                   <span className="help-block"><small className="text-muted">(Limit 28 characters; Banner)</small></span>
                </div>
              </div>
              </fieldset>
          </div>
        );
    }
});


var TypeInterface = React.createClass({
    getInitialState: function() {
        return {showModal: false};
    },
    closeModal: function() {
        this.setState({ showModal: false });
    },
    openModal: function() {
        this.setState({ showModal: true });
    },
    grabTypeData: function(form) {
        var type = { type:  form.elements.typeOption.value}

        return type;
    },
    render: function() {
        var expType = this.props.experience_type;
        return(
          <div>
            <fieldset>
              <legend>Type</legend>
              <div className="form-group">
                <div className="col-lg-5 col-lg-offset-3">
                  {Object.keys(expType).map(function(key) {
                        if(key === "internship"){            
                          return(<div className="radio" key={key}>
                                    <label><input type="radio" name="typeOption" value={key} defaultChecked/>{expType[key]}</label>
                                 </div>)
                        } else {
                          return(<div className="radio" key={key}>
                                    <label><input type="radio" name="typeOption" value={key}/>{expType[key]}</label>
                                 </div>)
                        }
                    })}
                </div>
                <div className="col-lg-4">
                  <a id="internship-type-help-button" className="pull-right" onClick={this.openModal}><i className="fa fa-question-circle"></i> Type Definitions</a>
                  <TypeModalForm show={this.state.showModal} hide={this.closeModal} />
                </div>
              </div>
            </fieldset>
          </div>
        );
    }
});

var TypeModalForm = React.createClass({
    render: function() {
        return (
            <ReactBootstrap.Modal show={this.props.show} onHide={this.props.hide} backdrop='static'>
                <ReactBootstrap.Modal.Header closeButton>
                  <ReactBootstrap.Modal.Title><h2>Internship Type Definitions</h2></ReactBootstrap.Modal.Title>
                </ReactBootstrap.Modal.Header>
                <ReactBootstrap.Modal.Body>
                    <form className="form-horizontal">
                        <div className="form-group">
                            <div className="col-lg-12">
                            <h3>Student Teaching</h3>
                            <p>A course requiring students to instruct or teach at an entity external to the institution, generally as part of the culminating curriculum of a teacher education or certificate program.</p>

                            <h3>Practicum</h3>
                            <p>A course requiring students to participate in an approved project or proposal that practically applies previously studied theory of the field or discipline under the supervision of an expert or qualified representative of the field or discipline.</p>

                            <h3>Clinical</h3>
                            <p>A course requiring medical- or healthcare-focused experiential work where students test, observe, experiment, or practice a field or discipline in a hands-on or simulated environment.</p>

                            <h3>Internship</h3>
                            <p>A course requiring students to participate in a partnership, professional employment, work experience or cooperative education with any entity external to the institution, generally under the supervision of an employee of the external entity.</p>
                          </div>
                        </div>
                    </form>
                </ReactBootstrap.Modal.Body>
                <ReactBootstrap.Modal.Footer>
                    <ReactBootstrap.Button onClick={this.props.hide}>Close</ReactBootstrap.Button>
                </ReactBootstrap.Modal.Footer>
            </ReactBootstrap.Modal>
        );
    }
});

var NoteBox = React.createClass({
    grabNoteData: function() {

    },
    render: function() {
        return(

          <div>
            <div className="form-group">
              <div className="col-lg-10">
                <label>Add a note</label> 
              </div>
            </div>

            <div className="form-group">
              <div className="col-lg-10">
              <textarea className="form-control" style={{resize:"vertical"}} rows="3"></textarea>
              </div>
            </div>

            <div className="form-group">
              <div className="col-lg-10">
              <button type="submit" className="btn btn-primary pull-right" id="{SUBMIT_ID}">Save</button>
              </div>
            </div>
      
          </div>

        );
    }
});

var ChangeLog = React.createClass({
    render: function() {
        return(
          <div className="row">
            <div className="col-lg-8">
              <h3>Change History</h3>
                {this.props.noteData.map(function (notes) {
                  return (
                          <ChangeFields key         ={notes.id}
                                        exactDate   ={notes.exact_date}
                                        fromState   ={notes.from_state}
                                        toState     ={notes.to_state}
                                        username    ={notes.username}
                                        relativeData={notes.relative_date}
                                        note        ={notes.note} />
                      );
                }.bind(this))}
            </div>
          </div>
        );
    }
});

var ChangeFields = React.createClass({
    render: function() {
        return(
            <div id="changelog">
              <div className="change">
                <h3 className="change">
                  <span className="exact-date">{this.props.exactDate}</span>Changed {this.props.relativeData} ago by {this.props.username}
                </h3>
                {this.props.fromState != undefined ?
                <ul>
                  <li>Status changed from <strong>{this.props.fromState}</strong> to <strong>{this.props.toState}</strong>
                  </li>
                </ul>
                : null}
                <div className="comment">{this.props.note}</div>
              </div>             
            </div>
        );
    }
});

var DropzoneDemo = React.createClass({
    onDrop: function (files) {
      console.log('Received files: ', files);
    },

    render: function () {
      return (
          <div>
            <Dropzone onDrop={this.onDrop}>
              <div>Try dropping some files here, or click to select files to upload.</div>
            </Dropzone>
          </div>
      );
    }
});




// <--------------------------------------------Host Information Section---------------------------------------------->

var HostInterface = React.createClass({
    buildHostData: function(form) {
        var location     = this.refs.location.grabLocationData();
        var compensation = this.refs.compensation.grabCompensationData(form);
        var contract     = this.refs.contract.grabContractData();
        var hostDetails  = this.refs.hDetails.grabHostData();
        var superDetails = this.refs.sDetails.grabSupervisorData();

        var hostData = {location:     location,
                          compensation: compensation,
                          contract:     contract,
                          hostDetails:  hostDetails,
                          superDetails: superDetails};

        return hostData;
    },
    render: function() {
        var hostData = this.props.hostData;
        var intern = this.props.intern;
        var docs = this.props.docs;
        return(
          <div className="panel panel-default">
            <div className="panel-heading">
              <h3 className="panel-title">Host Information: {hostData.name}</h3>
            </div>
            <div className="panel-body">
                <div className="row">
                  <div className="col-lg-6">
                    <Location hostData = {hostData} domestic = {intern.domestic} ref = "location"/>
                    <Compensation hostData = {hostData}
                                  intern   = {intern}
                                  ref      = "compensation" />
                    <Contracts title = "Contracts" docs = {docs} ref = "contract"/>
                  </div>
                  <div className="col-lg-6">
                    <HostDetails hostData = {hostData}
                                 states   = {this.props.states}
                                 domestic = {intern.domestic} 
                                 ref      = "hDetails" />
                    <SupervisorInfo hostData = {hostData} 
                                    domestic = {intern.domestic} 
                                    states   = {this.props.states}
                                    ref = "sDetails"/>
                  </div>
                </div>
            </div>
          </div>
        );
    }
});

var Location = React.createClass({
    grabLocationData: function() {
        var locationData = {
            loc_address:  this.refs.locAddress.value,
            loc_city:     this.refs.locCity.value,
            loc_zip:      this.refs.locZip.value,
            loc_start:    this.refs.locStart.value,
            loc_end:      this.refs.locEnd.value
        };

        return locationData;
    },
    render: function() {
        var hostData = this.props.hostData;
        var domestic = this.props.domestic;

        if(domestic){
            var locState = "State";
            var zip      = "Zip";
        } else {
            var locState = "Country";
            var zip      = "Postal Code";
        }
        return(
          <div>
            <fieldset>
                <legend>Location</legend>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="campus">Location</label>
                  <div id="campus" className="col-lg-6"><p className="form-control-static">{hostData.international}</p></div>
                </div>

                <div className="checkbox">
                  <label><input type="checkbox" value="" />Location's information is same as Internship's</label>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" for="{COURSE_SECT_ID}">Address</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="locAddress" defaultValue={hostData.loc_address} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" for="{COURSE_SECT_ID}">City</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="locCity" defaultValue={hostData.loc_city} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="campus">{locState}</label>
                  <div id="campus" className="col-lg-6"><p className="form-control-static">{hostData.loc_state}</p></div>
                </div>

                {!domestic ? <div className="form-group">
                              <label className="col-lg-3 control-label" htmlFor="campus">Province/Territory</label>
                              <div id="campus" className="col-lg-6"><p className="form-control-static">{hostData.loc_province}</p></div>
                            </div>
                          : null}


                <div className="form-group">
                  <label className="col-lg-3 control-label" for="{COURSE_SECT_ID}">{zip}</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="locZip" defaultValue={hostData.loc_zip} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" for="{COURSE_SECT_ID}">Location Start Date on Site</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="locStart" defaultValue={hostData.loc_start} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" for="{COURSE_SECT_ID}">Location End Date on Site</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="locEnd" defaultValue={hostData.loc_end} /></div>
                </div>
            </fieldset>
          </div>
        );
    }
});



var Compensation = React.createClass({
    getInitialState: function() {
        return {
            paid: false,
            stipend: false
        };
    },
    componentWillMount: function() {
        var intern = this.props.intern;
        if(intern.paid == true){
            this.setState({paid: true});
        }

        if(intern.stipend == true){
            this.setState({stipend: true});
        }
    },
    grabCompensationData: function(form) {

        var compensationData = {
            paid:           this.state.paid,
            stipend:        this.state.stipend,
            pay_rate:       this.refs.payRate.value,
            avg_hours_week: this.refs.hoursPerWeek.value
        };

        return compensationData;
    },
    changePaid: function(e) {
        this.setState({paid: e.currentTarget.value});
    },
    changeStipend: function(e) {
        this.setState({stipend: e.currentTarget.checked});
    },
    render: function() {
        var hostData = this.props.hostData;
        var intern = this.props.intern;
        var allow = intern.stipend;
        var rButtons;
        var rName = this.props.hostData.id;
        if(this.state.paid == false){ 
          rButtons = <div className="radio">
                     <label className="radio-inline"><input type="radio" name={rName} value="false" onChange={this.changePaid} defaultChecked />Unpaid</label>
                     <label className="radio-inline"><input type="radio" name={rName} value="true" onChange={this.changePaid}/>Paid</label>
                     </div>
        } else {
          rButtons = <div className="radio">
                     <label className="radio-inline"><input type="radio" name={rName} value="false" onChange={this.changePaid} />Unpaid</label>
                     <label className="radio-inline"><input type="radio" name={rName} value="true" onChange={this.changePaid} defaultChecked />Paid</label>
                     </div>
        }

        return(
          <div>
            <fieldset>
                <legend>Compensation</legend>
                <div className="form-group">
                  <div className="col-lg-6 col-lg-offset-3">

                    {rButtons}

                    <div className="checkbox">
                      { this.state.stipend ? <label><input type="checkbox" onChange={this.changeStipend} checked/>Stipend</label>
                                           : <label><input type="checkbox" onChange={this.changeStipend} />Stipend</label>
                      }
                    </div>
                  </div>
                </div>
                
                <div className="form-group">
                  <label className="col-lg-3 control-label" for="{COURSE_SECT_ID}">Pay rate</label>
                  <div className="col-lg-3"><input type="text" className="form-control" ref="payRate" defaultValue={intern.pay_rate} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" for="{COURSE_SECT_ID}">Average Hours per Week</label>
                  <div className="col-lg-3"><input type="text" className="form-control" ref="hoursPerWeek" defaultValue={intern.avg_hours_week} /></div>
                </div>

            </fieldset>
          </div>
        );
    }
});

var Contracts = React.createClass({
    grabContractData: function() {

    },
    render: function() {
      change = undefined;
        return(
          <div>
            <fieldset>
                <legend>{this.props.title}</legend>
                <div className="row">
                  <div className="col-lg-9">
                  {change != undefined ?
                    <ul className="list-group">
                      <li className="list-group-item"><i className="fa fa-file"></i> DOWNLOAD &nbsp;DELETE</li>
                    </ul>
                    : null
                  }
                  </div>
                  <div className="col-lg-2">
                    <button type="button" className="btn btn-default btn-sm" ><i className="fa fa-upload"></i> label</button>
                  </div>

                  
                </div>
            </fieldset>
          </div>
        );
    }
});

var HostDetails = React.createClass({
    grabHostData: function() {
        var hostData = { 
                name:    this.refs.name.value, 
                phone:   this.refs.phone.value,
                address: this.refs.address.value,
                city:    this.refs.city.value,
                state:   this.refs.state.value,
                zip:     this.refs.zip.value
                }

        return hostData;
    },
    render: function() {
        var hostData = this.props.hostData;
        var stateData = '';

        if(this.props.states != null){
            stateData = this.props.states.map(function (state) {
                  return (
                          <StateDropDown  key={state.abbr}
                                          sAbbr={state.abbr}
                                          stateName={state.full_name}
                                          active={hostData.state} />
                      );
              }.bind(this));
        }
        return(
          <div>
            <fieldset>
                <legend>Host Details</legend>
                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_ADDRESS_ID}">Host Name</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="name" defaultValue={hostData.name} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_ADDRESS_ID}">Phone</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="phone" defaultValue={hostData.phone} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_ADDRESS_ID}">Address</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="address" defaultValue={hostData.address} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_CITY_ID}">City</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="city" defaultValue={hostData.city} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_STATE_ID}">State</label>
                  <div className="col-lg-6">
                    <select className="form-control" ref="state" onChange={this.handleDrop}>{stateData}</select></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_ZIP_ID}">Zip Code</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="zip" defaultValue={hostData.zip} /></div>
                </div>
            </fieldset>
          </div>
        );
    }
});

var SupervisorInfo = React.createClass({
    grabSupervisorData: function() {
        var superData = { 
                fname:   this.refs.fname.value, 
                lname:   this.refs.lname.value,
                title:   this.refs.title.value,
                email:   this.refs.email.value,
                fax:     this.refs.fax.value,
                phone:   this.refs.phone.value,
                address: this.refs.address.value, 
                city:    this.refs.city.value,
                state:   this.refs.state.value,
                zip:     this.refs.zip.value
                }

        return superData;
    },
    render: function() {
        var hostData = this.props.hostData;
        var stateData = '';

        if(this.props.states != null){
            stateData = this.props.states.map(function (state) {
                  return (
                          <StateDropDown  key={state.abbr}
                                          sAbbr={state.abbr}
                                          stateName={state.full_name}
                                          active={hostData.state} />
                      );
              }.bind(this));
        }
        return(
          <div>
            <fieldset>
                <legend>Supervisor Information</legend>
                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_MIDDLE_NAME_ID}">First Name</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="fname" defaultValue={hostData.supervisor_first_name} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_MIDDLE_NAME_ID}">Last Name</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="lname" defaultValue={hostData.supervisor_last_name} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_MIDDLE_NAME_ID}">Title</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="title" defaultValue={hostData.supervisor_title} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_MIDDLE_NAME_ID}">Email</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="email" defaultValue={hostData.supervisor_email} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_MIDDLE_NAME_ID}">Fax</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="fax" defaultValue={hostData.supervisor_fax} /></div>
                </div>

                <div className="checkbox">
                  <label><input type="checkbox" value="" />Supervisor's information is same as Host's</label>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_ADDRESS_ID}">Phone</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="phone" defaultValue={hostData.supervisor_phone} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_ADDRESS_ID}">Address</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="address" defaultValue={hostData.supervisor_address} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_CITY_ID}">City</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="city" defaultValue={hostData.supervisor_city} /></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_STATE_ID}">State</label>
                  <div className="col-lg-6">
                    <select className="form-control" ref="state" onChange={this.handleDrop}>{stateData}</select></div>
                </div>

                <div className="form-group">
                  <label className="col-lg-3 control-label" htmlFor="{STUDENT_ZIP_ID}">Zip Code</label>
                  <div className="col-lg-6"><input type="text" className="form-control" ref="zip" defaultValue={hostData.supervisor_zip} /></div>
                </div>
            </fieldset>
          </div>
        );
    }
});

ReactDOM.render(
    <EditInternshipInterface />, 
    document.getElementById('editInternshipInterface')
);