if ("ontouchstart" in document.documentElement)
{
    var browserEvent = "touchstart";
}
else
{
    var browserEvent = "click";
}

$("#confirm_user_activation").on(browserEvent, function () {
    var base_uri=document.location.href.replace("http://"+document.domain,'');
    var base_uri=base_uri.replace("#","");
    var work_uri=base_uri.replace("/Edit/","/Activate/User/");
    var really=null; //Will contain possible confirm box value later on.
    
    var id=$("#edit_activation option:selected").val();
    var group=$("#add_to_group option:selected").val();
    
    if(group=="0")
    {
        really=confirm("Om du inte tilldelat någon grupp så kommer användaren vara utan\n\
        grupp och du kommer behöver lägga till användaren skilt. Vill du detta?");
    }
    
    if(really==null || really==true)
    {
        $.post(work_uri,{userId:id,groupId:group},function( data ) {
            if(!isNaN(data))
            {
                $("#edit_activation option:selected").remove();
                $("#edit_activation option[value="+id+"]").prop('selected', true);
                $("#add_to_group option[value='0']").prop("selected",true);
                alert("Användare aktiverad och tillagd i gruppen!");
        
                //When user is added and activated, we select that group from the select list 
                //to show user that the user truly is added!
                $("#edit_groups option[value="+group+"]").prop('selected', true);
                
                //Now when it is selected, we emulate a user change in the list
                //so the items will show up!
                $("#edit_groups").trigger("change");

            } else {
                alert("Gick ej att aktivera användaren av någon anledning.")
            }
        }, "text");
    }
    
});

$('#help_add_user_to_group').on(browserEvent, function () {
  
    alert("Om det är så att du inte väljer en grupp att tilldela användaren så sker endast en aktivering av användaren!");
    
});

function PreferredGroup(name)
{
    var base_uri=document.location.href.replace("http://"+document.domain,'');
    var base_uri=base_uri.replace("#","");
    var work_uri=base_uri.replace("/Edit/","/Preferred/Group/");

    if(name!=0)
    {
        $.post(work_uri,{userId:name},function( data ) {
            if(!isNaN(name))
            {
                if(data!=0)
                { 
                    var group=$("#activate_to_group option[value="+data+"]").text();
                    alert("Vald användare har önskemål om att få bli tillagd i gruppen "+group);
                }
            }
        }, "text");
    } else {
        $("#edit_view").html("");
    }
}


$('#edit_activation').on('change', function() {
    var name = $('option:selected', this).attr('value');  
    if(name!="0")
    {
        $("#activate_to_group").show();
        $("#help_add_user_to_group").show();
        PreferredGroup(name);
    } else {
        $("#activate_to_group").hide();
    }
});