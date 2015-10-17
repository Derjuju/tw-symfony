$(function() {
    
    initialiseSliderHeader();
    /*
    *
    * Datepicker pour formulaires
    *
    */
    $('.input-group.date').datetimepicker({ format: 'DD/MM/YYYY' });
    
    /* Si la barre doit être statique */

    var data = $('.static-search').data('default');

    if (data) {
      console.log(data);
      displaySearchForm(data);
      $('.btn-search-bottle').addClass('active');
    }
    
    if($('body').hasClass('inscription')){
        initialiseBoutonAvatar();
    }
    if($('body').hasClass('modifier-profil')){
        initialiseBoutonAvatar();
    }
});

function initialiseBoutonAvatar(){
    $('.upload-btn').on('click', function(e) {
        event.preventDefault();
        document.getElementById('file_picker').click();
    });

    $('#file_picker').on('change', function(){
        var target = $(this).data('classDest');
        displayImage(this, '.' + target);
    });
}

function initialiseSliderHeader(){
    $('.bxslider').bxSlider({
        pager: false,
        controls: false,
        auto: true
    });
}




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

function displaySearchForm(searchForm) {
  if(searchForm === "bottle") {
    $('.form-swapper-search')
      .removeClass('show');

    $('.form-bottle-search')
      .addClass('show');

    $('.search-bar-toggle')
      .addClass('unfold');

  } else if(searchForm === "swapper") {
    $('.form-bottle-search')
      .removeClass('show');

    $('.form-swapper-search')
      .addClass('show');

    $('.search-bar-toggle')
      .addClass('unfold');
  }
}