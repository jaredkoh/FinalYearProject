
$(document).ready(function(){
    highlight($("#form"));
    $("#copy").click(function(){
      var text = $('form').val();
      document.execcommand("copy");

    })
});


function highlight(field) {
field.focus();
field.select();
}
