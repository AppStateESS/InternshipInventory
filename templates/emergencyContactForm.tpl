<div id="emergency-dialog">
  <form class="form-horizontal {FORM_CLASS}" id="{FORM_ID}" action="{FORM_ACTION}" autocomplete="{FORM_AUTOCOMPLETE}" method="{FORM_METHOD}"{FORM_ENCODE}>
    {HIDDEN_FIELDS}

    <div class="form-group">
      <label for="{EMERGENCY_CONTACT_NAME_ID}" class="col-lg-3 control-label">{EMERGENCY_CONTACT_NAME_LABEL_TEXT}</label>
      <div class="col-lg-9">{EMERGENCY_CONTACT_NAME}</div>
    </div>

    <div class="form-group">
      <label for="{EMERGENCY_CONTACT_RELATION_ID}" class="col-lg-3 control-label">{EMERGENCY_CONTACT_RELATION_LABEL_TEXT}</label>
      <div class="col-lg-9">{EMERGENCY_CONTACT_RELATION}</div>
    </div>

    <div class="form-group">
      <label for="{EMERGENCY_CONTACT_PHONE_ID}" class="col-lg-3 control-label">{EMERGENCY_CONTACT_PHONE_LABEL_TEXT}</label>
      <div class="col-lg-9">{EMERGENCY_CONTACT_PHONE}</div>
    </div>
  </form>
</div>

<div id="emergency-contact-delete-confirm" style="display: none;" title="Delete contact?">
  <span class="tango22 tango-dialog-warning"></span>Are you sure you want to delete that emergency contact?
</div>