/* global exports*/
exports.path = require('path');
exports.JS_DIR = exports.path.resolve(__dirname, 'javascript');

exports.entry = {
      createInterface: exports.JS_DIR + '/createInterface/CreateInternshipInterface.jsx',
      searchInterface: exports.JS_DIR + '/searchInterface/SearchInterface.jsx',
      editAdmin: exports.JS_DIR + '/editAdmin/editAdmin.jsx',
      editDepartment: exports.JS_DIR + '/editDepartment/deptEditor.jsx',
      stateList: exports.JS_DIR + '/stateList/StateList.jsx',
      emergencyContact: exports.JS_DIR + '/emergencyContact/EmgContactList.jsx',
      facultyEdit: exports.JS_DIR + '/facultyEdit/FacultyEdit.jsx',
      editLevel: exports.JS_DIR + '/editLevel/editLevel.jsx',
      affiliateList: exports.JS_DIR + '/affiliationAgreement/AffiliateList.jsx',
      affiliationDepartments: exports.JS_DIR + '/affiliationAgreement/AffiliationDepartments.jsx',
      affiliationLocation: exports.JS_DIR + '/affiliationAgreement/AffiliationLocation.jsx',
      affiliationTerminate: exports.JS_DIR + '/affiliationAgreement/AffiliationTerminate.jsx',
      affiliationUpload: exports.JS_DIR + '/affiliationAgreement/AffiliationUpload.jsx',
      editExpectedCourses: exports.JS_DIR + '/editCourses/courseEditor.jsx',
      majorSelector: exports.JS_DIR + '/majorSelector/MajorSelector.jsx',
      adminSettings: exports.JS_DIR + '/settings/settings.jsx',
      editTerms: exports.JS_DIR + '/editTerms/EditTerms.jsx',
      contractAffiliation: exports.JS_DIR + '/contractAffiliation/ContractAffiliation.jsx',
      otherDocuments: exports.JS_DIR + '/otherDocuments/OtherDocuments.jsx',
      approveHost: exports.JS_DIR + '/specialHost/ApproveHost.jsx'
      //vendor: ['jquery', 'react', 'react-dom', 'react-bootstrap']
}
