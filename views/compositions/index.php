<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Compositions</title>
    <!-- Lien vers les fichiers CSS pour le style du header et la mise en page générale -->
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css">
    <link rel="stylesheet" href="/loup-garou-crud/public/css/style.css">
    <!-- Inclusion de jQuery pour les interactions JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Script pour la gestion des compositions (JavaScript spécifique à la page) -->
    <script src="/loup-garou-crud/public/js/composition.js"></script>
</head>
<body>
    <header>
        <nav>
            <!-- Liens de navigation vers les compositions et les cartes -->
            <a href="/loup-garou-crud/public/index.php">Compositions</a> | 
            <a href="/loup-garou-crud/public/index.php?action=cartes">Cartes</a> |
            <!-- Gestion de l'affichage des options selon l'état de connexion de l'utilisateur -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="?action=logout">Déconnexion (<?= htmlspecialchars($_SESSION['pseudo']) ?>)</a>
            <?php else: ?>
                <a href="?action=login">Connexion</a> | 
                <a href="?action=register">Créer un compte</a>
            <?php endif; ?>
        </nav>
    </header>

    <h1>Liste des Compositions</h1>
    <!-- Bouton pour créer une nouvelle composition, visible uniquement si l'utilisateur est connecté -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/loup-garou-crud/public/index.php?action=create" class="btn">Nouvelle Composition</a>
    <?php endif; ?> 

    <!-- Section des 5 compositions les plus aimées -->
    <section id="top-liked-compositions">
        <h2>Les 5 compositions les plus aimées</h2>
        <div class="compositions-container">
            <?php foreach ($topLikedCompositions as $composition): ?>
                <div class="composition uniform-size">
                    <h2><?= htmlspecialchars($composition['nom']) ?> (<?= htmlspecialchars($composition['nombre_joueurs']) ?> joueurs)</h2>
                    <p><?= htmlspecialchars($composition['description']) ?></p>
                    <p><strong>Posté par :</strong> <?= htmlspecialchars($composition['utilisateur']) ?></p>
                    <p>J'aime : <?= isset($composition['likes']) ? htmlspecialchars($composition['likes']) : 0 ?></p>

                    <!-- Conteneur pour afficher les cartes associées à la composition -->
                    <div class="cartes-conteneur">
                        <?php 
                        $cartes = json_decode($composition['cartes'], true);
                        foreach ($cartes as $carte_id => $quantity):
                            if ($quantity > 0): 
                                $carte = $carteModel->getCarteById($carte_id);
                                if ($carte): ?>
                                    <div class="carte-item composition-carte" data-id="<?= htmlspecialchars($carte['id']); ?>">
                                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                        <div class="carte-quantity"><?= $quantity ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Boutons pour aimer, modifier ou supprimer la composition -->
                    <div class="like-button-container">
                        <?php
                        $userLiked = $compositionModel->hasUserLiked($composition['id'], $_SESSION['user_id'] ?? null); 
                        ?>
                        <form method="POST" action="/loup-garou-crud/public/index.php?action=like" style="display: inline;">
                            <input type="hidden" name="composition_id" value="<?= $composition['id'] ?>">
                            <button type="submit" class="btn <?= $userLiked ? 'btn-liked' : 'btn-frame' ?>">
                                <?= $userLiked ? 'Aimé' : 'J\'aime' ?>
                            </button>
                        </form>

                        <!-- Afficher les boutons de modification et de suppression uniquement si l'utilisateur est administrateur ou l'auteur de la composition -->
                        <?php if ((isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || 
                            (isset($_SESSION['user_id']) && $compositionModel->isAuthor($_SESSION['user_id'], $composition['id']))): ?>
                            <a href="/loup-garou-crud/public/index.php?action=edit&id=<?= $composition['id']; ?>" class="btn btn-edit">Modifier</a>
                            <a href="/loup-garou-crud/public/index.php?action=delete_composition&id=<?= $composition['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette composition ?');">Supprimer</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Section pour filtrer les compositions par cartes -->
    <div class="filters-container" style="display: flex; justify-content: space-between;">
        <section id="filter-by-cards">
            <h2>Filtrer par cartes</h2>
            <div class="cartes-selection">
                <?php
                // Ordre des rôles pour trier les cartes
                $order = ['Villageois', 'Loup-Garou', 'Neutre'];

                // Regrouper les cartes en fonction de leur rôle
                $sortedCards = ['Villageois' => [], 'Loup-Garou' => [], 'Neutre' => []];
                
                foreach ($cartesDisponibles as $carte) {
                    $role = isset($carte['role']) ? $carte['role'] : 'Neutre';
                    if (in_array($role, $order)) {
                        $sortedCards[$role][] = $carte;
                    } else {
                        $sortedCards['Neutre'][] = $carte;
                    }
                }

                // Afficher les cartes triées par rôle
                foreach ($order as $role) {
                    foreach ($sortedCards[$role] as $carte): ?>
                        <div class="carte-item filter-card" data-card-id="<?= htmlspecialchars($carte['id']) ?>" data-role="<?= htmlspecialchars($carte['role'] ?? 'Neutre') ?>">
                            <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                        </div>
                    <?php endforeach;
                }
                ?>
            </div>
        </section>
    </div>

    <!-- Section pour filtrer les compositions par nombre de joueurs -->
    <section id="filter-by-players">
        <h2>Filtrer par nombre de joueurs</h2>
        <select id="nombre_joueurs">
            <option value="">Tous</option>
            <!-- Affichage des options de 5 à 30 joueurs -->
            <?php for ($i = 5; $i <= 30; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?> joueurs</option>
            <?php endfor; ?>
        </select>   
    </section>

    <!-- Liste des compositions restantes après application des filtres -->
    <section id="compositions-list">
        <div class="compositions-container">
            <?php foreach ($compositionsAlphabetical as $composition): ?>
                <div class="composition uniform-size" data-player-count="<?= htmlspecialchars($composition['nombre_joueurs']) ?>">
                    <h2><?= htmlspecialchars($composition['nom']) ?> (<?= htmlspecialchars($composition['nombre_joueurs']) ?> joueurs)</h2>
                    <p><?= htmlspecialchars($composition['description']) ?></p>
                    <p><strong>Posté par :</strong> <?= htmlspecialchars($composition['utilisateur']) ?></p>
                    <p>J'aime : <?= isset($composition['likes']) ? htmlspecialchars($composition['likes']) : 0 ?></p>

                    <!-- Affichage des cartes de la composition -->
                    <div class="cartes-conteneur">
                        <?php 
                        $cartes = json_decode($composition['cartes'], true);
                        foreach ($cartes as $carte_id => $quantity):
                            if ($quantity > 0): 
                                $carte = $carteModel->getCarteById($carte_id);
                                if ($carte): ?>
                                    <div class="carte-item composition-carte" data-id="<?= htmlspecialchars($carte['id']); ?>">
                                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                        <div class="carte-quantity"><?= $quantity ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Boutons pour aimer, modifier ou supprimer la composition -->
                    <div class="like-button-container">
                        <?php
                        $userLiked = $compositionModel->hasUserLiked($composition['id'], $_SESSION['user_id'] ?? null); 
                        ?>
                        <form method="POST" action="/loup-garou-crud/public/index.php?action=like" style="display: inline;">
                            <input type="hidden" name="composition_id" value="<?= $composition['id'] ?>">
                            <button type="submit" class="btn <?= $userLiked ? 'btn-liked' : 'btn-frame' ?>">
                                <?= $userLiked ? 'Aimé' : 'J\'aime' ?>
                            </button>
                        </form>

                        <!-- Affichage des boutons pour modifier ou supprimer, uniquement si administrateur ou auteur de la composition -->
                        <?php if ((isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || 
                            (isset($_SESSION['user_id']) && $compositionModel->isAuthor($_SESSION['user_id'], $composition['id']))): ?>
                            <a href="/loup-garou-crud/public/index.php?action=edit&id=<?= $composition['id']; ?>" class="btn btn-edit">Modifier</a>
                            <a href="/loup-garou-crud/public/index.php?action=delete_composition&id=<?= $composition['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette composition ?');">Supprimer</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Conteneur pour afficher le nom de la carte sélectionnée -->
    <div id="carte-nom-affichage"></div>

</body>
</html>
