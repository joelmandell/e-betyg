function Download(val, work_uri)
{   
    var id=val;
    var params="docId="+id;
 
    //Because lack of window and document when we are in this worker
    //we will use XMLHttpRequest instead of jquery....
    var xhr = new XMLHttpRequest();
    
    xhr.open("POST", work_uri, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.setRequestHeader("Content-length", params.length);
    xhr.setRequestHeader("Connection", "close");
    xhr.onload = function(e) {
        if (this.status == 200) {
            var array=JSON.parse(this.responseText);
            postMessage(array);
        }
    };

    xhr.send(params);
}

//Our worker is sitting and waiting for message to start downloading the file.
onmessage = function (oEvent) {   
  data=oEvent.data;
  Download(data.id,data.uri);
};

