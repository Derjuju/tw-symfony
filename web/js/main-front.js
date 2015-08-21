/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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

$(function() {
    /*
    *
    * Datepicker pour formulaires
    *
    */
    $('.input-group.date').datetimepicker({ format: 'DD/MM/YYYY' });

});