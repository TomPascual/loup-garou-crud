<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <!-- Lien vers la feuille de style pour la page de connexion -->
    <link rel="stylesheet" href="/loup-garou-crud/public/css/login.css">
</head>
<body>
    <!-- Conteneur du formulaire de connexion -->
    <div class="form-container">
        <h1>Connexion</h1>
        <!-- Formulaire de connexion, utilisant la méthode POST pour envoyer les informations d'identification -->
        <form method="POST" action="/loup-garou-crud/public/index.php?action=login">
            <!-- Champ pour entrer le pseudo ou l'email -->
            <label for="identifiant">Pseudo ou Email:</label>
            <input type="text" name="identifiant" id="identifiant" class="form-input" required><br>

            <!-- Champ pour entrer le mot de passe -->
            <label for="password">Mot de passe:</label>
            <input type="password" name="password" id="password" class="form-input" required><br>

            <!-- Bouton pour soumettre le formulaire et se connecter -->
            <button type="submit" class="btn">Se connecter</button>
        </form>
    </div>
</body>
</html>
