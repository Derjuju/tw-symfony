$(function() {
    
    initialiseCookie();
    
    initialiseSliderHeader();
    
    initialiseSearchSelectorBar();
    /*
    *
    * Datepicker pour formulaires
    *
    */
    $('.input-group.date').datetimepicker({ format: 'DD/MM/YYYY' });
    
    /* Si la barre doit être statique */

    var data = $('.static-search').data('default');

    if (data) {
      displaySearchForm(data);
      $('.btn-search-'+data).addClass('active');
    }
    
    if($('body').hasClass('inscription')){
        initialiseBoutonAvatar();
    }
    if($('body').hasClass('modifier-profil')){
        initialiseBoutonAvatar();
    }
    if($('body').hasClass('ajouter-bouteille')){
        initialiseBoutonBouteille();
    }
    if($('body').hasClass('editer-bouteille')){
        initialiseBoutonBouteille();
    }    
    if($('body').hasClass('fiche-bouteille')){
        initialiseFicheBouteille();
    }    
});

function initialiseSliderHeader(){
    $('.bxslider').bxSlider({
        pager: false,
        controls: false,
        auto: true
    });
}

function initialiseSearchSelectorBar(){
    $('.btn-search-bottle').on('click', function(event) {
        if(!$(this).hasClass('useLink')){
            event.preventDefault();
            if($(this).hasClass('active')){
                if(!$('body').hasClass('search')){
                    $(this).removeClass('active');
                    displaySearchForm("none");
                }
            }else{
                $(this).parent().children().removeClass('active');
                $(this).addClass('active');
                displaySearchForm("bottle");
            }
        }
    });

    $('.btn-search-swapper').on('click', function(event) {
        if(!$(this).hasClass('useLink')){
            event.preventDefault();        
            if($(this).hasClass('active')){
                if(!$('body').hasClass('search')){
                    $(this).removeClass('active');
                    displaySearchForm("none");
                }
            }else{      
                $(this).parent().children().removeClass('active');
                $(this).addClass('active');
                displaySearchForm("swapper");
            }
        }
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
    $('.form-swapper-search').removeClass('show');
    $('.form-bottle-search').addClass('show');
    $('.search-bar-toggle').addClass('unfold');
  } else if(searchForm === "swapper") {
    $('.form-bottle-search').removeClass('show');
    $('.form-swapper-search').addClass('show');
    $('.search-bar-toggle').addClass('unfold');
  } else {
      if($('body').hasClass('homepage')){
        $('.form-swapper-search').removeClass('show');
        $('.form-bottle-search').removeClass('show');          
      }
        $('.search-bar-toggle').removeClass('unfold');      
  }
}

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

function initialiseBoutonBouteille(){
    $('.wine-image .upload-btn').on('click', function(e) {
        event.preventDefault();
        document.getElementById('file_picker').click();
    });

    $('#file_picker').on('change', function(){
        var target = $(this).data('classDest');
        displayImage(this, '.' + target);
    });
}

function confirmSuppression(){
    return confirm('Voulez-vous supprimer définitivement cette bouteille ?');
}

function confirmTrocSuppression(){
    return confirm('Voulez-vous supprimer définitivement ce troc ?');
}


function initialiseFicheBouteille(){    
    $('.bottle-info-wrapper .wine-image').on('click', function (){        
       jQuery.fancybox({
                //'orig'			: $(this),
                'padding'		: 0,
                'href'			: $(this).data('imagezoom'),
                'title'   		: '',
                'transitionIn'	: 'elastic',
                'transitionOut'	: 'elastic'
        });
    });
}

function initialiseCookie(){
    if(readCookie('accepteCookie')==null){ 
        $('#cookie-warn').hide();     
        $('#cookie-warn').removeClass('hidden'); 
        $('#cookie-warn').show(500);
    }
}
function confirmCookies(){
    // valide cookie pour 30 jours
    createCookie('accepteCookie',true,30);
    $('#cookie-warn').hide(500);
    return false;
}