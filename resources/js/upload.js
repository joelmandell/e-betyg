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
var selectedfile=null;

$('#send').on('click', function() {

    if(group!=null)
    {
        var file=$('#selfile').get(0).files[0];
        var reader = new FileReader();
        selectedfile=file;
        reader.readAsText(file);
        
        //Check if mimetype is correct.
        if(file.type=="application/vnd.oasis.opendocument.text")
        {
            //Good! Just continue..
        } else if (file.type=="application/x-pdf") {
            //Good....
        } else if (file.type=="application/pdf") {
            //Good...    
        } else if(file.type=="application/vnd.openxmlformats-officedocument.presentationml.presentation") {
            //Good
        } else if(file.type=="application/msword") {
            //Good           
        } else if(file.type=="application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
            //Good
        } else if(file.type=="application/vnd.ms-excel") {
            //Good
        } else if(file.type=="application/vnd.ms-powerpoint") {
            //Good
        } else if(file.type=="application/vnd.openxmlformats-officedocument.presentationml.presentation") {
            //Good
        } else {
            //NOoo way that you gonna upload that file dude!
            alert("Otillåtet filformat - endast pdf, odt och office format tillåtna.");
            return;
        }
        
        var base_uri=document.location.href.replace("http://"+document.domain,'');
        var base_uri=base_uri.replace("#","");

        var fd = new FormData();
        fd.append("file", file);
        fd.append("group", group);
        fd.append("mime", file.type);
        fd.append("usercomment", $("#usercomment").val());
        fd.append("filename",selectedfile.name);    
        $('input[name=groupPublic]').is(':checked') ? fd.append("groupPublic","1") : fd.append("groupPublic","0");

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

                if(resp.indexOf("true")!=-1)
                {
                    $("#usercomment").val("");
                    $("#selfile").val("");
                    $("#file_choice_layer").hide();
                    $('input[name=groupPublic]').attr('checked', false);
                    $("#send").hide();            
                    alert("Filen har laddats upp!");
                    
                } else if(resp.indexOf("ERROR")!=-1) { 
                    //Eeeh..so you tried bybass the javascript. You will not get 
                    //possibility to upload other mime anyways, sucker!
                    alert("Otillåtet filformat - endast pdf, odt och office format tillåtna.");
                } else {    
                    alert("Misslyckades ladda upp. Försök igen!");
                }
            };
        };

        xhr.send(fd); 
    } else {
        alert("Du har inte valt vilken grupp du skall ladda upp dokumentet till.");
    }
});

$('#select_group').on('change', function() {
    group= $('option:selected', this).attr('value');  
 
});

$("#selfile").on("change", function() {
    selectedfile=$('#selfile').get(0).files[0];
    $("#file_choice_layer").show();
    $("#file_choice").html(selectedfile.name); 
    $("#send").show();
});

filepick.addEventListener("click", function (e) {
  if (selfile) {
    selfile.click();
  }
}, false);