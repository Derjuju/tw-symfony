$(function() {
    
       
    if($('#file_picker').length > 0){
        initialiseUploadFile();
    }
    
    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    // popover demo
    $("[data-toggle=popover]")
        .popover()
    
});

function displayImage(input, target) {

  if(input.files && typeof input.files[0] !== 'undefined') {
    var profileImage = $(target);

    var reader = new FileReader();

    reader.onload = function(e) {
      profileImage
        .css('background-image', 'url(' + e.target.result + ')');
    };

    reader.readAsDataURL(input.files[0]);
  }
}

function initialiseUploadFile(){

    $('#file_picker').on('change', function(){
        var target = $(this).data('classDest');
        displayImage(this, '.' + target);
    });
    
}