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
    
    if(location.hash.indexOf("#review")!=-1)
    {
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