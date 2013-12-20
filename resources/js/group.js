/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if ("ontouchstart" in document.documentElement)
{
    var browserEvent = "touchstart";
}
else
{
    var browserEvent = "click";
}

$('#edit_groups').on('change', function() {
    var name = $('option:selected', this).attr('value');  
    Edit(name);
});

$('#create_group').on(browserEvent, function() {
    var name = prompt("Vad skall gruppen heta?", "");   
    if(name.indexOf(" ") || name!==null) 
    {
        Create(name);
    } else {
        alert("Du avbröt eller så var namnet \""+name+"\" ogiltigt.");
    }
});


$('#delete_group').on(browserEvent, function() {
    var name = $("#edit_groups option:selected").text()
    Delete(name);
});

function Delete(name)
{
    var really=confirm("Vill du verkligen ta bort gruppen "+name+"? Det innebär att alla\n\
    elevanslutningar tas bort och måste läggas till på nytt senare.");
    
    if(really)
    {
        var base_uri=document.location.href.replace("http://"+document.domain,'');
        var base_uri=base_uri.replace("#","");
        var work_uri=base_uri.replace("/Edit/","/Delete/Group/");
        
        $.post(work_uri,{groupName:name},function( data ) {

            if(data.indexOf("true")!=-1)
            {
                $("#edit_groups option:selected").remove();
                $("#edit_view").html("");

            } else {
                if(name.indexOf("ADMIN")!=-1)
                {
                    alert("Du kan inte ta bort "+name+" gruppen");
                } else {
                    alert("Lyckades inte ta bort gruppen av någon anledning");
                }
            }
        });
    }
}

function Create(name)
{
    var base_uri=document.location.href.replace("http://"+document.domain,'');
    var base_uri=base_uri.replace("#","");
    var work_uri=base_uri.replace("/Edit/","/Create/Group/");
    
    if(name!="")
    {
        $.post(work_uri,{groupName:name},function( data ) {
            id=data;
            
            //if recieved data is not null then continue
            if(!isNaN(data))
            {
                if(data!="false")
                {
                    $.each({id:name }, function(key, value) {  
                        $('#edit_groups')
                             .append($('<option>', { value : data })
                             .text(value)); 
                    });
                } else {
                    alert("Du har inga rättigheter för detta!");
                }
            } else {
                alert("Du har inga rättigheter för detta!");
            }
        
        }, "text"); 
        
    }
}

function Edit(name)
{
    var base_uri=document.location.href.replace("http://"+document.domain,'');
    var base_uri=base_uri.replace("#","");
    var work_uri=base_uri+"Groups"+"/";
    if(name!=0)
    {
        $.post(work_uri,{id:name},function( data ) {
        $("#edit_view").html(data);
        }, "text");
    } else {
        $("#edit_view").html("");
    }
}

