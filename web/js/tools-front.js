/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var xhr;
var ajaxModalMessagesQueue;
var twitter;

function showLogin()
{
    //$('#pannel_login').show();
    $('#loginModal').modal('show');
}

function showLoginAndClose()
{
    fermeMenuSmartphone();
    showLogin();
}

function ouvreMenuSmartphone(){
    $('#MenuMobile').show(250);
    $('.search-selector').css('z-index',97);
    $('.home-header-nav-close').on('click', function(event){
        event.preventDefault();
        fermeMenuSmartphone();
    });
}
function fermeMenuSmartphone(){
    $('#MenuMobile').hide(250);
    $('.search-selector').css('z-index',99);
}



function switchPanelHome(cible)
{
    $('.boxResearch .panelHome').hide();
    $('.boxResearch .'+cible).show(500);
    $('.boxResearchChoice a.active').removeClass('active');
    $(this).addClass('active');
}
function displayLoadingWheel() {
    return '<img src="/img/loader-ajax-wb.gif" class="loadingWheel">';
}
function displayLoadingWheelFondBlanc() {
    return '<img src="/img/loader-ajax-bw.gif" class="loadingWheel">';
}

function checkAjaxModal() {

    if (xhr && xhr.readystate != 4) {
        xhr.abort();
    }

    xhr = $.ajax({
        type: "POST",
        url: '/ajax/flashbags',
        dataType: "json",
        success: function (json)
        {
            ajaxModalMessagesQueue = new Array();
            // priorité 1 : les erreurs
            if (json.error.length > 0) {
                var messages = "";
                for (var i = 0; i < json.error.length; i++) {
                    messages += json.error[i] + '<br>';
                }
                ajaxModalMessagesQueue.push({'type': 'error', 'messages': messages});
            }
            // priorité 2 : les warnings
            if (json.warning.length > 0) {
                var messages = "";
                for (var i = 0; i < json.warning.length; i++) {
                    messages += json.warning[i] + '<br>';
                }
                ajaxModalMessagesQueue.push({'type': 'warning', 'messages': messages});
            }
            // priorité 3 : les notices
            if (json.notice.length > 0) {
                var messages = "";
                for (var i = 0; i < json.notice.length; i++) {
                    messages += json.notice[i] + '<br>';
                }
                ajaxModalMessagesQueue.push({'type': 'notice', 'messages': messages});
            }
            // priorité 4 : les succès
            if (json.success.length > 0) {
                var messages = "";
                for (var i = 0; i < json.success.length; i++) {
                    messages += json.success[i] + '<br>';
                }
                ajaxModalMessagesQueue.push({'type': 'success', 'messages': messages});
            }
            if (ajaxModalMessagesQueue.length > 0) {
                displayFlashModal(ajaxModalMessagesQueue[0]['type'], ajaxModalMessagesQueue[0]['messages']);
            }
        }
    });
    return false;
}
function displayFlashModal(typeMessage, message) {
    if (ajaxModalMessagesQueue.length > 0) {
        ajaxModalMessagesQueue.shift()
    }
    if ((typeMessage == 'success') || (typeMessage == 'notice')) {
        $('#flashBagModal').addClass('green');
    } else {
        $('#flashBagModal').removeClass('green');
    }
    if (typeMessage == 'success') {
        $('#flashBagModal h4').html('Confirmation');
        $('#flashBagModal .modal-body').html('<div class="flash-success">' + message + '</div>');
    }
    if (typeMessage == 'notice') {
        $('#flashBagModal h4').html('Information');
        $('#flashBagModal .modal-body').html('<div class="flash-notice">' + message + '</div>');
    }
    if (typeMessage == 'warning') {
        $('#flashBagModal h4').html('Alerte');
        $('#flashBagModal .modal-body').html('<div class="flash-warning">' + message + '</div>');
    }
    if (typeMessage == 'error') {
        $('#flashBagModal h4').html('Erreur');
        $('#flashBagModal .modal-body').html('<div class="flash-error">' + message + '</div>');
    }
    $('#flashBagModal .modal-body').append('<div class="text-right"><button type="submit" class="btn submit" data-dismiss="modal">OK</button></div>');

    $('#flashBagModal').modal('show');
}

$(document).on('hidden.bs.modal', '#flashBagModal', function () {
    if (ajaxModalMessagesQueue)
    {
        if (ajaxModalMessagesQueue.length > 0) {
            displayFlashModal(ajaxModalMessagesQueue[0]['type'], ajaxModalMessagesQueue[0]['messages']);
        }
    }
});

