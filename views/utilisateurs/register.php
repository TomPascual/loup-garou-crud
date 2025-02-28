<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <!-- Lien vers la feuille de style pour la page d'inscription -->
    <link rel="stylesheet" href="/loup-garou-crud/public/css/login.css">
</head>
<body>
    <!-- Conteneur du formulaire d'inscription -->
    <div class="form-container">
        <h1>Inscription</h1>
        <!-- Formulaire d'inscription, utilisant la méthode POST pour envoyer les informations -->
        <form method="POST" action="/loup-garou-crud/public/index.php?action=register">
            <!-- Champ pour entrer le pseudo -->
            <label for="pseudo">Pseudo:</label>
            <input type="text" name="pseudo" id="pseudo" class="form-input" required><br>

            <!-- Champ pour entrer l'adresse email -->
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-input" required><br>

            <!-- Champ pour entrer le mot de passe -->
            <label for="password">Mot de passe:</label>
            <input type="password" name="password" id="password" class="form-input" required><br>

            <!-- Bouton pour soumettre le formulaire et s'inscrire -->
            <button type="submit" class="btn">S'inscrire</button>
        </form>
    </div>
</body>
</html>
