<?php
/**
 * Ce fichier affiche la liste des compositions filtrées, avec leurs informations principales et les cartes associées.
 * Si aucune composition ne correspond aux critères, un message est affiché.
 */
?>

<?php if (!empty($filteredCompositions)): ?>
    <div class="compositions-container">
        <?php foreach ($filteredCompositions as $composition): ?>
            <div class="composition uniform-size">
                <!-- Titre de la composition avec le nombre de joueurs -->
                <h2><?= htmlspecialchars($composition['nom']) ?> (<?= htmlspecialchars($composition['nombre_joueurs']) ?> joueurs)</h2>
                <!-- Description de la composition -->
                <p><?= htmlspecialchars($composition['description']) ?></p>
                <!-- Auteur de la composition -->
                <p><strong>Posté par :</strong> <?= htmlspecialchars($composition['utilisateur']) ?></p>
                <!-- Nombre de "likes" pour la composition -->
                <p>J'aime : <?= isset($composition['likes']) ? htmlspecialchars($composition['likes']) : 0 ?></p>

                <!-- Affichage des cartes associées à la composition -->
                <div class="cartes">
                    <?php 
                    // Décoder les cartes associées à la composition (stockées en JSON)
                    $cartes = json_decode($composition['cartes'], true);
                    foreach ($cartes as $carte_id => $quantity):
                        if ($quantity > 0): 
                            // Récupérer les informations de la carte associée
                            $carte = $carteModel->getCarteById($carte_id);
                            if ($carte): ?>
                                <div class="carte-item">
                                    <!-- Affichage de l'image de la carte -->
                                    <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                    <!-- Quantité de cartes de ce type dans la composition -->
                                    <div class="carte-quantity"><?= $quantity ?></div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <!-- Message affiché si aucune composition ne correspond aux critères de filtre -->
    <p>Aucune composition ne correspond à ces critères.</p>
<?php endif; ?>
