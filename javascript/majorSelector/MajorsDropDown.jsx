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

        var options = null;

        if(level === 'ugrad' || level === 'grad'){
            options = Object.keys(majors).map(function(index) {
                        return <option key={majors[index].code} value={majors[index].code}>{majors[index].description}</option>;
                    });
        } else {
            options = (<option >Choose a level first</option>);
        }

        return (
            <div className="form-group">
                <label htmlFor="majors" className="col-lg-3 control-label">Major/Program</label>
                <div className="col-lg-8">
                    <select id={level} name={level} className="form-control">
                        {options}
                    </select>
                </div>
            </div>
        );
    }
}

export default MajorsDropDown;
