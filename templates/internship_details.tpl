<table class="internship-details">
  <!-- BEGIN student -->
  <tr>
    <th colspan="4" class="detail-header">Student</th>
  </tr>
  <tr>
    <td>
      <b>Email:</b> <a href="mailto:{email}">{email}</a>
    </td>
    <td>
      <b>Phone:</b> {phone} 
    </td>
  </tr>
  <tr>
    <td>
      <b>Graduate Program:</b> {grad_prog}
    </td>
    <td>
      <b>Undergraduate Major:</b> {major}
    </td>
  </tr>
  <!-- END student -->

  <!-- BEGIN internship -->
  <tr>
    <th colspan="6" class="detail-header">Internship Type[s]</th>
  </tr>
  <tr>
    <td colspan="2">
      {internship}
    </td>
  </tr>
  <!-- END internship -->
  <!-- BEGIN agency -->
  <tr>
    <th colspan="6" class="detail-header">Agency</th>
  </tr>
  <tr>
    <td>
      <b>Name:</b> {name}
    </td>
    <td>
      <b>Address:</b> {address}
    </td>
    <td>
      <b>Phone:</b> {phone}
    </td>
  </tr>
  <tr>
    <th colspan="6" class="detail-header">Agency Supervisor</th>
  </tr>
  <tr>
    <td>
      <b>Name:</b> {supervisor_first_name} {supervisor_last_name}
    </td>
    <td>
      <b>Phone:</b> {supervisor_phone}
    </td>
    <td>
      <b>Email:</b> <a href="mailto:{supervisor_email}">{supervisor_email}</a>
    </td>
  </tr>
  <tr>
    <td>
      <b>Fax:</b> {supervisor_fax}
    </td>
    <td>
      <b>Address:</b> {supervisor_address}
    </td>
  </tr>
  <!-- END agency -->

  <!-- BEGIN faculty -->
  <tr>
    <th colspan="6" class="detail-header">Faculty Supervisor</th>
  </tr>
  <tr>
    <td>
      <b>Name:</b> {first_name} {last_name}
    </td>
    <td>
      <b>Phone:</b> {phone}
    </td>
    <td>
      <b>Email:</b> <a href="mailto:{email}">{email}</a>
    </td>
  </tr>
  <!-- END faculty -->
  <tr>
    <td>
      <ul class="document-list">
        <h4 class="detail-header">Documents</h4>
        <!-- BEGIN docs -->
        <li>{DOWNLOAD}{DELETE}</li>
        <!-- END docs -->
        <li>{UPLOAD_DOC}</li>
      </ul>
    </td>
  </tr>
</table>


