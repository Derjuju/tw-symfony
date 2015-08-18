/*
Utilisation :
Structure HTML :
<div class="paginate">
    <div class="links"></div>
    <div class="records">
        <div class="recordlist">
            <p>a record</p>
            <p>a record</p>
            <p>a record</p>
        </div>
        <div class="recordlist">
            <p>another record</p>
            <p>another record</p>
            <p>another record</p>
        </div>
        <div class="recordlist">
            <p>another record</p>
            <p>another record</p>
            <p>another record</p>
        </div>
    </div>
    <div class="links"></div>
</div>

Javascript :
$('.paginate').paginate();

Options :
currentPageClass : classe appliquée aux liens de la page courante (par défaut : activeLink)
effectName : un effet jQuery UI (fade, bounce, slide...) (par défaut : fade)
effectEasing : nom de la fonction d'easing à utiliser pour l'effet
effectDuration : nombre de ms ou string (slow, fast...) représentant la durée de l'effet
effectComplete : fonction de callback à apeller une fois l'effet terminé

Le plugin ne s'occupe pas de diviser les données.
Il manipule simplement le DOM pour afficher ou cacher les éléments.
*/
(function($) {
    $.fn.paginate = function(options) {
        return this.each(function(index, element) {
            var defaults = {
                'currentPageClass': 'activeLink',
                'effectName': 'fade',
                'effectEasing': 'swing',
                'effectDuration': 400,
                'effectComplete': function() {}
            };

            params = $.extend(defaults, options);

            var $element = $(element), // élément en cours
                $records = $element.find('.records'), // div où sont affichés les résultats
                $recordlist = $records.find('.recordlist'), // pages
                nbLinks = $recordlist.length, // nombre de pages
                $links = $element.find('.links'),
                currentPageNumber = 0;

            // s'il y a besoin de paginer
            if (nbLinks > 1) {
                // on cache tout, sauf la première page
                $recordlist.hide();
                $($recordlist[0]).show();

                // on construit la liste des liens
                for (var i = 0, linksStr = ''; i < nbLinks; ++i) {
                    linksStr += '<a href="#" class="' + (i == 0 ? params.currentPageClass : '') + '" data-pagenumber="' + i + '">' + (i + 1) + '</a>';
                }

                // on l'insère
                $links.append(linksStr);

                $links.on('click.paginate', 'a', function(event) {
                    event.preventDefault();

                    var pageNumber = $(event.target).attr('data-pagenumber');

                    // si la page demandée n'est pas la page actuelle
                    if (pageNumber != currentPageNumber) {
                        currentPageNumber = pageNumber;

                        // on affiche la bonne page
                        $recordlist.hide();
                        $($recordlist[pageNumber]).effect({
                            'effect': params.effectName,
                            'easing': params.effectEasing,
                            'duration': params.effectDuration,
                            'complete': params.effectComplete
                        });

                        // on change le numéro de page courante dans les liens
                        $links.find('.' + params.currentPageClass).removeClass(params.currentPageClass);
                        $links.find('[data-pagenumber=' + pageNumber + ']').addClass(params.currentPageClass);
                    }
                });
            }
        });
    };
})(jQuery);
