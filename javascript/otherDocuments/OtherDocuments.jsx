import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import Dropzone from 'react-dropzone';

class DocumentInfo extends Component{
    constructor(props) {
        super(props)
        this.state = {currentFiles: []}
        this.addFiles = this.addFiles.bind(this);
        this.componentDidMount = this.componentDidMount.bind(this);
        this.deleteFile = this.deleteFile.bind(this);
    }
    componentDidMount(){
        // Gets saved files if any
        $.ajax({
            url: 'index.php?module=intern&action=documentRest&type=other&internship_id=' + this.props.internshipId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                this.setState({currentFiles: data});
            }.bind(this)
        });
    }
    addFiles(files){
        let currentfiles = [];
        // Sends a seperate request for each file uploaded since there can be multiple at once
        $.each(files, function (key, value) {
            let formData = new FormData();
            formData.append(key, value);
            $.ajax({
                url: 'index.php?module=intern&action=documentRest&type=other&key='+key+'&internship_id=' + this.props.internshipId,
                type: 'POST',
                enctype: 'multipart/form-data',
                data: formData,
                cache: false,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (stat) {
                    currentfiles = this.state.currentFiles
                    if (stat.message === "") {
                        currentfiles.push(stat);
                    } else {
                        alert(stat.message)
                        return
                    }
                    this.setState({currentFiles: currentfiles})
                }.bind(this),
                error: function(xhr, status, err) {
                    alert("File failed to save.")
                    console.error(this.props.url, status, err.toString());
                }.bind(this)
            })
        }.bind(this))
    }
    deleteFile(file) {
        // Find key of file in currentFiles to be used for splice
        let key;
        for(let i=0; i<this.state.currentFiles.length;i++){
            if(file.id === this.state.currentFiles[i]['id']){
                key = i;
                break;
            }
        }
        $.ajax({
            url: 'index.php?module=intern&action=documentRest&type=other&docId='+file.id+'&internship_id=' + this.props.internshipId,
            method: 'DELETE',
            success: function (data) {
                let files = this.state.currentFiles
                files.splice(key, 1)
                this.setState({currentFiles: files})
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Unable to delete file. File does not exist.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        })
    }
    render(){
        let list
        if (this.state.currentFiles.length > 0) {
            list = this.state.currentFiles.map(function(f){
                let url = "index.php?module=intern&action=documentRest&type=other&docId="+f.id+"&internship_id="+this.props.internshipId;
                return(<li className="list-group-item" key={f.id}><i className="fa fa-file"></i> <a href={url} >{f.name}</a> &nbsp;
                <button type="button" className="close" onClick={this.deleteFile.bind(this, f)}><span aria-hidden="true"><i className='fa fa-trash-o'></i></span></button> </li>
            )}.bind(this));
        }
        return(
            <section>
                <div>
                    <div className="dropzone text-center pointer">
                        <Dropzone ref="dropzone" style={{width: 'auto', height: 'auto', border: '2px dashed gray'}} onDrop={this.addFiles}>
                            <div style={{paddingTop: '1%'}}>
                                <i className="fa fa-file"></i><br/>
                                <p>Click to browse or drag file(s) here.</p>
                            </div>
                        </Dropzone>
                    </div>
                    <div>
                        <label>Added Files:</label>
                        <ul className="list-group">
                            {list}
                        </ul>
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
