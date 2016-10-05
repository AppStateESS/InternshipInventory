import React from 'react';
import ReactDOM from 'react-dom';
import Manager from '../manager/Manager.jsx';

ReactDOM.render(
	<Manager ajaxURL="deptRest"
			 title="Departments"
			 panelTitle="Add A Department "
			 buttonTitle="Add Department"  />,
	document.getElementById('content')
);
