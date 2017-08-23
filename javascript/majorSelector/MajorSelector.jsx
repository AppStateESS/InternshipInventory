import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import classNames from 'classnames';

import MajorsDropDown from './MajorsDropDown.jsx';

class MajorSelector extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            undergrad: false,
            graduate: false,
            availableUndergradMajors: false,
            availableGradMajors: false,
            hasError: false
        };

        this.undergrad = this.undergrad.bind(this);
        this.graduate = this.graduate.bind(this);
        this.anyLevel = this.anyLevel.bind(this);
    }
    componentDidMount() {
        // Fetch list of available undergrad majors
        $.ajax({
            url: 'index.php?module=intern&action=GetUndergradMajors',
            dataType: 'json',
            success: function(data) {
                this.setState({availableUndergradMajors: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });

        // Fetch list of available graduate majors
        $.ajax({
            url: 'index.php?module=intern&action=GetGraduateMajors',
            dataType: 'json',
            success: function(data) {
                this.setState({availableGradMajors: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });
    }
    undergrad() {
        this.setState({undergrad: true, graduate: false});
    }
    graduate() {
        this.setState({undergrad: false, graduate: true});
    }
    anyLevel() {
        this.setState({undergrad: false, graduate: false});
    }
    render() {
        var majorsDropdown;

        if(!this.state.undergrad && !this.state.graduate) {
            majorsDropdown = <MajorsDropDown formStyle='horizontal'/>;
        } else if (this.state.undergrad) {
            majorsDropdown = <MajorsDropDown key="undergradMajors" ref="undergrad" majors={this.state.availableUndergradMajors} level='ugrad' formStyle='horizontal'/>;
        } else {
            majorsDropdown = <MajorsDropDown key="gradMajors" ref="graduate" majors={this.state.availableGradMajors} level='grad' formStyle='horizontal'/>;
        }

        var anyLevelClass = classNames({
            'btn':true,
            'btn-default': true,
            'active': !this.state.undergrad && !this.state.graduate
        });

        var undergradLevelClass = classNames({
            'btn':true,
            'btn-default': true,
            'active': this.state.undergrad
        });

        var graduateLevelClass = classNames({
            'btn':true,
            'btn-default': true,
            'active':this.state.graduate
        });

        return (
            <div>
                <div className="form-group">
                    <label className="col-lg-3 control-label" htmlFor="level">Level</label>
                    <div className="col-lg-8">
                        <div className="btn-group">
                            <label className={anyLevelClass}>Any Level
                                <input type="radio" name="level" value="-1" style={{position: "absolute", clip: "rect(0, 0, 0, 0)"}}  onClick={this.anyLevel} />
                            </label>
                            <label className={undergradLevelClass}>Undergraduate
                                <input type="radio" name="level" value="ugrad" style={{position: "absolute", clip: "rect(0, 0, 0, 0)"}} onClick={this.undergrad}/>
                            </label>
                            <label className={graduateLevelClass}>Graduate
                                <input type="radio" name="level" value="grad" style={{position: "absolute", clip: "rect(0, 0, 0, 0)"}} onClick={this.graduate} />
                            </label>
                        </div>
                    </div>
                </div>

                {majorsDropdown}
            </div>
        );
    }
}


ReactDOM.render(
    <MajorSelector />, document.getElementById('MajorSelector')
);
