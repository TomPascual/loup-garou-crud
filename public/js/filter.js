$(document).ready(function() {
    var filterParams = {
        card_ids: [],
        nombre_joueurs: ''
    };

    // Filtrage par carte
    $('.carte-item').click(function() {
        var cardId = $(this).data('card-id');
        var $thisCard = $(this);

        // Affichage dans la console pour vérifier l'ajout de la classe "selected"
        console.log("Carte cliquée: ", cardId);

        // Ajouter ou retirer la classe "selected" pour l'élément carte
        if ($thisCard.hasClass('selected')) {
            $thisCard.removeClass('selected');
            console.log("Carte désélectionnée: ", cardId);
            filterParams.card_ids = filterParams.card_ids.filter(function(id) {
                return id !== cardId;
            });
        } else {
            $thisCard.addClass('selected');
            console.log("Carte sélectionnée: ", cardId);
            filterParams.card_ids.push(cardId);
        }

        updateCompositions(); // Actualiser les compositions avec le filtre
    });

    // Filtrage par nombre de joueurs
    $('#nombre_joueurs').change(function() {
        filterParams.nombre_joueurs = $(this).val();
        updateCompositions();
    });

    // Fonction pour envoyer une requête AJAX et mettre à jour les compositions
    function updateCompositions() {
        $.ajax({
            url: '/loup-garou-crud/public/index.php?action=filter',
            method: 'GET',
            data: filterParams,
            success: function(response) {
                $('#compositions-list').html(response); // Remplacement du contenu par les résultats filtrés
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors de la mise à jour des compositions:", error);
            }
        });
    }
});
    