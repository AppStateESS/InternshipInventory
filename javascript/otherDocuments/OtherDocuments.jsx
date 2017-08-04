import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import Dropzone from 'react-dropzone';


/*    onOpenClick: function(){
        this.refs.dropzone.open();
    },
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
            currentFiles: [],
            status: [],
            toDownload: []}
        this.addFiles = this.addFiles.bind(this);
        this.clearNewFiles = this.clearNewFiles.bind(this);
        this.delete = this.delete.bind(this);
        this.download = this.delete.bind(this);
    }
    componentDidMount(){
        $.ajax({
            url: 'index.php?module=intern&action=documentRest&internship_id=' + this.props.internshipId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                this.setState({currentFiles: data});
            }.bind(this)
        });
    }
    clearNewFiles() {
        this.setState({newFiles: []});
    }
    addFiles(files){
        let status = this.state.status;
        let currentfiles = [];
        this.clearNewFiles();
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
                    console.log('stat:', stat);
                    currentfiles = this.state.currentFiles
                    console.log(currentfiles, this.state.currentFiles);
                    if (stat === null) {
                        currentfiles.push(data)
                    } else {
                        alert(stat)
                        return
                    }
                    alert("Passed.")
                    status[key] = data.success
                    this.setState({status: status, currentFiles: currentfiles})
                }.bind(this),
                error: function(xhr, status, err) {
                    alert("Failed.")
                    console.error(this.props.url, status, err.toString());
                }.bind(this)
            })
        }.bind(this))
    }
    download(){
        $.ajax({
            url: 'index.php?module=intern&action=documentRest&type=other&internship_id=' + this.props.internshipId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                this.setState({toDownload: data})
            }.bind(this),
            error: function(xhr, status, err) {
                alert("Unable to download file.")
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        })
    }
    delete(file, key) {
        $.ajax({
            url: 'index.php?module=intern&action=documentRest&type=other&internship_id=' + this.props.internshipId,
            method: 'DELETE',
            success: function (data) {
                let files = this.state.currentFiles
                if (data.success === true) {
                    files.splice(key, 1)
                }
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
                <li className="list-group-item" onClick={this.download}><i className="fa fa-file"></i> {f.name} &nbsp;
                <button type="button" className="close" onClick={this.delete}><span aria-hidden="true"><i className='fa fa-trash-o'></i></span></button> </li>
            );
        }
        //if files
        //let list = <li class="list-group-item"><i class="fa fa-file"></i> {this.download} &nbsp;{this.delete}</li>
        /*list = <li className="list-group-item"><i className="fa fa-file"></i> test &nbsp;
            <button type="button" className="close" onClick={this.delete}><span aria-hidden="true"><i className='fa fa-trash-o'></i></span></button> </li>*/
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
//onDropRejected={this.onDropRejected} for when file size is too big, see if maxSize is varying
ReactDOM.render(
    <DocumentInfo internshipId={window.internshipId}/>,
    document.getElementById('other-documents')
);
