document.addEventListener('DOMContentLoaded', () => {
    const nombreJoueursInput = document.getElementById('nombre_joueurs');
    const submitButton = document.getElementById('submit-button');
    const errorMessage = document.getElementById('error-message');
    let totalSelectedCards = 0;
    const carteNomAffichage = document.getElementById('carte-nom-affichage');

    // Gérer le survol des cartes de filtrage et des compositions
    function setupHoverEffect() {
        document.querySelectorAll('.carte-item').forEach(carte => {
            carte.addEventListener('mouseover', (e) => {
                const carteNom = e.currentTarget.querySelector('.carte-image').alt;
                carteNomAffichage.textContent = carteNom;
                carteNomAffichage.style.display = 'block';
            });

            carte.addEventListener('mouseout', () => {
                carteNomAffichage.style.display = 'none';
            });
        });
    }
    setupHoverEffect();

    // Code existant pour la logique de filtrage et le comptage des cartes
    document.querySelectorAll('.carte').forEach((carte) => {
        const countElem = carte.querySelector('.card-count');
        const inputElem = carte.querySelector('input[type="hidden"]');
        const initialCount = parseInt(inputElem.value) || 0;

        totalSelectedCards += initialCount;
        countElem.textContent = initialCount;

        if (initialCount > 0) {
            countElem.style.display = 'block';
        }

        carte.dataset.count = initialCount;
    });

    const updateSubmitButtonState = () => {
        const maxCards = parseInt(nombreJoueursInput.value);
        if (submitButton) {
            if (totalSelectedCards === maxCards) {
                submitButton.disabled = false;
                errorMessage.style.display = 'none';
            } else {
                submitButton.disabled = true;
                errorMessage.style.display = (totalSelectedCards > maxCards) ? 'block' : 'none';
            }
        }
    };
    updateSubmitButtonState();

    document.querySelectorAll('.btn-increase').forEach((btnIncrease) => {
        btnIncrease.addEventListener('click', (e) => {
            const carte = e.target.closest('.carte');
            const countElem = carte.querySelector('.card-count');
            const inputElem = carte.querySelector('input[type="hidden"]');
            let count = parseInt(carte.dataset.count) || 0;
            const maxCards = parseInt(nombreJoueursInput.value);

            if (totalSelectedCards < maxCards) {
                count++;
                countElem.textContent = count;
                carte.dataset.count = count;
                inputElem.value = count;
                totalSelectedCards++;
                updateSubmitButtonState();

                if (count > 0) {
                    countElem.style.display = 'block';
                }
            }
        });
    });

    document.querySelectorAll('.btn-decrease').forEach((btnDecrease) => {
        btnDecrease.addEventListener('click', (e) => {
            const carte = e.target.closest('.carte');
            const countElem = carte.querySelector('.card-count');
            const inputElem = carte.querySelector('input[type="hidden"]');
            let count = parseInt(carte.dataset.count) || 0;

            if (count > 0) {
                count--;
                countElem.textContent = count;
                carte.dataset.count = count;
                inputElem.value = count;
                totalSelectedCards--;
                updateSubmitButtonState();

                if (count === 0) {
                    countElem.style.display = 'none';
                }
            }
        });
    });

    if (nombreJoueursInput) {
        nombreJoueursInput.addEventListener('change', filterCompositions);
    }

    function filterCompositions() {
        const selectedCards = document.querySelectorAll('.filter-card.selected');
        const playerCount = parseInt(nombreJoueursInput.value) || null;

        document.querySelectorAll('#compositions-list .composition').forEach(composition => {
            composition.style.display = '';  // Affiche par défaut toutes les compositions
        });

        if (selectedCards.length === 0 && !playerCount) {
            return;
        }

        document.querySelectorAll('#compositions-list .composition').forEach(composition => {
            const compositionPlayerCount = parseInt(composition.getAttribute('data-player-count'));
            const compositionCards = composition.querySelectorAll('.composition-carte');

            const matchesCards = Array.from(compositionCards).some(compositionCard => {
                return Array.from(selectedCards).some(selectedCard =>
                    compositionCard.getAttribute('data-id') === selectedCard.getAttribute('data-card-id')
                );
            });

            const matchesPlayers = !playerCount || compositionPlayerCount === playerCount;

            if ((selectedCards.length > 0 && !matchesCards) || (playerCount && !matchesPlayers)) {
                composition.style.display = 'none';
            }
        });
    }

    function setupFilterCards() {
        const filterCards = document.querySelectorAll('.filter-card');

        filterCards.forEach(card => {
            card.addEventListener('click', function() {
                card.classList.toggle('selected');
                filterCompositions();
            });
        });
    }

    setupFilterCards();
});
