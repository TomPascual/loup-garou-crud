<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Carte</title>
    <!-- Lien vers le fichier CSS pour le header -->
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css"> 
    <!-- Lien vers le fichier CSS principal pour styliser la page -->
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
        <!-- Conteneur du formulaire pour ajouter une nouvelle carte -->
        <h1>Ajouter une Nouvelle Carte</h1>

        <!-- Formulaire pour créer une carte, avec envoi de fichier pour la photo -->
        <form method="POST" action="?action=create_carte" enctype="multipart/form-data"> 
            <label for="nom">Nom de la carte:</label>
            <!-- Champ de saisie pour le nom de la carte -->
            <input type="text" name="nom" required> 

            <label for="description">Description:</label>
            <!-- Champ texte pour la description de la carte -->
            <textarea name="description" required></textarea>

            <label for="categorie">Catégorie:</label>
            <!-- Sélecteur pour choisir la catégorie de la carte -->
            <select name="categorie" required>
                <option value="neutre">Neutre</option>
                <option value="loup">Loup</option>
                <option value="villageois">Villageois</option>
            </select>

            <label for="photo">Photo :</label>
            <!-- Champ pour uploader une photo associée à la carte -->
            <input type="file" name="photo" accept="image/*" required>

            <!-- Bouton pour soumettre le formulaire et créer la carte -->
            <button type="submit">Ajouter la carte</button>
        </form>

        <!-- Lien de retour à la liste des cartes -->
        <a href="/loup-garou-crud/public/index.php?action=cartes" class="btn-back">Retour à la liste des cartes</a> 
    </div>
</body>
</html>
