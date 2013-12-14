if ("ontouchstart" in document.documentElement)
{
    var browserEvent = "touchstart";
}
else
{
    var browserEvent = "click";
}

$('#edit_activation').on('change', function() {
    var name = $('option:selected', this).attr('value');  
    if(name!="0")
    {
        $("#activate_to_group").show();
    } else {
        $("#activate_to_group").hide();
    }
});