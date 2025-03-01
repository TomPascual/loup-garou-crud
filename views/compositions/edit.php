<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Composition</title>
    <link rel="stylesheet" href="/loup-garou-crud/public/css/header.css">
    <link rel="stylesheet" href="/loup-garou-crud/public/css/composition.css">
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

<h1>Modifier la Composition</h1>

<?php if (isset($composition) && isset($cartesDisponibles)): ?>
    <form method="POST" action="/loup-garou-crud/public/index.php?action=edit_composition&id=<?= htmlspecialchars($composition['id']) ?>">
        <label for="nom">Nom de la Composition:</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($composition['nom']) ?>" required><br>

        <label for="description">Description:</label>
        <textarea name="description" rows="5" cols="40" required><?= htmlspecialchars($composition['description']) ?></textarea><br>

        <label for="nombre_joueurs">Nombre de Joueurs (Minimum : 5):</label>
        <input type="number" id="nombre_joueurs" name="nombre_joueurs" min="5" value="<?= htmlspecialchars($composition['nombre_joueurs']) ?>" required><br>

        <p id="error-message" style="color: red; display: none;">Le nombre de cartes ne peut pas dépasser le nombre de joueurs. Augmentez le nombre de joueurs.</p>

        <label for="cartes">Sélectionner les Cartes:</label><br>

        <div class="cartes-selection">
            <?php foreach (['villageois', 'neutre', 'loup'] as $categorie): ?>
                <div>
                    <div class="category-header"><?= ucfirst($categorie) ?></div>
                    <div class="cards-category <?= $categorie ?>">
                        <?php foreach ($cartesDisponibles as $carte): ?>
                            <?php if ($carte['categorie'] == $categorie): ?>
                                <div class="carte">
                                    <img src="<?= htmlspecialchars($carte['photo']) ?>" alt="<?= htmlspecialchars($carte['nom']) ?>" class="carte-image">
                                    <div class="card-actions">
                                        <button type="button" class="btn-decrease">-</button>
                                        <button type="button" class="btn-increase">+</button>
                                    </div>
                                    <?php $currentCount = $cartesSelectionnees[$carte['id']] ?? 0; ?>
                                    <div class="card-count" data-count="<?= $currentCount ?>">
                                        <?= $currentCount ?>
                                    </div>
                                    <input type="hidden" name="cartes[<?= $carte['id'] ?>]" value="<?= $currentCount ?>">
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" id="submit-button">Mettre à jour</button>
    </form>

    <!-- Vérification si le formulaire envoie bien les données -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        var_dump($_POST);
        exit;
    }
    ?>

<?php else: ?>
    <p>Impossible de charger la composition ou les cartes disponibles.</p>
<?php endif; ?>

<script src="/loup-garou-crud/public/js/composition.js"></script>
</body>
</html>
