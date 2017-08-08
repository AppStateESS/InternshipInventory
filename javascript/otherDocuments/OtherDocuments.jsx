import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import Dropzone from 'react-dropzone';


/*
    <Dropzone ref="dropzone" accept="file/pdf, file/doc, file/odt" onDrop={this.onDrop}>
*/
class DocumentInfo extends Component{
    constructor(props) {
        super(props)
        this.state = {show: false,
            currentFiles: []}
        this.addFiles = this.addFiles.bind(this);
    }
    componentDidMount(){
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
        let add = {'name':'','newName':''};
        $.each(files, function (key, value) {
            let formData = new FormData()
            formData.append('file', value);
            let data = value;
            $.ajax({
                url: 'index.php?module=intern&action=documentRest&type=other&internship_id=' + this.props.internshipId,
                type: 'POST',
                enctype: 'multipart/form-data',
                data: formData,
                cache: false,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (stat) {
                    console.log('stat:', stat.message);
                    currentfiles = this.state.currentFiles
                    console.log(currentfiles, this.state.currentFiles);
                    if (stat.name !== null) {
                        add['name'] = data.name;
                        add['store_name'] = stat.name;
                        currentfiles.push(add);
                    } else {
                        alert(stat)
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
    download(file){
        $.ajax({
            url: 'index.php?module=intern&action=documentRest&type=other&name='+file.store_name+'&internship_id=' + this.props.internshipId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                window.open(data);
            },
            error: function(xhr, status, err) {
                alert("Unable to download file.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        })
    }
    deleteFile(file) {
        // Find key of file in currentFiles
        let key;
        for(let i=0; i<this.state.currentFiles.length;i++){
            if(file.name === this.state.currentFiles[i]){
                key = i;
                break;
            }
        }
        $.ajax({
            url: 'index.php?module=intern&action=documentRest&type=other&name='+file.store_name+'&internship_id=' + this.props.internshipId,
            method: 'DELETE',
            success: function (data) {
                let files = this.state.currentFiles
                files.splice(key, 1)
                this.setState({currentFiles: files})
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Unable to delete file.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        })
    }
    render(){
        let list
        console.log('currentFiles: ',this.state.currentFiles);
        if (this.state.currentFiles.length > 0) {
            list = this.state.currentFiles.map(f =>
                <li className="list-group-item"><i className="fa fa-file"></i> <a onClick={this.download.bind(this, f)}>{f.name}</a> &nbsp;
                <button type="button" className="close" onClick={this.deleteFile.bind(this, f)}><span aria-hidden="true"><i className='fa fa-trash-o close'></i></span></button> </li>
            );
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
                        <h4>Added Files:</h4>
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
