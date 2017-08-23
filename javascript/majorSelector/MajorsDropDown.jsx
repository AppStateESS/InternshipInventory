import React from 'react';

class MajorsDropDown extends React.Component {
    constructor(props){
        super(props);
        this.state = {hasError: false};
    }
    setError(status){
        this.setState({hasError: status});
    }
    render() {
        var majors = this.props.majors;
        var level = this.props.level;

        var output = null;

        if(level === 'ugrad' || level === 'grad'){
            output = (
                <select id={level} name={level} className="form-control">
                    {Object.keys(majors).map(function(key) {
                        return <option key={key} value={key}>{majors[key]}</option>;
                    })}
                </select>
            );
        } else {
            output = (
                <select id="def" name="def" className="form-control">
                    <option >Choose a level first</option>
                </select>
            );
        }

        return (
            <div className="form-group">
                <label htmlFor="majors" className="col-lg-3 control-label">Major/Program</label>
                <div className="col-lg-8">
                    {output}
                </div>
            </div>
        );
    }
}

export default MajorsDropDown;
