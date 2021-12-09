<script type="text/javascript">
    $(document).ready(function() {
        // Setup date pickers
        $("#internship_start_date").datepicker();
        $("#internship_end_date").datepicker();
    });
</script>

<script type="text/javascript">
    var internship = {INTERNSHIP_JSON};
</script>

<h1>
  <i class="fa fa-edit"></i> Edit Internship
</h1>

<form class="form-horizontal {FORM_CLASS}" id="{FORM_ID}" action="{FORM_ACTION}" autocomplete="{FORM_AUTOCOMPLETE}" method="{FORM_METHOD}"{FORM_ENCODE}>
  {HIDDEN_FIELDS}

  <div class="form-group">
    <div class="col-lg-1 col-lg-offset-6">
      <button type="submit" class="btn btn-primary" id="{SUBMIT_ID}">{SUBMIT_VALUE}</button>
    </div>

    <div class="col-lg-1">
      <!-- BEGIN delete_btn -->
      <a href="{DELETE_URL}" class="btn btn-danger-hover" onclick="return confirm('Are you sure you want to delete this internship?');">Delete</a>
      <!-- END delete_btn -->
    </div>

    <div class="col-lg-2">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-copy"></i> Continue This Internship <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <!-- BEGIN CONTINUE_TERM_LIST -->
                <li><a href="index.php?module=intern&action=copyInternshipToNextTerm&internshipId={INTERN_ID}&destinationTerm={DEST_TERM}"><i class="fa fa-fast-forward"></i> Continue in {DEST_TERM_TEXT}</a></li>
                <!-- END CONTINUE_TERM_LIST -->

                <!-- BEGIN CONTINUE_TERM_NO_TERMS -->
                <li><a href="" class="text-muted" style="color:#777; pointer-events: none" disabled>{CONTINUE_TERM_NO_TERMS}</a></li>
                <!-- END CONTINUE_TERM_NO_TERMS -->
            </ul>
        </div>
    </div>

    <div class="col-lg-1 col-lg-offset-1">
      <button type="button" id="contract-button" class="btn btn-default pull-right generateContract"><i class="fa fa-file"></i> Generate Contract</button>
    </div>
  </div>

  <div class="row">
    <!-- Left column -->
    <div class="col-lg-6">
      <!-- Student info section -->
      <fieldset>
        <legend>Student</legend>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="bannerid">Banner Id</label>
          <div id="bannerid" class="col-lg-6"><p class="form-control-static">{BANNER}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_FIRST_NAME_ID}">First Name</label>
          <div class="col-lg-6"><p class="form-control-static">{STUDENT_FIRST_NAME}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_MIDDLE_NAME_ID}">Middle Name/Initial</label>
          <div class="col-lg-6"><p class="form-control-static">{STUDENT_MIDDLE_NAME}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_LAST_NAME_ID}">Last Name</label>
          <div class="col-lg-6"><p class="form-control-static">{STUDENT_LAST_NAME}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_PREFERRED_NAME_ID}">{STUDENT_PREFERRED_NAME_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_PREFERRED_NAME}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_EMAIL_ID}">ASU Email</label>
          <div class="col-lg-6"><p class="form-control-static">{STUDENT_EMAIL}@appstate.edu</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_PHONE_ID}">{STUDENT_PHONE_LABEL_TEXT}</label>
          <div class="col-lg-6">{STUDENT_PHONE}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{STUDENT_GPA_ID}">GPA</label>
          <div class="col-lg-6"><p class="form-control-static">{STUDENT_GPA}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="campus">Campus</label>
          <div id="campus" class="col-lg-6"><p class="form-control-static">{CAMPUS}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="level">Level</label>
          <div id="level" class="col-lg-6"><p class="form-control-static">{LEVEL}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{UGRAD_MAJOR_ID}{GRAD_MAJOR_ID}">Major / Program</label>

          <!-- BEGIN oneMajor -->
          <div class="col-lg-8"><p class="form-control-static">{MAJOR}</p></div>
          <!-- END oneMajor -->

          <div class="col-lg-8">
            <div class="btn-group-vertical" data-toggle="buttons" role="group" aria-label="major selector">
              <!-- BEGIN majors_repeat -->
              <label class="btn btn-default {ACTIVE}">
                <input type="radio" name="major_code" autocomplete="off" value="{CODE}" {CHECKED}> {DESC}
              </label>
              <!-- END majors_repeat -->
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="gradDate">Graduation Date</label>
          <div id="gradDate" class="col-lg-6"><p class="form-control-static">{GRAD_DATE}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="credit-hours">Credit Hours</label>
          <div id="credit-hours" class="col-lg-6"><p class="form-control-static">{ENROLLED_CREDIT_HORUS}</p></div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{BACKGROUND_CHECK_ID}">Background Check Needed?</label>
          <div class="col-lg-6">
              <div class="col-lg-8 col-lg-offset-3">
                <div class="checkbox">
                  <label>{BGCHECK}&nbsp;{BGCHECK_LABLE_TEXT}</label>
                </div>
              </div>
            <!-- BEGIN back_check -->
              <button type="button" class="btn btn-default" name="background_code" id="back_check_id" value="0">
                {BACK_CHECK_REQUEST_BTN}
              </button>
              <!-- END back_check -->
              <!-- BEGIN back_check_req -->
              <button type="button" class="btn btn-default" name="background_code" id="back_check_id" value="1" disabled>
                {BACK_CHECK_REQUESTED_BTN}
              </button>
              <!-- END back_check_req -->
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{BACKGROUND_CHECK_ID}">Drug Test Needed?</label>
          <div class="col-lg-6">
              <div class="col-lg-8 col-lg-offset-3">
                <div class="checkbox">
                  <label>{DCHECK}&nbsp;{DCHECK_LABLE_TEXT}</label>
                </div>
              </div>
              <!-- BEGIN drug_check -->
              <button type="button" class="btn btn-default" name="drug_code" id="drug_check_id" value="0">
                {DRUG_CHECK_REQUEST_BTN}
              </button>
              <!-- END drug_check -->
              <!-- BEGIN drug_check_req -->
              <button type="button" class="btn btn-default" name="drug_code" id="drug_check_id" value="1" disabled>
                {DRUG_CHECK_REQUESTED_BTN}
              </button>
              <!-- END drug_check_req -->
          </div>
      </div>

      </fieldset>

      <!-- Emergency Contact Info -->
      <fieldset>
          <legend>Emergency Contacts</legend>
          <div class="row">
              <!-- React Emergency Contact -->
              <div class="col-md-12">
                  <div id="emergency-contact-list"></div>
              </div>

          </div>
      </fieldset>

      <fieldset>
        <legend>Location</legend>
        <p>
          <span class="help-block">Physical Location of Internship</span>
        </p>

        <div class="form-group">
          <div class="col-lg-3 control-label">
            <label>Location</label>
          </div>
          <div class="col-lg-6">
            <p class="form-control-static">{LOCATION}</p>
          </div>
        </div>
        <!-- Host info -->
        <fieldset>

            <div class="col-lg-8 col-lg-offset-3">
              <div class="checkbox">
                <label>{REMOTE}&nbsp;{REMOTE_LABEL_TEXT} </label><a href="#remoteModal" id="internship-remote-help-button" class="pull-right"  data-toggle="modal"><i class="fa fa-question-circle"></i> Explanation</a>
              </div>
            </div>
            <!-- BEGIN remote-state -->
            <div class="form-group" id="internship_remote_state_label">
                <label class="col-lg-3 control-label" for="{REMOTE_STATE_ID}">Remote State</label>
                <div id="internship_remote_state" class="col-lg-6"><p class="form-control-static">{REMOTE_STATE}</p></div>
            </div>
            <!-- END remote-state -->

            <!-- Informational Modal -->
            <div id ="remoteModal" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class = "modal-content">
                  <div class = "modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2>Remote Internship Explanation</h2>
                  </div>
                  <div class="modal-body">
                    <div id="internship-remote-help">
                      <h3>When do I need to click this?</h3>
                      <p>Select the checkbox when the student is working remotely, such as from their house. As most remote locations are students working from home, and we do not want to record students' home address, we are only collecting the state for reporting purposes.</p>

                      <h3>What if it's a different country?</h3>
                      <p>As working remotely from another country that is not the USA is rare, we have not added that feature. If you have a student working remotely in a country that is not America, please check the remote box and list the details in the notes section.</p>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary pull-right" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- BEGIN host-state -->
            <div class="form-group">
                <label class="col-lg-3 control-label" for="{HOST_STATE_ID}">State</label>
                <div id="internship_host_state" class="col-lg-6"><p class="form-control-static">{HOST_STATE}</p></div>
            </div>
            <!-- END host-state -->

            <div class="form-group">
                <label class="col-lg-3 control-label" for="{HOST_ID}">Host Name</label>
                <div class="col-lg-6"><p class="form-control-static">{HOST_NAME}</p></div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label" for="{HOST_SUB_ID}">Sub Name</label>
                <div class="col-lg-9"><p class="form-control-static">{SUB_NAME}</p></div>
            </div>
            <!-- Sub Contact Info -->
                <div class="row">
                    <!-- React sub-->
                    <div class="col-md-12">
                        <div id="sub-list"></div>
                    </div>

                </div>

            <div class="form-group">
                <label class="col-lg-3 control-label" for="{HOST_PHONE_ID}">Phone</label>
                <div class="col-lg-6">{HOST_PHONE}</div>
            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label" for="{HOST_ADDRESS_ID}">Address</label>
                <div id="internship_host_address" class="col-lg-6"><p class="form-control-static">{HOST_ADDRESS}</p></div>
            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label" for="{HOST_CITY_ID}">City</label>
                <div id="internship_host_city" class="col-lg-6"><p class="form-control-static">{HOST_CITY}</p></div>
            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label" for="{HOST_ZIP_ID}" id="internship_host_zip-label">{HOST_ZIP_LABEL_TEXT}</label>
                <div id="internship_host_zip" class="col-lg-6"><p class="form-control-static">{HOST_ZIP}</p></div>
            </div>

          <!-- BEGIN host-intl -->
          <div class="form-group">
            <label class="col-lg-3 control-label" for="{HOST_PROVINCE_ID}">Province/Territory</label>
            <div id="internship_host_province" class="col-lg-6"><p class="form-control-static">{HOST_PROVINCE}</p></div>
          </div>

          <div class="form-group">
            <label class="col-lg-3 control-label" for="{HOST_COUNTRY_ID}">Country</label>
            <div id="internship_host_country" class="col-lg-6"><p class="form-control-static">{HOST_COUNTRY}</p></div>
          </div>
          <!-- END host-intl -->

        </fieldset>

      <legend>Term Information</legend>
      <div class="form-group">
        <div class="col-lg-3 control-label">
            <label>Term</label>
          </div>
          <div class="col-lg-6">
            <p class="form-control-static">{TERM}</p>
          </div>
      </div>

      <div class="form-group">
          <div class="col-lg-6 col-lg-push-3"><small class="text-muted">{TERM_DATES}</small></div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{START_DATE_ID}">{START_DATE_LABEL_TEXT}</label>
        <div class="col-lg-6">{START_DATE}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{END_DATE_ID}">{END_DATE_LABEL_TEXT}</label>
        <div class="col-lg-6">{END_DATE}</div>
      </div>

      <legend>Course Information</legend>

      <!-- Link to Informational Modal -->
      <div class="col-lg-12">
        <a href="#partModal" id="internship-part-help-button" class="pull-right"  data-toggle="modal"><i class="fa fa-question-circle"></i> Multi-part Explanation</a>
      </div>

      <!-- Informational Modal -->
      <div id ="partModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class = "modal-content">
            <div class = "modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h2>Multi-part Experience Information</h2>
            </div>
            <div class="modal-body">
              <div id="internship-part-help">
                <h3>What is a Multi-part Experience?</h3>
                <p>If a student’s internship takes place on two or more sites in one term than it is considered a multi-part experience. Do not check the box if the internship takes place at only one site.</p>

                <h3>Why have a Multi-part checkbox?</h3>
                <p>An internship record is created for each of the sites in the multi-part experience. This is to ensure the following:
                    <ul>
                        <li>Each location moves through all the approval steps</li>
                        <li>Separate contracts can be generated and fully completed for each site</li>
                        <li>All information is properly gathered for the additional sites</li>
                    </ul></p>

                <h3>What is the secondary part?</h3>
                <p>The second site of the internship is called the secondary part. The secondary part requires a separate record from the first part and will include the secondary site location and start/end date.<br /><i>Note: ONLY check this box if it is the form for the secondary part. Boxes for course information will be grayed out upon checking the box indicating that the form is a secondary part. Course information should not be entered under the secondary part and should only be entered under the record for the first part of the multi-part experience. Leave boxes blank.</i></p>

                <h3>When are the credits entered?</h3>
                <p>All credits hours should be entered on the first part of the multi-part experience internship record. Do not fill in credit hours in on the secondary part.</p>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary pull-right" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="col-lg-8 col-lg-offset-3">
          <div class="checkbox">
            <label>{MULTIPART}&nbsp;{MULTIPART_LABEL_TEXT}</label>
          </div>
        </div>

        <div class="col-lg-8 col-lg-offset-3">
          <div class="checkbox">
            <label id="secondary-part-label">{SECONDARY_PART}&nbsp;{SECONDARY_PART_LABEL_TEXT}</label>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{COURSE_SUBJ_ID}">{COURSE_SUBJ_LABEL_TEXT}</label>
        <div class="col-lg-6">{COURSE_SUBJ}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{COURSE_NO_ID}">{COURSE_NO_LABEL_TEXT}</label>
        <div class="col-lg-6">{COURSE_NO}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{COURSE_SECT_ID}">{COURSE_SECT_LABEL_TEXT}</label>
        <div class="col-lg-6">{COURSE_SECT}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{CREDITS_ID}">{CREDITS_LABEL_TEXT}</label>
        <div class="col-lg-6">
          {CREDITS} <span class="help-block"><small class="text-muted">Decimal values will be rounded.</small></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{CREDITS_ID}">{COURSE_TITLE_LABEL_TEXT}</label>
        <div class="col-lg-6">
          {COURSE_TITLE} <span class="help-block"><small class="text-muted">(Limit 28 characters; Banner)</small></span>
        </div>
      </div>

      <!-- BEGIN corequisite -->
      <h4>Corequisite Course</h4>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{CREDITS_ID}">Course Number</label>
        <div class="col-lg-6">{COREQUISITE_COURSE_NUM}</div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 control-label" for="{CREDITS_ID}">Course Section</label>
        <div class="col-lg-6">{COREQUISITE_COURSE_SECT}</div>
      </div>
      <!-- END corequisite -->

    </div>
    <!-- End of left column -->


    <!-- Right Column -->
    <div class="col-lg-6">
      <!-- Status -->
      <fieldset>
        <legend>Status</legend>
        <p>
          Current Status: <strong>{WORKFLOW_STATE}</strong>
        </p>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">Next status</h4>
          </div>
          <div class="panel-body">

            <!-- BEGIN workflow_action_repeat -->
            <div class="radio">
              <label>{WORKFLOW_ACTION} {WORKFLOW_ACTION_LABEL}</label>
            </div>
            <!-- END workflow_action_repeat -->
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-10">
            <div class="checkbox">
              <label>{OIED_CERTIFIED} {OIED_CERTIFIED_LABEL}</label>
            </div>
          </div>
        </div>
      </fieldset>

      <!-- Faculty info -->
      <fieldset>
        <legend>Faculty Supervisor</legend>
        <div id="faculty_selector">
          <div class="form-group required">
            <label class="col-lg-3 control-label" for="{DEPARTMENT_ID}">{DEPARTMENT_LABEL_TEXT}</label>
            <div class="col-lg-8">{DEPARTMENT}</div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label" for="{FACULTY_ID}">{FACULTY_LABEL_TEXT}</label>
            <div class="col-lg-8">{FACULTY}</div>
          </div>
        </div>
        <div id="faculty_details">

          <div class="row">
            <div id="faculty_change" class="col-lg-2">
              <button type="button" id="faculty-change" class="btn btn-default btn-xs">
                <i class="fa fa-chevron-left"></i> change
              </button>
            </div>
            <div id="faculty_name" class="col-lg-10 lead"></div>
          </div>

          <div class="row">
            <div class="col-lg-5 col-lg-offset-2">

              <div class="row">
                <div class="col-lg-12">
                  <p>
                    <abbr title="Email address"><i class="fa fa-envelope"></i></abbr> &nbsp;<span id="faculty_email"></span>
                  </p>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <p>
                    <abbr title="Phone"><i class="fa fa-phone"></i></abbr> &nbsp;<span id="faculty_phone"></span>
                  </p>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <p>
                    <abbr title="Fax"><i class="fa fa-print"></i></abbr> &nbsp;<span id="faculty_fax"></span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-lg-5">
              <abbr title="Address"><i class="fa fa-map-marker"></i></abbr> &nbsp;
              <address id="faculty_address"></address>
            </div>
          </div>

        </div>
      </fieldset>

      <!-- Contract & Affiliation -->
      <fieldset>
          <legend> Contract &amp; Affiliation Agreements</legend>
          <div class="row">
              <!-- React Contract -->
              <div class="col-md-12">
                  <div id="contract-affiliation"></div>
              </div>
          </div>
      </fieldset>

      <!-- New Document List -->
      <fieldset>
          <legend>Other Documents</legend>
          <div class="row">
              <!-- React Contract -->
              <div class="col-md-12">
                  <div id="other-documents"></div>
              </div>
          </div>
      </fieldset>

      <fieldset>
        <legend>Supervisor Info</legend>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{SUPERVISOR_FIRST_NAME_ID}">{SUPERVISOR_FIRST_NAME_LABEL_TEXT}</label>
          <div class="col-lg-6">{SUPERVISOR_FIRST_NAME}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{SUPERVISOR_LAST_NAME_ID}">{SUPERVISOR_LAST_NAME_LABEL_TEXT}</label>
          <div class="col-lg-6">{SUPERVISOR_LAST_NAME}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{SUPERVISOR_TITLE_ID}">{SUPERVISOR_TITLE_LABEL_TEXT}</label>
          <div class="col-lg-6">{SUPERVISOR_TITLE}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{SUPERVISOR_EMAIL_ID}">{SUPERVISOR_EMAIL_LABEL_TEXT}</label>
          <div class="col-lg-6">{SUPERVISOR_EMAIL}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{SUPERVISOR_FAX_ID}">{SUPERVISOR_FAX_LABEL_TEXT}</label>
          <div class="col-lg-6">{SUPERVISOR_FAX}</div>
        </div>

        <div class="checkbox">
          <label> {COPY_ADDRESS} {COPY_ADDRESS_LABEL_TEXT} </label>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{SUPERVISOR_PHONE_ID}">{SUPERVISOR_PHONE_LABEL_TEXT}</label>
          <div class="col-lg-6">{SUPERVISOR_PHONE}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{SUPERVISOR_ADDRESS_ID}">{SUPERVISOR_ADDRESS_LABEL_TEXT}</label>
          <div class="col-lg-6">{SUPERVISOR_ADDRESS}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{SUPERVISOR_CITY_ID}">{SUPERVISOR_CITY_LABEL_TEXT}</label>
          <div class="col-lg-6">{SUPERVISOR_CITY}</div>
        </div>

        <!-- BEGIN supervisor-state -->
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{SUPERVISOR_STATE_ID}">{SUPERVISOR_STATE_LABEL_TEXT}</label>
          <div class="col-lg-6">{SUPERVISOR_STATE}</div>
        </div>
        <!-- END supervisor-state -->

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{SUPERVISOR_ZIP_ID}" id="internship_supervisor_sup_zip-label">{SUPERVISOR_ZIP_LABEL_TEXT}</label>
          <div class="col-lg-6">{SUPERVISOR_ZIP}</div>
        </div>

        <!-- BEGIN supervisor-intl -->
        <div class="form-group">
          <label class="col-lg-3 control-label" for="{SUPERVISOR_PROVINCE_ID}">{SUPERVISOR_PROVINCE_LABEL_TEXT}</label>
          <div class="col-lg-6">{SUPERVISOR_PROVINCE}</div>
        </div>
        <!-- END supervisor-intl -->

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{SUPERVISOR_COUNTRY_ID}">{SUPERVISOR_COUNTRY_LABEL_TEXT}</label>
          <div class="col-lg-6">{SUPERVISOR_COUNTRY}</div>
        </div>
      </fieldset>

      <fieldset>
        <legend>Compensation</legend>

        <div class="form-group">
          <div class="col-lg-6 col-lg-offset-3">
            <!-- BEGIN payment_repeat -->
            <label class="radio-inline"> {PAYMENT}{PAYMENT_LABEL_TEXT}</label>
            <!-- END payment_repeat -->

            <div class="checkbox">
              <label>{STIPEND}&nbsp;{STIPEND_LABEL_TEXT}</label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label" for="{PAY_RATE_ID}">Pay rate</label>
          <div class="col-lg-3">{PAY_RATE}</div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 col-lg-offset-1control-label" for="{AVG_HOURS_WEEK_ID}">{AVG_HOURS_WEEK_LABEL_TEXT}</label>
          <div class="col-lg-3">{AVG_HOURS_WEEK}</div>
        </div>
      </fieldset>
      <fieldset>
        <legend>Type</legend>
        <div class="form-group">
          <div class="col-lg-5 col-lg-offset-3">
            <!-- BEGIN experience_type_repeat -->
            <label class="radio"> {EXPERIENCE_TYPE} {EXPERIENCE_TYPE_LABEL} </label>
            <!-- END experience_type_repeat -->
          </div>

          <!-- Link to Informational Modal -->
          <div class="col-lg-4">
            <a href="#typeModal" id="internship-type-help-button" class="pull-right"  data-toggle="modal"><i class="fa fa-question-circle"></i> Type Definitions</a>
          </div>

          <!-- Informational Modal -->
          <div id ="typeModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class = "modal-content">
                <div class = "modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h2>Internship Type Definitions</h2>
                </div>
                <div class="modal-body">
                  <div id="internship-type-help">
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
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary pull-right" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </fieldset>

      <div class="row">
        <div class="col-lg-12">
          <div class="form-group print-hide">
            <label for="{NOTES_ID}">Add a note</label> {NOTES}
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary pull-right" id="{SUBMIT_ID}">{SUBMIT_VALUE}</button>
          </div>
        </div>
      </div>

    </div> <!-- End of right column -->
  </div> <!-- End of main row -->

  <div class="row">
    <div class="col-lg-8">
      <div class="form-group">{CHANGE_LOG}</div>
    </div>
  </div>

{END_FORM}

<script type = "text/javascript">
    window.internshipId = {INTERN_ID};
</script>

<script type="text/javascript" src="{vendor_bundle}"></script>
<script type="text/javascript" src="{emergency_entry_bundle}"></script>
<script type="text/javascript" src="{contract_entry_bundle}"></script>
<script type="text/javascript" src="{documents_entry_bundle}"></script>
<script type="text/javascript" src="{location_entry_bundle}"></script>
