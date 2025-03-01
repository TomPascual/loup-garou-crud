<?php 
if (!isset($carteModel)) { 
    $carteModel = new Carte(Database::getInstance()->getConnection()); 
} 
?>

<?php 
if (!isset($compositionModel)) { 
    $compositionModel = new Composition(Database::getInstance()->getConnection()); 
} 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Compositions</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css">
    <link rel="stylesheet" href="/loup-garou-crud/public/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/loup-garou-crud/public/js/composition.js"></script>
</head>
<body>
    <header>
        <nav>
            <a href="/loup-garou-crud/public/index.php">Compositions</a> | 
            <a href="/loup-garou-crud/public/index.php?action=cartes">Cartes</a> |
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="?action=logout">Déconnexion (<?= htmlspecialchars($_SESSION['pseudo']) ?>)</a>
            <?php else: ?>
                <a href="?action=login">Connexion</a> | 
                <a href="?action=register">Créer un compte</a>
            <?php endif; ?>
        </nav>
    </header>

    <h1>Liste des Compositions</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/loup-garou-crud/public/index.php?action=create_composition" class="btn">Nouvelle Composition</a>



    <?php endif; ?> 

    <section id="top-liked-compositions">
        <h2>Les 5 compositions les plus aimées</h2>
        <div class="compositions-container">
            <?php if (!empty($topLikedCompositions)): ?>
                <?php foreach ($topLikedCompositions as $composition): ?>
                    <div class="composition uniform-size">
                        <h2><?= htmlspecialchars($composition['nom']) ?> (<?= htmlspecialchars($composition['nombre_joueurs']) ?> joueurs)</h2>
                        <p><?= htmlspecialchars($composition['description']) ?></p>
                        <p><strong>Posté par :</strong> <?= isset($composition['utilisateur']) ? htmlspecialchars($composition['utilisateur']) : 'Inconnu' ?></p>
                        <p>J'aime : <?= isset($composition['likes']) ? htmlspecialchars($composition['likes']) : 0 ?></p>

                        <div class="cartes-conteneur">
                            <?php 
                            $cartes = json_decode($composition['cartes'], true);
                            foreach ($cartes as $carte_id => $quantity):
                                if ($quantity > 0): 
                                    if (isset($carteModel)) { 
                                        $carte = $carteModel->getCarteById($carte_id);
                                        if ($carte): ?>
                                            <div class="carte-item composition-carte" data-id="<?= htmlspecialchars($carte['id']); ?>">
                                                <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                                <div class="carte-quantity"><?= $quantity ?></div>
                                            </div>
                                        <?php endif; 
                                    }
                                endif;
                            endforeach; ?>
                        </div>
                        <div class="like-button-container">
                            <?php
                            // Vérifie que $compositionModel est bien défini
                            $userLiked = false;
                            if (isset($compositionModel) && isset($_SESSION['user_id'])) {
                                $userLiked = $compositionModel->hasUserLiked($composition['id'], $_SESSION['user_id']); 
                            }
                            ?>
                            <form method="POST" action="/loup-garou-crud/public/index.php?action=like" style="display: inline;">
                                <input type="hidden" name="composition_id" value="<?= $composition['id'] ?>">
                                <button type="submit" class="btn <?= $userLiked ? 'btn-liked' : 'btn-frame' ?>">
                                    <?= $userLiked ? 'Aimé' : 'J\'aime' ?>
                                </button>
                            </form>
                        </div>
                        <!-- Vérification des permissions de l'utilisateur -->
                    <?php if ((isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || 
                            (isset($_SESSION['user_id']) && $compositionModel->isAuthor($_SESSION['user_id'], $composition['id']))): ?>
                        <div class="edit-delete-buttons">
                            <a href="/loup-garou-crud/public/index.php?action=edit_composition&id=<?= $composition['id']; ?>" class="btn btn-edit">Modifier</a>
                            <a href="/loup-garou-crud/public/index.php?action=delete_composition&id=<?= $composition['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette composition ?');">Supprimer</a>
                        </div>
                    <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune composition disponible</p>
            <?php endif; ?>
        </div>
    </section>

    <div class="filters-container" style="display: flex; justify-content: space-between;">
        <section id="filter-by-cards">
            <h2>Filtrer par cartes</h2>
            <div class="cartes-selection">
                <?php if (!empty($cartesDisponibles)): ?>
                    <?php
                    $order = ['Villageois', 'Loup-Garou', 'Neutre'];
                    $sortedCards = ['Villageois' => [], 'Loup-Garou' => [], 'Neutre' => []];

                    foreach ($cartesDisponibles as $carte) {
                        $role = $carte['role'] ?? 'Neutre';
                        if (in_array($role, $order)) {
                            $sortedCards[$role][] = $carte;
                        } else {
                            $sortedCards['Neutre'][] = $carte;
                        }
                    }

                    foreach ($order as $role) {
                        foreach ($sortedCards[$role] as $carte): ?>
                            <div class="carte-item filter-card" data-card-id="<?= htmlspecialchars($carte['id']) ?>" data-role="<?= htmlspecialchars($carte['role'] ?? 'Neutre') ?>">
                                <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                            </div>
                        <?php endforeach;
                    }
                    ?>
                <?php else: ?>
                    <p>Aucune carte disponible pour le filtrage.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <section id="filter-by-players">
        <h2>Filtrer par nombre de joueurs</h2>
        <select id="nombre_joueurs">
            <option value="">Tous</option>
            <?php for ($i = 5; $i <= 30; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?> joueurs</option>
            <?php endfor; ?>
        </select>   
    </section>

    <section id="compositions-list">
        <div class="compositions-container">
            <?php if (!empty($compositionsAlphabetical)): ?>
                <?php foreach ($compositionsAlphabetical as $composition): ?>
                    <div class="composition uniform-size" data-player-count="<?= htmlspecialchars($composition['nombre_joueurs']) ?>">
                        <h2><?= htmlspecialchars($composition['nom']) ?> (<?= htmlspecialchars($composition['nombre_joueurs']) ?> joueurs)</h2>
                        <p><?= htmlspecialchars($composition['description']) ?></p>
                        <p><strong>Posté par :</strong> <?= isset($composition['utilisateur']) ? htmlspecialchars($composition['utilisateur']) : 'Inconnu' ?></p>
                        <p>J'aime : <?= isset($composition['likes']) ? htmlspecialchars($composition['likes']) : 0 ?></p>

                        <div class="cartes-conteneur">
                            <?php 
                            $cartes = json_decode($composition['cartes'], true);
                            foreach ($cartes as $carte_id => $quantity):
                                if ($quantity > 0): 
                                    if (isset($carteModel)) { 
                                        $carte = $carteModel->getCarteById($carte_id);
                                        if ($carte): ?>
                                            <div class="carte-item composition-carte" data-id="<?= htmlspecialchars($carte['id']); ?>">
                                                <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                                <div class="carte-quantity"><?= $quantity ?></div>
                                            </div>
                                        <?php endif; 
                                    }
                                endif;
                            endforeach; ?>
                        </div>
                        
                        <div class="like-button-container">
                            <?php
                            // Vérifie que $compositionModel est bien défini
                            $userLiked = false;
                            if (isset($compositionModel) && isset($_SESSION['user_id'])) {
                                $userLiked = $compositionModel->hasUserLiked($composition['id'], $_SESSION['user_id']); 
                            }
                            ?>
                            <form method="POST" action="/loup-garou-crud/public/index.php?action=like" style="display: inline;">
                                <input type="hidden" name="composition_id" value="<?= $composition['id'] ?>">
                                <button type="submit" class="btn <?= $userLiked ? 'btn-liked' : 'btn-frame' ?>">
                                    <?= $userLiked ? 'Aimé' : 'J\'aime' ?>
                                </button>
                            </form>
                        </div>
                        <!-- Vérification des permissions de l'utilisateur -->
                    <?php if ((isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || 
                            (isset($_SESSION['user_id']) && $compositionModel->isAuthor($_SESSION['user_id'], $composition['id']))): ?>
                        <div class="edit-delete-buttons">
                            <a href="/loup-garou-crud/public/index.php?action=edit_composition&id=<?= $composition['id']; ?>" class="btn btn-edit">Modifier</a>
                            <a href="/loup-garou-crud/public/index.php?action=delete_composition&id=<?= $composition['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette composition ?');">Supprimer</a>
                        </div>
                    <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune composition disponible.</p>
            <?php endif; ?>
        </div>
    </section>

    <div id="carte-nom-affichage"></div>

</body>
</html>
