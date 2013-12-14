
$('#edit_pending_options').on('change', function() {
    var base_uri=document.location.href.replace("http://"+document.domain,'');
    var base_uri=base_uri.replace("#","");
    var work_uri=base_uri.replace("/Docs/","/Docs/PendingCorrection/");

    var id = $('option:selected', this).attr('value');  

    $.post(work_uri,{groupId:id},function( data ) {
        var ar=JSON.parse(data);
        var html="";
        for(a in ar)
        {
            html+="<strong>"+a+"</strong> - \""+ar[a]+"\"<br />";
        }
        $("#doc_view").html(html)

    }, "text");
});