function ajaxRefreshHTML(url, target, data, modalId){
    if (typeof modalId === "undefined" || modalId === null) { 
        modalId = "#myModal"; 
    }
    if (typeof data === "undefined" || data === null) { 
        data = null; 
    }
    $(modalId).empty().html(displayLoadingWheel());
    $(modalId).modal('show');
    $.ajax({
        type : 'POST',
        url : url,
        data : data,
        timeout : 20000,
        success : function(htmlResponse) {

            $(modalId).empty().html();                 
            $(modalId).modal('toggle');
            $(target).empty().html(htmlResponse);                               
            checkAjaxModal();  
            
            //re-render the facebook icons (in a div with id of 'content') 
            var idDom = target.substring(1);
            FB.XFBML.parse(document.getElementById(idDom));
            twttr.widgets.load();
        },
        error : function(jqXHR, textStatus, errorThrown) {
            $(modalId).empty().html('Erreur de traitement...');                             
            checkAjaxModal();
        }
    });
}

function ajaxRefreshHTMLResults(url, target, data, modalId){
    if (typeof modalId === "undefined" || modalId === null) { 
        modalId = "#myModal"; 
    }
    if (typeof data === "undefined" || data === null) { 
        data = null; 
    }
    $(modalId).empty().html(displayLoadingWheel());
    $(modalId).modal('show');
    $.ajax({
        type : 'POST',
        url : url,
        data : data,
        timeout : 20000,
        success : function(htmlResponse) {

            $(modalId).empty().html();                 
            $(modalId).modal('toggle');
            $(target).empty().replaceWith(htmlResponse);   
            
            if($(target).hasClass('bottle-list')){
                var total = $(target).attr('data-total-bottle');
                $('.search-results-wrapper .red.total-bottle').html(total);
                if(total>1){
                    $('.label-result-bottle-plural').html('s');
                }else{
                    $('.label-result-bottle-plural').empty();                
                }
                var totalRef = $(target).attr('data-total-ref');
                $('.search-results-wrapper .red.total-ref').html(totalRef);
                if(totalRef>1){
                    $('.label-result-ref-plural').html('s');
                }else{
                    $('.label-result-ref-plural').empty();                
                }
            }else if($(target).hasClass('swapper-list')){
                var totalTroqueur = $(target).attr('data-total-troqueur');
                $('.search-results-wrapper .red.total-troqueur').html(totalTroqueur);
                if(totalTroqueur>1){
                    $('.label-result-troqueur-plural').html('s');
                }else{
                    $('.label-result-troqueur-plural').empty();                
                }
            }else {
                var total = $(target).attr('data-total');
                $('.search-results-wrapper .red').html(total);
                if(totalTroqueur>1){
                    $('.label-result-plural').html('s');
                }else{
                    $('.label-result-plural').empty();                
                }
            }
            
            $('#panelInfoResultat').removeClass('hidden'); 
            
            checkAjaxModal();  
            
            //re-render the facebook icons (in a div with id of 'content') 
            var idDom = target.substring(1);
            FB.XFBML.parse(document.getElementById(idDom));
            if (typeof (twttr) != 'undefined') {
                twttr.widgets.load();
            } else {
                $.getScript('http://platform.twitter.com/widgets.js');
            }
        },
        error : function(jqXHR, textStatus, errorThrown) {
            $(modalId).empty().html('Erreur de traitement...');                             
            checkAjaxModal();
        }
    });
}




var map;
var map_tw;
var bigMap = [];
var bigMap_tw = [];
function initMap(){
    map = new google.maps.Map(document.getElementById('bigMap'), {
        center:{lat: 48.8589506, lng: 2.2773456},
        zoom: 16
    });
    map_tw = new google.maps.Map(document.getElementById('bigMap-tw'), {
        center:{lat: 48.8589506, lng: 2.2773456},
        zoom: 16
    });
}

function initialiseBigCarte(latitude, longitude, mapId){
    
    var latLng = {lat: Number(latitude.substring(0,10)), lng: Number(longitude.substring(0,9))};

    bigMap[mapId] = new google.maps.Map(document.getElementById('big-map-'+mapId), {    
            center: latLng,
            zoom: 16,
            scrollwheel: false,
            zoomControl: true,
            zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.LARGE
            }
    });

    var marker = new google.maps.Marker({
            position: latLng,
            map: bigMap[mapId]
    });

}

function getAddressForMap(url, id){
    var modalId = "#myModal";
    $(modalId).empty().html(displayLoadingWheel());
    $(modalId).modal('show');
    
    var data = {'id':id};
    
    $.ajax({
        type : 'POST',
        url : url,
        data : data,
        timeout : 20000,
        success : function(htmlResponse) {

            $(modalId).empty().html();                 
            $(modalId).modal('toggle');
            updateMapFromAddress(htmlResponse); 
        },
        error : function(jqXHR, textStatus, errorThrown) {
            $(modalId).empty().html('Erreur de traitement...');  
        }
    });
}
function updateMapFromAddress(address){
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          map_tw.setCenter(results[0].geometry.location);
          var marker = new google.maps.Marker({
              map: map_tw,
              position: results[0].geometry.location
          });
        } else {
          alert("Geocode was not successful for the following reason: " + status);
        }
    });
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}