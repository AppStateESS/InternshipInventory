var path = require('path');
var JS_DIR = path.resolve(__dirname, 'javascript');

module.exports = {
    entryPoints: {
      createInterface: JS_DIR + '/createInterface/CreateInternshipInterface.jsx',
      searchInterface: JS_DIR + '/searchInterface/SearchInterface.jsx',
      editAdmin: JS_DIR + '/editAdmin/editAdmin.jsx',
      editDepartment: JS_DIR + '/editDepartment/deptEditor.jsx',
      stateList: JS_DIR + '/stateList/StateList.jsx',
      emergencyContact: JS_DIR + '/emergencyContact/EmgContactList.jsx',
      facultyEdit: JS_DIR + '/facultyEdit/FacultyEdit.jsx',
      editMajor: JS_DIR + '/editMajor/editMajor.jsx',
      editGrad: JS_DIR + '/editGrad/editGrad.jsx',
      affiliationDepartments: JS_DIR + '/affiliationAgreement/AffiliationDepartments.jsx',
      affiliationLocation: JS_DIR + '/affiliationAgreement/AffiliationLocation.jsx',
      affiliationTerminate: JS_DIR + '/affiliationAgreement/AffiliationTerminate.jsx',
      majorSelector: JS_DIR + '/majorSelector/MajorSelector.jsx',
      vendor: ['jquery', 'react', 'react-dom', 'react-bootstrap']
    }
}
