<h1>Faculty Members</h1>

{START_FORM}

Department: {DEPARTMENT_DROP}

<div id="new-faculty-dialog">
    First Name: {FIRSTNAME}<br />
    Last Name: {LASTNAME}<br />
    Banner Id: {BANNERID}<br />
    Username: {USERNAME}<br />
    Phone: {PHONE}<br />
    Fax: {FAX}<br />
    <strong>Address</strong>:<br />
    Street: {STREETADDRESS1}<br />
    Street 2: {STREETADDRESS2}<br />
    City: {CITY}<br />
    State: {STATE}<br />
    Zip Code: {ZIP}<br />
</div>

{END_FORM}

<div style="float:right;margin-right:20em;">
    <input id="add-button" type="button" value="Add a Faculty Member">
</div>

<div style="margin-top:3em;">
    <ul id="facultyList">
    </ul>
</div>