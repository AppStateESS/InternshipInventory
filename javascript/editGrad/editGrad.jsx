import React from 'react';
import ReactDOM from 'react-dom';
import Manager from '../manager/Manager.jsx';

ReactDOM.render(
	<Manager ajaxURL="gradRest"
			 title="Graduate Programs"
			 panelTitle="Add A Graduate Program:"
			 buttonTitle="Add Program"  />,
	document.getElementById('content')
);
