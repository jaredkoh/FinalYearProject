//redirects to the url in the form. 
$(document).ready(function(){
    highlight($("#form"));
    $("#go").click(function(){
        window.location.replace($("#form").val());
    })
    
});

function highlight(field) {
field.focus();
field.select();
}