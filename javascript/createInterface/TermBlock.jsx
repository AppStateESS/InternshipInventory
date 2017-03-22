import React from 'react';
import $ from 'jquery';
import classNames from 'classnames';


/*********
 * Terms *
 *********/
var TermBlock = React.createClass({
    getInitialState: function() {
        return ({terms: null, hasError: false, selectedTerm: null});
    },
    componentWillMount: function() {
        $.ajax({
            url: 'index.php?module=intern&action=GetAvailableTerms',
            dataType: 'json',
            success: function(data) {
                this.setState({terms: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }
        });
    },
    setError: function(status){
        this.setState({hasError: status});
    },
    handleChange: function(clickEvent){
        this.setState({selectedTerm: clickEvent.target.childNodes[0].value});
    },
    render: function() {
        if(this.state.terms === null){
            return (<div></div>);
        }

        var fgClasses = classNames({
                        'form-group': true,
                        'has-error': this.state.hasError
                    });

        var termDates = null;
        if(this.state.selectedTerm !== null){
            termDates = this.state.terms[this.state.selectedTerm].startDate + " through " + this.state.terms[this.state.selectedTerm].endDate;
        }else{
            termDates = '';
        }

        return (
            <div className="row">
                <div className="col-sm-12 col-md-5 col-md-push-3">
                    <div className={fgClasses} id="term">
                        <label htmlFor="term" className="control-label">Term</label><br />
                        <div className="btn-group" data-toggle="buttons">

                            {Object.keys(this.state.terms).map(function(key) {
                                return (
                                    <label className="btn btn-default" key={key} onClick={this.handleChange}>
                                        <input type="radio" ref="term" name="term" key={key} value={key} />{this.state.terms[key].description}
                                    </label>
                                );
                            }.bind(this))}
                        </div>
                    </div>
                </div>
                <div className="col-md-4 col-md-push-2">
                    <span id="helpBlock" className="help-block" style={{marginTop: '32px'}}>{termDates}</span>
                </div>
            </div>

        );
    }
});

export default TermBlock;
