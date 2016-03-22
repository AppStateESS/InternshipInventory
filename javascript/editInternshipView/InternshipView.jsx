
var EditInternshipInterface = React.createClass({
    getInitialState: function() {
        return {
            internData: null,
            stateData: null
        };
    },
    componentWillMount: function(){
        this.getInternData();
        this.getStates();
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
    render: function() {
        if(this.state.internData != null){
            var interface = <StudentInformation intern = {this.state.internData.intern}
                                                student = {this.state.internData.student}
                                                states = {this.state.stateData} />
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
            <form className="form-horizontal">
              <div className="row">
           
                <div className="col-lg-6">
        
                  <fieldset>
                    <legend>Student</legend>
                      <div className="form-group">
                        <label className="col-lg-3 control-label">Banner Id</label>
                        <div id="bannerid" className="col-lg-6"><p className="form-control-static">{intern.banner}</p></div>
                      </div>

                      <div className="form-group required">
                        <label className="col-lg-3 control-label">First Name</label>
                        <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.first_name} /></div>
                      </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_MIDDLE_NAME_ID}">Middle Name/Initial</label>
                      <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.middle_name} /></div>
                    </div>

                    <div className="form-group required">
                      <label className="col-lg-3 control-label" for="{STUDENT_LAST_NAME_ID}">Last Name</label>
                      <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.last_name} /></div>
                    </div>

                    <div className="form-group required">
                      <label className="col-lg-3 control-label" for="{STUDENT_EMAIL_ID}">ASU Email</label>
                      <div className="col-lg-6">
                        <div className="input-group">
                          <input type="text" className="form-control" defaultValue={intern.email} /><span className="input-group-addon">@appstate.edu</span>
                        </div>
                      </div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="birthdate">Birth date</label>
                      <div id="birthdate" className="col-lg-6"><p className="form-control-static">{intern.birth_date}</p></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_ADDRESS_ID}">Address</label>
                      <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.student_address} /></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_CITY_ID}">City</label>
                      <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.student_city} /></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_STATE_ID}">State</label>
                      <div className="col-lg-6">
                        <select className="form-control" onChange={this.handleDrop}>{stateData}</select></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_ZIP_ID}">Zip Code</label>
                      <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.student_zip} /></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_PHONE_ID}">Phone</label>
                      <div className="col-lg-6"><input type="text" className="form-control" defaultValue={intern.phone} /></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_GPA_ID}">GPA</label>
                      <div className="col-lg-6"><p className="form-control-static">{intern.gpa}</p></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="campus">Campus</label>
                      <div id="campus" className="col-lg-6"><p className="form-control-static">{intern.campus}</p></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="level">Level</label>
                      <div id="level" className="col-lg-6"><p className="form-control-static">{intern.level}</p></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{UGRAD_MAJOR_ID}{GRAD_MAJOR_ID}">Major / Program</label>

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
                      <label className="col-lg-3 control-label" for="gradDate">Graduation Date</label>
                      <div id="gradDate" className="col-lg-6"><p className="form-control-static">{student.grad_date}</p></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="credit-hours">Credit Hours</label>
                      <div id="credit-hours" className="col-lg-6"><p className="form-control-static">{student.enrolled_credit_hours}</p></div>
                    </div>
                  </fieldset>
                </div>
              </div> 
            </form>
        );
    }
});

var StateDropDown = React.createClass({
    render: function() {
        var optionSelect = <div />
        if (this.props.active == 1 && this.props.stuState == this.props.sAbbr){
          optionSelect = <option value={this.props.sAbbr} selected>{this.props.stateName}</option>
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
                                <input type="radio" name="major_code" autocomplete="off" value="{this.props.code}"  defaultChecked /> {this.props.desc}
                              </label>
        }else{
            var majorSelect = <label className={activeButton}>
                                <input type="radio" name="major_code" autocomplete="off" value="{this.props.code}" /> {this.props.desc}
                              </label>
        }
        return (   
            majorSelect
        );
    }
});

React.render(
    <EditInternshipInterface />, document.getElementById('editInternshipInterface')
);