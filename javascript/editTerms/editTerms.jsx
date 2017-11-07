import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group'

//got from editAdmin.jsx

//error or notification block?


class TermRow extends React.Component {

}

class TermList extends React.Component {
    render() {
        return (
            <table className="table table-condensed table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
        );
    }
}

class CreateTerm extends React.Component {

}

class TermSelector extends React.Component {

}


ReactDOM.render(
    <TermList />,
    document.getElementById('edit_terms')
);
