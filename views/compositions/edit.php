<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Composition</title>
    <!-- Lien vers les fichiers CSS pour styliser le header et la page de modification de composition -->
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css">
    <link rel="stylesheet" href="/loup-garou-crud/public/css/composition.css">
</head>
<body>
<header>
    <nav>
        <!-- Liens de navigation -->
        <a href="/loup-garou-crud/public/index.php">Compositions</a> | 
        <a href="/loup-garou-crud/public/index.php?action=cartes">Cartes</a> |
        <!-- Afficher les options de connexion/déconnexion selon l'état de la session -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="?action=logout">Déconnexion (<?= htmlspecialchars($_SESSION['pseudo']) ?>)</a>
        <?php else: ?>
            <a href="?action=login">Connexion</a> | 
            <a href="?action=register">Créer un compte</a>
        <?php endif; ?>
    </nav>
</header>

<!-- Titre de la page de modification de la composition -->
<h1>Modifier la Composition</h1>

<!-- Vérifie si la composition et les cartes disponibles sont définies -->
<?php if (isset($composition) && isset($cartesDisponibles)): ?>
    <!-- Formulaire pour modifier la composition existante -->
    <form method="POST" action="/loup-garou-crud/public/index.php?action=edit&id=<?= htmlspecialchars($composition['id']) ?>">
        <label for="nom">Nom de la Composition:</label>
        <!-- Champ pour modifier le nom de la composition -->
        <input type="text" name="nom" value="<?= htmlspecialchars($composition['nom']) ?>" required><br>

        <label for="description">Description:</label>
        <!-- Champ pour modifier la description de la composition -->
        <textarea name="description" rows="5" cols="40" required><?= htmlspecialchars($composition['description']) ?></textarea><br>

        <label for="nombre_joueurs">Nombre de Joueurs (Minimum : 5):</label>
        <!-- Champ pour modifier le nombre de joueurs -->
        <input type="number" id="nombre_joueurs" name="nombre_joueurs" min="5" value="<?= htmlspecialchars($composition['nombre_joueurs']) ?>" required><br>

        <!-- Message d'erreur si le nombre de cartes dépasse le nombre de joueurs -->
        <p id="error-message" style="color: red; display: none;">Le nombre de cartes ne peut pas dépasser le nombre de joueurs. Augmentez le nombre de joueurs.</p>

        <label for="cartes">Sélectionner les Cartes (exactement égal au nombre de joueurs):</label><br>

        <!-- Conteneur pour les catégories de cartes -->
        <div class="cartes-selection">
            <!-- Boucle pour chaque catégorie de cartes (Villageois, Neutre, Loup) -->
            <?php foreach (['villageois', 'neutre', 'loup'] as $categorie): ?>
                <div>
                    <div class="category-header"><?= ucfirst($categorie) ?></div>
                    <div class="cards-category <?= $categorie ?>">
                        <!-- Affichage des cartes disponibles dans la catégorie -->
                        <?php foreach ($cartesDisponibles as $carte): ?>
                            <?php if ($carte['categorie'] == $categorie): ?>
                                <div class="carte">
                                    <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                    <div class="card-actions">
                                        <!-- Boutons pour ajuster le nombre de cartes -->
                                        <button type="button" class="btn-decrease">-</button>
                                        <button type="button" class="btn-increase">+</button>
                                    </div>
                                    <!-- Préremplir avec la quantité actuelle de chaque carte -->
                                    <?php $currentCount = $cartesSelectionnees[$carte['id']] ?? 0; ?>
                                    <div class="card-count" data-count="<?= $currentCount ?>">
                                        <?= $currentCount ?>
                                    </div>
                                    <!-- Champ caché pour stocker la quantité de cartes sélectionnées -->
                                    <input type="hidden" name="cartes[<?= $carte['id'] ?>]" value="<?= $currentCount ?>">
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bouton pour soumettre les modifications -->
        <button type="submit" id="submit-button">Mettre à jour</button>
    </form>
<?php else: ?>
    <!-- Message d'erreur si la composition ou les cartes ne peuvent pas être chargées -->
    <p>Impossible de charger la composition ou les cartes disponibles.</p>
<?php endif; ?>

<!-- Fichier JavaScript pour gérer les interactions avec les cartes -->
<script src="/loup-garou-crud/public/js/composition.js"></script>
</body>
</html>
