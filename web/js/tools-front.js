/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var xhr;
var ajaxModalMessagesQueue;

function showLogin()
{
    //$('#pannel_login').show();
    $('#loginModal').modal('show');
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
        },
        error : function(jqXHR, textStatus, errorThrown) {
            $(modalId).empty().html('Erreur de traitement...');                             
            checkAjaxModal();
        }
    });
}
