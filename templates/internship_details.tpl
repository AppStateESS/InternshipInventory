<table class="internship-details">
  <!-- BEGIN student -->
  <tr>
    <th colspan="4">Student</th>
  </tr>
  <tr>
    <td>Email:</td><td><a href="mailto:{email}">{email}</a></td>
    <td>Phone:</td><td> {phone} </td>
  </tr>
  <tr>
    <td>Graduate Program:</td><td> {grad_prog} </td>
    <td>Undergraduate Major: </td><td> {ugrad_major} </td>
  </tr>
  <!-- END student -->
  <!-- BEGIN internship -->
  <tr>
    <th colspan="4">Internship Type</th>
  </tr>
  <tr>
    <td>Default Internship: </td><td>{internship}</td>
    <td>Service Learning: </td><td>{service_learning}</td>
    <td>Independent Study: </td><td>{independent_study}</td>
  </tr>
  <tr>
    <td>Research Assistant: </td><td>{research_assist}</td>
    <td>Other type: </td><td>{other_type}</td>
  </tr>
  <!-- END internship -->
  <!-- BEGIN agency -->
  <tr>
    <th colspan="6">Agency</th>
  </tr>
  <tr>
    <td>Name:</td><td>{name}</td>
    <td>Address:</td><td>{address}</td>
    <td>Phone:</td><td>{phone}</td>
  </tr>
  <tr>
    <th colspan="6">Agency Supervisor</th>
  </tr>
  <tr>
    <td>Name:</td><td>{supervisor_first_name}{supervisor_last_name}</td>
    <td>Phone:</td><td>{supervisor_phone}</td>
    <td>Email:</td><td><a href="mailto:{supervisor_email}">{supervisor_email}</td>
  </tr>
  <tr>
    <td>Fax:</td><td>{supervisor_fax}</td>
    <td>Address:</td><td>{supervisor_address}</td>
  </tr>
  <!-- END agency -->

  <!-- BEGIN faculty -->
  <tr>
    <th colspan="6">Faculty Supervisor</th>
  </tr>
  <tr>
    <td>Name:</td><td>{first_name} {last_name}</td>
    <td>Phone:</td><td>{phone}</td>
    <td>Email:</td><td><a href="mailto:{email}">{email}</a></td>
  </tr>
  <!-- END faculty -->
</table>


<ul class="document-list">
  <h4>Documents</h4>
  <!-- BEGIN docs -->
  <li>{DOWNLOAD}{DELETE}</li>
  <!-- END docs -->
  <li>{UPLOAD_DOC}</li>
</ul>
