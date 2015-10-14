$(function() {
    
    initialiseSliderHeader();
    /*
    *
    * Datepicker pour formulaires
    *
    
    $('.input-group.date').datetimepicker({ format: 'DD/MM/YYYY' });
    */
});


function initialiseSliderHeader(){
    $('.bxslider').bxSlider({
        pager: false,
        controls: false,
        auto: true
    });
}