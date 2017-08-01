import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import Dropzone from 'react-dropzone';

/*var DocumentInfo = React.createClass({
    getDefaultProps: function(){
        return{filesSaved: []};
    },
    onDrop: function(files){
        this.setState({filesSaved: files});
        var data = new FormData();
        $.each(this.state.filesSaved, function(key, value){
            data.append(key, value);
        });
        $.ajax({
            url: 'index.php?module=intern&action=documentRest',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (data) {
                this.props.reload();
            }.bind(this),
            error: function(){
                alert('Faild to save files.');
            }
        });
    },
    onOpenClick: function(){
        this.refs.dropzone.open();
    },
    render: function() {
        return (
            <section>
                <div className="dropzone text-center pointer">
                    <Dropzone ref="dropzone" accept="file/pdf, file/doc, file/odt" onDrop={this.onDrop}>
                    <div className="clickme">
                      <i className="fa fa-file"></i>
                      <p>Click or drag files here.</p>
                    </div>
                    <ul>{this.state.filesSaved.map(f => <li key={f.name}>{f.name}</li>)}</ul>
                    </Dropzone>
                </div>
            </section>
        );
    }
});*/

/*var DocumentInfo = React.creatClass({
    getInitialState: function(){
        super()
        this.state = {files: []}
    },
    onDrop: function(files) {
        var req = request.post('index.php?module=intern&action=documentRest');
        files.forEach(file => {req.attach(file.name, file);});
        req.end(callback);
    },
    onOpenClick: function(){
        this.refs.dropzone.open();
    },
    render: function() {
        var files;

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
                    <Dropzone ref="dropzone" accept="file/pdf, file/doc, file/odt" onDrop={this.onDrop}>
                    <div className="clickme">
                      <i className="fa fa-file"></i>
                      <p>Click or drag files here.</p>
                    </div>
                    <ul>{this.state.accepted.map(f => <li key={f.name}>{f.name}</li>)}</ul>
                    </Dropzone>
                </div>
            </section>
        );
    }
});*/
class DocumentInfo extends Component{
    constructor(props) {
        super(props)
        this.state = {show: false,
            newFiles: [],
            currentFiles: [],
            status: []}
        this.addFiles = this.addFiles.bind(this)
        this.clearNewFiles = this.clearNewFiles.bind(this)
        this.delete = this.delete.bind(this)
    }
    componentDidMount(){
        /*if(currentFiles.length > 0) {
            this.setState({currentFiles: currentFiles})
        }*/
    }
    clearNewFiles() {
        this.setState({newFiles: []})
    }
    addFiles(files){
        console.log(files);
        let status = this.state.status
        let newFiles = []
        let currentFiles = []
        this.clearNewFiles();
        $.each(files, function (key, value) {
            console.log(key, value);
            $.ajax({
                url: 'index.php?module=intern&action=documentRest&internship_id=' + this.props.internshipId,
                type: 'POST',
                data: value,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    console.log('pass');
                    currentFiles = this.state.currentFiles
                    /*if (data.success === true) {
                        currentFiles.push(data.photo)
                    } else if (data.success === false) {
                        alert('A server error prevented uploading of your file. Contact the site administrators')
                        return
                    }
                    newFiles.push(data.photo)
                    status[key] = data.success
                    this.setState({status: status, currentPhotos: currentFiles, newPhotos: newFiles})*/
                }.bind(this),
                failure: function (data) {
                    console.log('fail');
                    /*newFiles.push(data.photo)
                    status[key] = false*/
                    this.setState({status: status, newPhotos: newFiles})
                }.bind(this)
            })
        }.bind(this))
    }
    delete(file, key) {
        $.ajax({
            url: '',
            dataType: 'json',
            method: 'DELETE',
            success: function (data) {
                let files = this.state.currentFiles
                if (data.success === true) {
                    files.splice(key, 1)
                }
                this.setState({currentFiles: files})
            }.bind(this),
            error: function () {}
        })
    }
    render(){
        let files = (<div style={{paddingTop: '5%'}}>
            <i className="fa fa-file fa-5x"></i><br/>
            <h4>Click to browse<br/>- or -<br/>drag file(s) here</h4>
        </div>)
        if (this.state.newFiles.length > 0) {
            files = this.state.newFiles.map(function (value, key) {
                let status
                if (this.state.status[key] !== undefined) {
                    status = this.state.status[key]
                }

            }.bind(this))}
        return(
            <section>
                <div>
                    <div className="dropzone text-center pointer">
                        <Dropzone ref="dropzone" onDrop={this.addFiles}>
                            {files}
                        </Dropzone>
                    </div>
                    <div>
                        <button className="btn btn-default" onClick={this.clearNewFiles}>Clear</button>
                    </div>
                </div>
            </section>
        )
    }
}
ReactDOM.render(
    <DocumentInfo internshipId={window.internshipId}/>,
    document.getElementById('other-documents')
);
