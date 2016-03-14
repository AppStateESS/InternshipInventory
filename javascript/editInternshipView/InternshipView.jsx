
var EditInternshipInterface = React.createClass({
    render: function() {
        return (
            <div>
                <StudentInformation />
            </div>
        );
    }
});

var StudentInformation = React.createClass({
    render: function() {
        return (
            <form className="form-horizontal">
              <div className="row">
           
                <div className="col-lg-6">
        
                  <fieldset>
                    <legend>Student</legend>
                      <div className="form-group">
                        <label className="col-lg-3 control-label">Banner Id</label>
                        <div id="bannerid" className="col-lg-6"><p className="form-control-static">temp</p></div>
                      </div>

                      <div className="form-group required">
                        <label className="col-lg-3 control-label">First Name</label>
                        <div className="col-lg-6"><input type="text" className="form-control" /></div>
                      </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_MIDDLE_NAME_ID}">Middle Name/Initial</label>
                      <div className="col-lg-6"><input type="text" className="form-control" /></div>
                    </div>

                    <div className="form-group required">
                      <label className="col-lg-3 control-label" for="{STUDENT_LAST_NAME_ID}">Last Name</label>
                      <div className="col-lg-6"><input type="text" className="form-control" /></div>
                    </div>

                    <div className="form-group required">
                      <label className="col-lg-3 control-label" for="{STUDENT_EMAIL_ID}">ASU Email</label>
                      <div className="col-lg-6">
                        <div className="input-group">
                          <input type="text" className="form-control" /><span className="input-group-addon">@appstate.edu</span>
                        </div>
                      </div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="birthdate">Birth date</label>
                      <div id="birthdate" className="col-lg-6"><p className="form-control-static">temp</p></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_ADDRESS_ID}">Address</label>
                      <div className="col-lg-6"><input type="text" className="form-control" /></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_CITY_ID}">City</label>
                      <div className="col-lg-6"><input type="text" className="form-control" /></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_STATE_ID}">State</label>
                      <div className="col-lg-6"></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_ZIP_ID}">Zip Code</label>
                      <div className="col-lg-6"><input type="text" className="form-control" /></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_PHONE_ID}">Phone</label>
                      <div className="col-lg-6"><input type="text" className="form-control" /></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="{STUDENT_GPA_ID}">GPA</label>
                      <div className="col-lg-6"><p className="form-control-static">temp</p></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="campus">Campus</label>
                      <div id="campus" className="col-lg-6"><p className="form-control-static">temp</p></div>
                    </div>

                    <div className="form-group">
                      <label className="col-lg-3 control-label" for="level">Level</label>
                      <div id="level" className="col-lg-6"><p className="form-control-static">temp</p></div>
                    </div>
                  </fieldset>
                </div>
              </div> 
            </form>
        );
    }
});


React.render(
    <EditInternshipInterface />, document.getElementById('editInternshipInterface')
);