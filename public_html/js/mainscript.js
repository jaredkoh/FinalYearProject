
$(document).ready(function() {
  $('#urlform').find('[name="Type"]').selectpicker();
  $('#Type').change(function(){
    if($(this).val()=="Cryptography"){
      $('.inputFields').append("<input type='url' class='form-control' name='illurlink' id='illurllink' placeholder='Place your ill url here'required>​");
      $('.inputFields').append("<input type='email' class='form-control' name='email' id='email' placeholder='Place your friend email here'required>​");
    }
    else if($('.inputFields').children().length == 2){
      $('.inputFields input:last-child').remove();
    }

  });
});
