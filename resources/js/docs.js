var myBlob=null;
var base_url="/e-betyg/";

//JQUERY PLUGIN FOR LISTENING TO LOCATION CHANGE.
(
    function( $ ){
    // Default to the current location.
    var strLocation = window.location.href;
    var strHash = window.location.hash;
    var strPrevLocation = "";
    var strPrevHash = "";

    // This is how often we will be checking for
    // changes on the location.
    var intIntervalTime = 100;

    // This method removes the pound from the hash.
    var fnCleanHash = function( strHash ){
        return(
            strHash.substring( 1, strHash.length )
        );
    }

    // This will be the method that we use to check
    // changes in the window location.
    var fnCheckLocation = function(){
        // Check to see if the location has changed.
        if (strLocation != window.location.href)
        {
            // Store the new and previous locations.
            strPrevLocation = strLocation;
            strPrevHash = strHash;
            strLocation = window.location.href;
            strHash = window.location.hash;

            // The location has changed. Trigger a
            // change event on the location object,
            // passing in the current and previous
            // location values.
            $( window.location ).trigger(
                "change",
                {
                currentHref: strLocation,
                currentHash: fnCleanHash( strHash ),
                previousHref: strPrevLocation,
                previousHash: fnCleanHash( strPrevHash )
                }
            );
        }
    }

    // Set an interval to check the location changes.
    setInterval( fnCheckLocation, intIntervalTime );
    }
)( jQuery );

//When user press F5 or do refresh of page
//we need to 
$(document).ready(function() {
    hashBrowsing();    

});

//Function to do the routing when navigating by hash-tags.
function hashBrowsing()
{
    if(location.hash.indexOf("#edit_pending_options")!=-1)
    {
        PendingCorrections(location.hash);
    }
    
    if(location.hash.indexOf("#download")!=-1)
    {
        tempArray=location.hash.split("|");
        fileName=tempArray[1];
        saveAs(myblob, fileName);
        window.URL.revokeObjectURL(myblob);
        history.back(-1);
    }
    
    if(location.hash.indexOf("#review")!=-1)
    {
        var base_uri=document.location.href.replace("http://"+document.domain,"");
        var work_uri=base_uri.replace(/\w*#\w*/,"Download");

        //Prepare the file in a separate "Thread" or Worker.
        var worker = new Worker(base_url+"resources/js/DownloadWorker.js");
        
        //We send message to the worker that we want to get blob
        //with id and that we get this id from work_uri.
        worker.postMessage({id:location.hash.replace("#review",""),uri:work_uri});
        
        //Here we listen to the worker and recieve message when it is finished
        //processing its data.
        worker.onmessage = function (event) {
            data=event.data;
            myblob=b64toBlob(data[2]);
            var obj_url = window.URL.createObjectURL(myblob,{oneTimeOnly: true, type:"application/octet-stream"});
            
            //Create link and send the filename and the hash-navigation url.
            $("html a[id='download_blob']").attr("href","#download|"+data[1]);
            //$("html a[id='download_blob']").attr("download",data[1]);
            $("html a[id='download_blob']").show();
        };
        
        //Download(location.hash.replace("#review",""));
        Review(location.hash.replace("#review",""));
    }  
    
}

$(window).on('hashchange', function() {
    hashBrowsing();
});

$( window.location ).bind(
    "change",function( objEvent, objData ){
        
        if(document.location.href.indexOf("#")==-1)
        {
            $("#doc_view").html("");
        }
    }
);

function b64toBlob(b64Data, contentType, sliceSize) {
    contentType = contentType || '';
    sliceSize = sliceSize || 512;

    var byteCharacters = atob(b64Data);
    var byteArrays = [];

    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

    var blob = new Blob(byteArrays, {type: contentType});
    return blob;
}

function DownloadCompat(val)
{
    
}



function publish(data) {
  if (!window.BlobBuilder && window.WebKitBlobBuilder) {
    window.BlobBuilder = window.WebKitBlobBuilder;
  }
  var builder = new BlobBuilder();
  builder.append(data);
  var blob = builder.getBlob();
  var url = window.webkitURL.createObjectURL(blob);
  document.location.href=url;
}

function Review(val)
{
    var base_uri=document.location.href.replace("http://"+document.domain,'');
    var base_uri=base_uri.replace("#\w*","");
    var work_uri=base_uri.replace("/Docs/","/Docs/Review/");
    
    var id=val;
    $.post(work_uri,{docId:id},function( data ) {
            var docs=data;
            $("#doc_view").html(data)

    }, "text");
}

function PendingCorrections(val)
{
    document.location.href=val;
    var base_uri=document.location.href.replace("http://"+document.domain,'');
    var base_uri=base_uri.replace("#\w*","");
    var work_uri=base_uri.replace("/Docs/","/Docs/PendingCorrection/");

    var id = val.replace("#edit_pending_options","");  

    $.post(work_uri,{groupId:id},function( data ) {
        
        var docs=JSON.parse(data);
        var html="";
        for(doc in docs)
        {
            docprop=docs[doc].split("|");
            html+="<strong>"+docprop[0]+"</strong> - \""+docprop[1]+"\"<br /><a href=\"#review"+doc+"\" class=\"review"+doc+"\">Granska</a><br /><img class=\"icon\" src=\"/e-betyg/resources/img/icons/research.png\" /><br />";
        }
        $("#doc_view").html(html)

    }, "text");  
}

$("#doc_view").on("click","[class^='review']", function() {
    
    var base_uri=document.location.href.replace("http://"+document.domain,'');
    var base_uri=base_uri.replace("#\w*","");
    var work_uri=base_uri.replace("/Docs/","/Docs/Review/");
    
    var id=$(this).attr("class").replace("review","");
    $.post(work_uri,{docId:id},function( data ) {
            var docs=data;
            $("#doc_view").html(data)

        }, "text");
});

$('#edit_pending_options').on('change', function() {
    document.location.href="#"+$(this).attr("id")+$("option:selected",this).attr("value");
    var base_uri=document.location.href.replace("http://"+document.domain,'');
    var base_uri=base_uri.replace("#\w*","");
    var work_uri=base_uri.replace("/Docs/","/Docs/PendingCorrection/");

    var id = $('option:selected', this).attr('value');  

    $.post(work_uri,{groupId:id},function( data ) {
        var docs=JSON.parse(data);
        var html="";
        for(doc in docs)
        {
            docprop=docs[doc].split("|");
            html+="<strong>"+docprop[0]+"</strong> - \""+docprop[1]+"\"<br /><a href=\"#review"+doc+"\" class=\"review"+doc+"\">Granska</a><br /><img class=\"icon\" src=\"/e-betyg/resources/img/icons/research.png\" /><br />";
        }
        $("#doc_view").html(html)

    }, "text");
    
});