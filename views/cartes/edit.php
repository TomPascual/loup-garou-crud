<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la Carte</title>
    <!-- Lien vers les fichiers CSS pour le style -->
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css">
    <link rel="stylesheet" href="/loup-garou-crud/public/css/style.css">
</head>
<body>
    <header>
        <nav>
            <!-- Liens de navigation -->
            <a href="/loup-garou-crud/public/index.php">Accueil</a> | 
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
    
    <div class="form-container">
        <!-- Titre de la page de modification -->
        <h1>Modifier la Carte</h1>

        <!-- Formulaire pour modifier une carte existante, avec des champs pré-remplis -->
        <form method="POST" action="?action=edit_carte&id=<?= $carte['id'] ?>" enctype="multipart/form-data">
            <label for="nom">Nom de la carte:</label>
            <!-- Champ pour le nom de la carte, pré-rempli avec la valeur actuelle -->
            <input type="text" name="nom" value="<?= htmlspecialchars($carte['nom']) ?>" required>

            <label for="description">Description:</label>
            <!-- Champ texte pour la description, pré-rempli avec la valeur actuelle -->
            <textarea name="description" required><?= htmlspecialchars($carte['description']) ?></textarea>

            <label for="categorie">Catégorie:</label>
            <!-- Sélecteur pour la catégorie de la carte, avec la catégorie actuelle pré-sélectionnée -->
            <select name="categorie" required>
                <option value="neutre" <?= $carte['categorie'] === 'neutre' ? 'selected' : '' ?>>Neutre</option>
                <option value="loup" <?= $carte['categorie'] === 'loup' ? 'selected' : '' ?>>Loup</option>
                <option value="villageois" <?= $carte['categorie'] === 'villageois' ? 'selected' : '' ?>>Villageois</option>
            </select>

            <label for="photo">Changer la photo :</label>
            <!-- Champ pour uploader une nouvelle photo -->
            <input type="file" name="photo" accept="image/*">

            <!-- Aperçu de la photo actuelle de la carte -->
            <div>
                <p>Photo actuelle :</p>
                <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="Aperçu de la carte" width="150">
            </div>

            <!-- Bouton pour soumettre les modifications -->
            <button type="submit">Modifier la carte</button>
        </form>

        <!-- Lien pour revenir à la liste des cartes -->
        <a href="/loup-garou-crud/public/index.php?action=cartes" class="btn-back">Retour à la liste des cartes</a>
    </div>
</body>
</html>
