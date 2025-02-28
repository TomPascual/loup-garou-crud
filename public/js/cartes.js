document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    const nameDisplay = document.getElementById('card-name');
    const descriptionDisplay = document.getElementById('card-description');
    const closeButton = document.getElementById('close-button');
    const container = document.querySelector('.cards-container');
    let activeCard = null;

    cards.forEach(card => {
        card.addEventListener('click', function() {
            if (activeCard) return;

            const cardName = card.getAttribute('data-nom');
            const cardDescription = card.getAttribute('data-description');

            nameDisplay.textContent = cardName;
            descriptionDisplay.textContent = cardDescription;

            container.classList.add('blur');
            card.classList.add('active');
            closeButton.style.display = 'block';
            document.getElementById('description-display').style.display = 'block';

            activeCard = card;
        });
    });

    closeButton.addEventListener('click', function() {
        if (!activeCard) return;

        container.classList.remove('blur');
        activeCard.classList.remove('active');
        closeButton.style.display = 'none';
        document.getElementById('description-display').style.display = 'none';

        activeCard = null;
    });
});
