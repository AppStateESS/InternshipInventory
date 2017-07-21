import React from 'react';
import ReactDOM from 'react-dom';
import Dropzone from 'react-dropzone';

/*********************
 * Documents *
 *********************/
var DocumentInfo = React.createClass({
    getDefaultProps: function() {
        return {files: []}
    },
    onDrop: function(files) {
        this.props.update(files);
    },
    onOpenClick: function(){
        this.refs.dropzone.open();
    },
    update(files){
        //console.log(files);
    },
    render: function() {
        var files;
        var fileName;

        if (this.props.files.length > 0) {
            files = this.props.files.map(f => <li>{f.name}</li>)
        } else {
            files = (
                <div className="clickme">
                  <i className="fa fa-file"></i>
                  <p>Click or drag files here.</p>
                </div>
            );
        }
        return (
            <section>
                <div className="dropzone text-center pointer">
                    <Dropzone style={{width: 'auto', height: 'auto', border: '2px dashed gray'}} multiple={false} ref="dropzone" accept="file/pdf, file/doc, file/odt" onDropAccepted={this.onDrop}>
                        {files}
                    </Dropzone>
                </div>
            </section>
        );
    }
});

ReactDOM.render(
    <DocumentInfo internshipId={window.internshipId}/>,
    document.getElementById('other-documents')
);
