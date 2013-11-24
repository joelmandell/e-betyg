if ("ontouchstart" in document.documentElement)
{
    var browserEvent = "touchstart";
}
else
{
    var browserEvent = "click";
}

var group=null;
var type=null;

$('#test').on('click', function() {

    if(group!=null)
    {
        var file=$('#selfile').get(0).files[0];

        var base_uri=document.location.href.replace("http://"+document.domain,'');
        var base_uri=base_uri.replace("#","");

        var fd = new FormData();
        fd.append("file", file);
        
        var progressBar = document.querySelector('progress');
        $("#prog").show();


        var xhr = new XMLHttpRequest();
        xhr.open('POST', base_uri, true);
        
        xhr.upload.onprogress = function(e) {
            if(e.lengthComputable) {
                progressBar.value = (e.loaded / e.total) * 100;
                progressBar.textContent = progressBar.value; // Fallback for unsupported browsers.
            }
        };
        
        xhr.onload = function() {
            if (this.status == 200) {
                var resp = this.response;
                $("#prog").hide();
                $("#upload_view").html("Filen är uppladdad!");
            };
        };

        xhr.send(fd); 
    } else {
        alert("Du har inte valt vilken grupp du skall ladda upp dokumentet till.");
    }
});

function fileLoaded(f)
{
  
  
    /*$.post(base_uri,{file:fileString},function( data ) {
        $("#upload_view").html("<img id=\"i\" src=\"\" />");
        $("#i").attr("src","data:"+type+";base64,"+data);
    });*/
}

$('#select_group').on('change', function() {
    group= $('option:selected', this).attr('value');  
 
});

filepick.addEventListener("click", function (e) {
  if (selfile) {
    selfile.click();
  }
}, false);