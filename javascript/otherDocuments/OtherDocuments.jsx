import React from 'react';
import ReactDOM from 'react-dom';
import Dropzone from 'react-dropzone';

/*********************
 * Documents *
 *********************/
/*var DocumentInfo = React.createClass({
    getDefaultProps: function() {
        return {files: []}
    },
    onDrop: function(files) {
        this.props.update(files);
    },
    onOpenClick: function(){
        this.refs.dropzone.open();
    },
    render: function() {
        var files;
        var filesLink = null;
        var fileName;
        files = (<div className="clickme">
          <i className="fa fa-file"></i><br/>
          <p>Click or drag file here</p>
        </div>);
        return (
                <div className="dropzone">
                    <Dropzone ref="dropzone" onDrop={this.onDrop}>
                        {files}
                    </Dropzone>
                </div>
        );
    }
});*/

var Test = React.createClass({
    render: function() {
        return (
            <div>
                <p>Test Other Doc</p>
            </div>
        );
    }
});

ReactDOM.render(
    <Test internshipId={window.internshipId}/>,
    document.getElementById('other-documents')
);
