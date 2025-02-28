<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une Composition</title>
    <!-- Lien vers les fichiers CSS pour le style du header et de la page de création de composition -->
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

<!-- Titre de la page de création de composition -->
<h1>Créer une Nouvelle Composition</h1>

<!-- Formulaire pour créer une nouvelle composition -->
<form method="POST" action="?action=create">
    <label for="nom">Nom de la Composition:</label>
    <!-- Champ pour le nom de la composition -->
    <input type="text" name="nom" required><br>

    <label for="description">Description:</label>
    <!-- Champ texte pour la description de la composition -->
    <textarea name="description" rows="5" cols="40" required></textarea><br>

    <label for="nombre_joueurs">Nombre de Joueurs (Minimum : 5):</label>
    <!-- Sélecteur pour le nombre de joueurs, avec une valeur minimale de 5 -->
    <input type="number" id="nombre_joueurs" name="nombre_joueurs" min="5" value="5" required><br>

    <!-- Message d'erreur si le nombre de cartes dépasse le nombre de joueurs -->
    <p id="error-message" style="color: red; display: none;">Le nombre de cartes ne peut pas dépasser le nombre de joueurs. Augmentez le nombre de joueurs.</p>

    <label for="cartes">Sélectionner les Cartes (exactement égal au nombre de joueurs):</label><br>
    
    <!-- Conteneur pour les catégories de cartes -->
    <div class="cartes-selection">
        <!-- Section pour les cartes "Villageois" -->
        <div>
            <div class="category-header">Villageois</div>
            <div class="cards-category villageois">
                <?php foreach ($cartesDisponibles as $carte): ?>
                    <?php if ($carte['categorie'] == 'villageois'): ?>
                        <div class="carte">
                            <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                            <div class="card-actions">
                                <!-- Boutons pour augmenter ou diminuer le nombre de cartes -->
                                <button type="button" class="btn-decrease">-</button>
                                <button type="button" class="btn-increase">+</button>
                            </div>
                            <div class="card-count" data-count="0">0</div>
                            <!-- Champ caché pour stocker le nombre de cartes sélectionnées -->
                            <input type="hidden" name="cartes[<?= $carte['id'] ?>]" value="0">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Section pour les cartes "Neutre" -->
        <div>
            <div class="category-header">Neutre</div>
            <div class="cards-category neutre">
                <?php foreach ($cartesDisponibles as $carte): ?>
                    <?php if ($carte['categorie'] == 'neutre'): ?>
                        <div class="carte">
                            <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                            <div class="card-actions">
                                <!-- Boutons pour augmenter ou diminuer le nombre de cartes -->
                                <button type="button" class="btn-decrease">-</button>
                                <button type="button" class="btn-increase">+</button>
                            </div>
                            <div class="card-count" data-count="0">0</div>
                            <!-- Champ caché pour stocker le nombre de cartes sélectionnées -->
                            <input type="hidden" name="cartes[<?= $carte['id'] ?>]" value="0">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Section pour les cartes "Loup" -->
        <div>
            <div class="category-header">Loup</div>
            <div class="cards-category loup">
                <?php foreach ($cartesDisponibles as $carte): ?>
                    <?php if ($carte['categorie'] == 'loup'): ?>
                        <div class="carte">
                            <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                            <div class="card-actions">
                                <!-- Boutons pour augmenter ou diminuer le nombre de cartes -->
                                <button type="button" class="btn-decrease">-</button>
                                <button type="button" class="btn-increase">+</button>
                            </div>
                            <div class="card-count" data-count="0">0</div>
                            <!-- Champ caché pour stocker le nombre de cartes sélectionnées -->
                            <input type="hidden" name="cartes[<?= $carte['id'] ?>]" value="0">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Bouton pour soumettre le formulaire, désactivé tant que la sélection n'est pas correcte -->
    <button type="submit" id="submit-button" disabled>Créer</button>
</form>

<!-- Lien vers le fichier JavaScript pour gérer l'interaction avec les cartes -->
<script src="/loup-garou-crud/public/js/composition.js"></script>
</body>
</html>
