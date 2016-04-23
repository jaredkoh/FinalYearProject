//shows and hides the forms
$(document).ready(function() {
  $('#urlform').find('[name="Type"]').selectpicker();
  $('#Type').change(function(){

    if($(this).val()=="Cryptography"){     
        $('#illurllink').fadeOut();
        $('#email').fadeIn();
        $('#useremail').fadeIn();

    }else if(($(this).val()=="Virus")){
        $('#email').fadeIn();
        $('#useremail').fadeOut();
        $('#illurllink').fadeOut();
    }
    else if(($(this).val()=="Dos")){
        $('#email').fadeOut();
        $('#useremail').fadeOut();
        $('#illurllink').fadeIn();
    }
     else if(($(this).val()=="Password")){
        $('#email').fadeOut();
        $('#useremail').fadeOut();
        $('#illurllink').fadeIn();
    }
      else{
        $('#email').fadeOut();
        $('#illurllink').fadeOut();
        $('#useremail').fadeOut();

      }
});
      
  });
