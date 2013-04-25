<script type="text/template" id="faculty-template">
<a href="javascript:void(0)" class="faculty-edit"><%= id %> - <%= first_name %> <%= last_name %>
</script>

<script type="text/template" id="faculty-edit-dialog-template">
    <div id="faculty-edit-dialog">
    <% if(!id) { %>
        <p>Please begin by entering a Banner ID.  If the faculty member exists within Internship Inventory, their data will be loaded automatically.</p>
    <% } else {%>
        <p>Note: Changes to this faculty member will be applied to all internships and departments in which the member is involved.</p>
    <% } %>
        <table>
            <tr>
                <th>Banner ID:</th>
                <td><input type="text" id="faculty-edit-id" value="<%=id%>"></td>
            </tr>
            <tr>
                <th>Username:</th>
                <td><input type="text" id="faculty-edit-username" value="<%=username%>"></td>
            </tr>
            <tr>
                <th>First Name:</th>
                <td><input type="text" id="faculty-edit-first_name" value="<%=first_name%>"></td>
            </tr>
            <tr>
                <th>Last Name:</th>
                <td><input type="text" id="faculty-edit-last_name" value="<%=last_name%>"></td>
            </tr>
            <tr>
                <th>Phone:</th>
                <td><input type="text" id="faculty-edit-phone" value="<%=phone%>"></td>
            </tr>
            <tr>
                <th>Fax:</th>
                <td><input type="text" id="faculty-edit-fax" value="<%=fax%>"></td>
            </tr>
            <tr>
                <th rowspan="2">Address:</th>
                <td><input type="text" id="faculty-edit-street_address1" value="<%=street_address1%>"></td>
            </tr>
            <tr>
                <td><input type="text" id="faculty-edit-street_address2" value="<%=street_address2%>"></td>
            </tr>
            <tr>
                <th>City:</th>
                <td><input type="text" id="faculty-edit-city" value="<%=city%>"></td>
            </tr>
            <tr>
                <th>State:</th>
                <td><input type="text" id="faculty-edit-state" value="<%=state%>"></td>
            </tr>
            <tr>
                <th>Zip:</th>
                <td><input type="text" id="faculty-edit-zip" value="<%=zip%>"></td>
            </tr>
        </table>
    </div>
</script>

<script type="text/javascript" src="{source_http}mod/intern/javascript/facultyEdit/faculty.js"></script>
