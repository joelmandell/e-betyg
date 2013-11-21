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
    Create(name);
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
            
            if(data.contains("true"))
            {
                $("#edit_groups option:selected").remove();
                $("#edit_view").html("");

            } else {
                if(name.contains("ADMIN"))
                {
                    alert("Du kan inte ta bort "+name+" gruppen");
                } else {
                    alert("Lyckades inte ta bort gruppen av någon anledning");
                }
            }
        });
    } else {
        alert("You ARE A COWARDDD!");
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
            $.each({id:name }, function(key, value) {   
                $('#edit_groups')
                     .append($('<option>', { value : key })
                     .text(value)); 
           });
        
        
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

