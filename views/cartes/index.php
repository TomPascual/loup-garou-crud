<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cartes - Loup-Garou</title>
    <!-- Lien vers le fichier CSS pour styliser le header -->
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css">
    <!-- Lien vers le fichier CSS pour styliser la liste des cartes -->
    <link rel="stylesheet" href="/loup-garou-crud/public/css/cartes.css">
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
    
<h1>Liste des Cartes</h1>

<!-- Bouton d'administration pour ajouter une carte, visible uniquement pour les administrateurs -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <div class="admin-actions">
        <a href="/loup-garou-crud/public/index.php?action=create_carte" class="btn">Ajouter une carte</a>
    </div>
<?php endif; ?>

<!-- Conteneur principal pour afficher les cartes -->
<div class="cards-container">

    <!-- Section pour les cartes "Villageois" -->
    <div class="cards-column">
        <h2>Villageois</h2>
        <div class="cards-list">
            <?php foreach ($cartes as $carte): ?>
                <?php if (isset($carte['categorie']) && $carte['categorie'] == 'villageois'): ?>
                    <div class="card" data-nom="<?= htmlspecialchars($carte['nom']); ?>" data-description="<?= htmlspecialchars($carte['description']); ?>">
                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="card-image">
                        <div class="card-name"><?= htmlspecialchars($carte['nom']); ?></div>
                        <!-- Options de modification/suppression visibles uniquement pour les administrateurs -->
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <div class="admin-card-actions">
                                <a href="/loup-garou-crud/public/index.php?action=edit_carte&id=<?= $carte['id']; ?>" class="btn btn-edit">Modifier</a>
                                <a href="/loup-garou-crud/public/index.php?action=delete_carte&id=<?= $carte['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte ?');">Supprimer</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Section pour les cartes "Neutre" -->
    <div class="cards-column">
        <h2>Neutre</h2>
        <div class="cards-list">
            <?php foreach ($cartes as $carte): ?>
                <?php if (isset($carte['categorie']) && $carte['categorie'] == 'neutre'): ?>
                    <div class="card" data-nom="<?= htmlspecialchars($carte['nom']); ?>" data-description="<?= htmlspecialchars($carte['description']); ?>">
                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="card-image">
                        <div class="card-name"><?= htmlspecialchars($carte['nom']); ?></div>
                        <!-- Options de modification/suppression visibles uniquement pour les administrateurs -->
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <div class="admin-card-actions">
                                <a href="/loup-garou-crud/public/index.php?action=edit_carte&id=<?= $carte['id']; ?>" class="btn btn-edit">Modifier</a>
                                <a href="/loup-garou-crud/public/index.php?action=delete_carte&id=<?= $carte['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte ?');">Supprimer</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Section pour les cartes "Loups-Garous" -->
    <div class="cards-column">
        <h2>Loups-Garous</h2>
        <div class="cards-list">
            <?php foreach ($cartes as $carte): ?>
                <?php if (isset($carte['categorie']) && $carte['categorie'] == 'loup'): ?>
                    <div class="card" data-nom="<?= htmlspecialchars($carte['nom']); ?>" data-description="<?= htmlspecialchars($carte['description']); ?>">
                        <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="card-image">
                        <div class="card-name"><?= htmlspecialchars($carte['nom']); ?></div>
                        <!-- Options de modification/suppression visibles uniquement pour les administrateurs -->
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <div class="admin-card-actions">
                                <a href="/loup-garou-crud/public/index.php?action=edit_carte&id=<?= $carte['id']; ?>" class="btn btn-edit">Modifier</a>
                                <a href="/loup-garou-crud/public/index.php?action=delete_carte&id=<?= $carte['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte ?');">Supprimer</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Section pour afficher la description agrandie d'une carte -->
<div id="description-display" style="display: none;">
    <h3 id="card-name"></h3>
    <p id="card-description"></p>
</div>

<!-- Bouton pour fermer la description agrandie -->
<div id="close-button" style="display: none;">✖</div>

<!-- Fichier JS pour gérer l'interactivité sur les cartes -->
<script src="/loup-garou-crud/public/js/cartes.js"></script>
</body>
</html>
