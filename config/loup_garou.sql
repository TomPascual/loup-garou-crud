-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 25 oct. 2024 à 11:53
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `loup_garou`
--

-- --------------------------------------------------------

--
-- Structure de la table `cartes`
--

DROP TABLE IF EXISTS `cartes`;
CREATE TABLE IF NOT EXISTS `cartes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text,
  `photo` varchar(255) DEFAULT NULL,
  `categorie` enum('villageois','neutre','loup') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `cartes`
--

INSERT INTO `cartes` (`id`, `nom`, `description`, `photo`, `categorie`) VALUES
(1, 'Renard', 'La première nuit, le renard flaire 3 personnes. Le Meneur lui dit si un loup garou est dans ce groupe. Si oui, il pourra réutiliser son pouvoir la nuit suivante. Sinon, il perd son flair et devient simple villageois.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.18.18 - A medieval-style fox standing in a lush, enchanting forest clearing, wearing a small leather vest and carrying a pouch slung across its back. The fox .webp', 'villageois'),
(2, 'Trois Frères', 'Au début de la partie, le meneur appelle les trois frères à se réveiller. Ils connaissent donc leur identité, et peuvent donc avoir confiance en eux. Ils peuvent sous l’autorisation du meneur se concerter en silence (bien sur).', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.19.04 - Three medieval-style brothers standing in a lush, enchanting forest clearing. Each brother wears distinct yet rugged medieval attire, including leathe.webp', 'villageois'),
(3, 'Voyante', 'Chaque nuit la voyante est appelée par le maître du jeu et peut découvrir la carte d\'un joueur. La voyante est un des personnages les plus puissants dans le camp du village. Elle pourra facilement identifier des joueurs en qui elle pourra avoir confiance, mais surtout l\'identité de ses ennemis.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.13.14 - A medieval-style fortune teller or seer sitting at a table in a dark, mystical forest. She has a mystical presence, with flowing robes in deep shades .webp', 'villageois'),
(4, 'Sorciere', 'La sorcière possède deux potions : une de guérison et une d\'empoisonnement. Elle ne peut utiliser chacune de ses potions qu\'une seule fois au cours de la partie. Durant la nuit, lorsque les loups-garous se sont rendormis, le meneur de jeu va appeler la sorcière et va lui montrer la personne tuée par les loups-garous.\r\n\r\nLa sorcière a trois possibilités :\r\n\r\nressusciter la personne tuée et donc perdre sa seule potion de guérison ;\r\ntuer une autre personne en plus de la victime et donc perdre sa seule potion d\'empoisonnement.\r\nne rien faire\r\nLa sorcière peut utiliser ses deux potions durant la même nuit si elle le souhaite.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.08.10 - A medieval-style witch with a crooked nose, standing over a large bubbling cauldron in a dark, enchanted forest. She is stirring the cauldron with a w.webp', 'villageois'),
(5, 'Simple villageois', 'Le villageois est un personnage qui incarne l\'habitant basique d\'un village. Son rôle est de découvrir l\'identité des loups-garous et de les éliminer avant qu\'ils ne tuent tous les villageois. Il gagne lors ce que tous les loups garous sont morts.\r\n\r\nLe villageois n\'a aucun pouvoir spécial, si ce n\'est de voter au conseil du village contre celui qu\'il suspecte être loup garou.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.11.30 - A medieval-style peasant standing in a mystical forest clearing, with a humble yet rugged appearance, wearing a simple tunic, leather belt, and woolen.webp', 'villageois'),
(6, 'Voleur', 'Le Voleur reçoit lors de la première nuit la possibilité de choisir son rôle parmi deux autres rôles non-distribués. S\'il n\'en choisit aucun il est considéré comme un Simple Villageois. C\'est un personnage dont les capacités varient énormément en fonction du meneur et des joueurs.\r\n\r\nSi le voleur est une des deux cartes non distribuées, le meneur de jeu doit faire comme si c\'était l\'un des joueurs et doit faire le même discours que si quelqu\'un avait la carte.[1]', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.14.54 - A medieval-style thief or rogue standing in a dark, narrow alleyway of a stone-walled village, with a mysterious and cunning expression hidden behind .webp', 'villageois'),
(7, 'Joueur de  flûte', 'Le joueur de flûte se réveille en dernier. Il peut alors charmer un ou deux joueurs (en fonction du nombre de joueurs) qui deviendront les charmés. Il gagne lorsque tous les joueurs en vie sont charmés.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.17.16 - A medieval-style flute player with a sinister expression, standing in a dark, eerie forest clearing, surrounded by swirling magical energy. He wears d.webp', 'neutre'),
(8, 'Cupidon', 'Cupidon est appelé uniquement la première nuit afin d\'unir un couple. Le Meneur de jeu le contacte le moment venu, puis cupidon désigne deux noms parmi les joueurs. Ces deux personnes seront les amoureux. Si un des deux qui sont en couple meurt, l\'autre meurt avec son amant. Pour ce faire, cupidon choisi deux personnes, elles sont alors prévenues, et découvrent avec qui elles sont désormais marié.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.02.00 - A medieval-style Cupid standing poised with a bow and arrow, with feathery wings, curly hair, and a determined expression. Cupid is wearing a leather .webp', 'villageois'),
(9, 'Loup-garou', 'Le loup-garou est un être mi-homme mi-loup qui se transforme la nuit pour tuer les villageois. C\'est le personnage emblématique du jeu de Loup Garou original.\r\n\r\nLe loup-garou incarne l\'un des rôles de l\'équipe des loups-garous. Il connaît l\'identité des autres loups-garous et doit essayer de tuer tous les villageois sans se faire découvrir. Il se réunit chaque nuit avec les autres loups-garous pour décider de leur victime. Il gagne si tout le village est éliminé.', '/loup-garou-crud/uploads/DALL·E 2024-09-20 14.53.55 - A fierce werewolf designed for a card game, standing in an intimidating pose. The werewolf has glowing eyes, sharp claws, and muscular limbs covered i.webp', 'loup'),
(10, 'Enfant Sauvage', 'Au début de la partie, l\'enfant sauvage choisit un mentor. Ce joueur ignore qu\'il est le mentor de l\'enfant sauvage. Si, au cours de la partie, le mentor vient à mourir, alors l\'enfant sauvage devient un loup-garou.', '/loup-garou-crud/uploads/DALLE_2024-10-11_11.33.54_-_A_wild_feral_child_standing_in_a_dark_eerie_forest_under_the_full_moon._The_child_is_small_with_pale_skin_and_tangled_dark_hair._His_posture_is_low.webp', 'neutre'),
(11, 'Grand méchant loup', 'Chaque nuit, le grand méchant loup se réunit avec ses compères Loups pour décider d\'une victime à éliminer... Tant qu\'aucun autre loup n\'est mort, il peut, chaque nuit, dévorer une victime supplémentaire.[1][2] Son objectif est d\'éliminer tous les Villageois (ceux qui ne sont pas Loups-Garous).', '/loup-garou-crud/uploads/DALLE_2024-10-11_11.34.13_-_An_older_more_beastly_black_werewolf_known_as_the_Father_of_Wolves_standing_in_a_dark_eerie_forest_under_the_full_moon._His_fur_is_dark_with_stre.webp', 'loup'),
(12, 'L\'infect père des loups', 'L\'Infect Père des Loups, également connu sous le nom de Loup-Noir, est une variation redoutable du classique loup-garou. Il détient un pouvoir unique et sinistre, capable de semer la discorde et le doute au sein du village.\r\n\r\nDans l\'équipe des loups-garous, l\'Infect Père des Loups joue un rôle crucial. En plus de participer aux décisions nocturnes pour éliminer les villageois, il possède un pouvoir particulier : celui de choisir un villageois à infecter. Ce villageois infecté se transformera en loup-garou lors de la prochaine nuit, sans le savoir. Cette capacité peut être utilisée avec subtilité pour manipuler les événements à venir.', '/loup-garou-crud/uploads/DALLE_2024-10-11_11.32.09_-_An_older_more_beastly_black_werewolf_known_as_the_Father_of_Wolves_standing_in_a_dark_eerie_forest_under_the_full_moon_surrounded_by_several_fera.webp', 'loup'),
(13, 'Chevalier à l\'Épée rouillée', 'Le chevalier à l\'épée rouillée donne le tétanos au premier loup-garou à sa gauche (qui était présent lors du vote des loups) s\'il est mangé par les loups durant la nuit. Ce loup-garou mourra la nuit d\'après, sans manger, innocentant au passage toutes les personnes entre lui et le chevalier.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.39.20 - A medieval-style knight standing in a misty forest, holding a large, rusted sword. The knight is dressed in worn, tarnished armor with a rugged cloak .webp', 'villageois'),
(14, 'Ange', 'L\'ange est un personnage de l\'Extension \"Personnages\" dont le but est de se faire tuer le premier jour, au vote du village.[1] Le but de ce rôle est d\'obliger les joueurs à jouer fair play, car dans beaucoup de parties le premier tour est constellé de morts des plus mauvais joueurs ou des nouveaux joueurs... Le village doit faire attention et utilise son pouvoir de vote avec parcimonie.[2]', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.38.38 - A medieval-style angel standing in a glowing, enchanted forest clearing. The angel has large, feathered wings that spread gracefully, wearing flowing .webp', 'neutre'),
(15, 'Servante Dévouée', 'Chaque nuit dans la partie la servante dévouée est réveillée à la fin de la nuit , le meneur de jeu lui montre la ou les victime(s) de la nuit elle doit choisir si oui ou non elle décide de se sacrifier à la place de cette personne.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.37.45 - A medieval-style servant standing in a rustic, stone-walled kitchen setting, with a warm and devoted expression. She wears a simple dress with an apro.webp', 'villageois'),
(16, 'Loup Blanc', 'Le Loup Garou Blanc ou Loup Blanc, est un des rôles du jeu les plus difficiles à jouer, car il gagne seul, en ayant éliminé tout le village et les loups garous. Une nuit sur deux, il peut dévorer un loup garou juste après leur tour. Il se réveille et vote en même temps que les loups.\r\n\r\nLa complexité de ce rôle le rend difficile d\'accès auprès des joueurs inexpérimentés, mais dans les parties plus avancées, il est un véritable challenge en plus pour les joueurs.\r\n\r\nC\'est un rôle très populaire. Sur la plateforme de jeu Wolfy, le loup blanc est une priorité pour les loups qui cherchent à l\'éliminer au plus vite. Cependant il peut se révéler l\'allié du village durant les premiers temps de la partie.\r\n\r\n', '/loup-garou-crud/uploads/DALLE_2024-10-11_11.34.48_-_An_older_more_beastly_werewolf_known_as_the_Father_of_Wolves_standing_in_a_dark_eerie_forest_under_the_full_moon._His_fur_is_white_with_streaks_of.webp', 'neutre'),
(17, 'Petite fille', 'La petite fille est un rôle très difficile à jouer. En effet lorsque les loups garous sont appelés la nuit, elle à le droit de les espionner. Il existe plusieurs variantes à ce rôle, plus intéressantes à jouer selon la communauté.\r\n\r\nSi la petite fille se fait prendre à espionner les loups garous par ceux-ci, elle meurt immédiatement, à la place de la victime désignée par les loups garous.\r\n\r\n', '/loup-garou-crud/uploads/DALL·E 2024-10-11 14.03.22 - A medieval-style little girl standing in a peaceful, enchanted forest clearing, wearing a hooded cloak that drapes over her shoulders, adding an air o.webp', 'villageois'),
(18, 'Chasseur', 'Le chasseur n\'a aucun rôle particulier à jouer tant qu\'il est vivant. Mais dès qu\'il meurt qu\'il soit tué dans la nuit (Loups-garous, sorcière) ou à la suite d\'une décision des villageois il doit désigner une personne qui mourra également, sur-le-champ, d\'une balle de son fusil.\r\n\r\nSi un chasseur amoureux est éliminé, il peut quand même tuer une personne, ce qui peut mener à une partie sans survivants puisque trois personnes mourront simultanément.\r\n\r\nLe chasseur peut choisir ou non de tirer sa dernière balle, ce qui dans certains cas est vraiment une réflexion qui n\'est pas a prendre à la légère.[1]', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.08.06 - A medieval-style hunter standing in a dense, mystical forest, holding a long, intricate rifle with a wooden stock and brass fittings, designed to fit .webp', 'villageois'),
(19, 'Chien Loup', 'La première nuit, le chien-loup choisit d’être un Simple Villageois ou un Loup-garou.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 14.01.40 - A medieval-style wolf-dog standing in a misty, enchanted forest clearing, with a muscular, powerful build and a thick, weathered coat. The creature ha.webp', 'neutre'),
(20, 'Pyromane', 'Le pyromane se réveille avant les loups-garous. Il peut une fois par partie brûler un bâtiment qui sera retiré définitivement du jeu, son occupant devient alors vagabond. Si le bâtiment choisi est celui de la victime des loups-garous, il ne meurt pas ; par contre, le premier loup-garou à droite de la victime est éliminé.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 13.56.26 - A medieval-style pyromaniac standing in a dark, enchanted forest, holding a small flame in one hand with a sinister grin. The pyromaniac is dressed in.webp', 'villageois'),
(21, 'Idiot du village', 'S\'il est désigné par le vote du village, il ne meurt pas, et ce une seule fois dans la partie, mais perd seulement sa capacité à voter (Il peut participer aux débats).', '/loup-garou-crud/uploads/DALL·E 2024-10-11 13.58.45 - A medieval-style village fool standing in the village square, wearing mismatched, ragged clothing with a foolish grin. He has an older, weathered face.webp', 'villageois'),
(22, 'Abominable sectaire', 'L\'Abominable Sectaire est un rôle indépendant. Pour gagner, il doit réussir à éliminer tous les joueurs du groupe opposé à lui, tout en restant en vie. C\'est le meneur de jeu qui décide quels sont les deux groupes opposés dans le village, par exemple les joueurs avec et sans lunettes (barbus et imperbes, blonds et bruns etc ...).', '/loup-garou-crud/uploads/DALL·E 2024-10-11 14.00.50 - A medieval-style abominable cultist standing in a dark, twisted forest, dressed in tattered, hooded robes covered with cryptic symbols and occult mark.webp', 'neutre'),
(23, 'Deux Soeurs', 'Au début de la partie, le meneur appelle les deux sœurs à se réveiller. Elles connaissent donc leur identité, et peuvent donc avoir confiance en eux. Elles peuvent sous l’autorisation du meneur se concerter en silence (bien sur).', '/loup-garou-crud/uploads/DALL·E 2024-10-11 11.33.23 - Two medieval-style sisters standing in a lush, enchanting forest clearing. Each sister wears unique, rustic medieval attire, with flowing dresses, bel.webp', 'villageois'),
(24, 'Comédien', 'Avant la partie, le meneur choisit trois cartes personnage non-loup ayant des capacités spéciales. Après la distribution des rôles, ces cartes sont placées face visible au centre de la table. Chaque nuit, à l’appel du meneur, le comédien peut désigner une des cartes et utiliser le pouvoir correspondant jusqu’à la nuit suivante. Quand le Comédien choisit une carte, le meneur l’ôte de la table, elle ne pourra plus être utilisée.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 14.06.30 - A medieval-style comedian standing in a quiet village square, dressed in dignified but simple attire, with muted colors and subtle patterns. He has a .webp', 'villageois'),
(25, 'Ancien du village', 'L’ancien possède deux vies contre la nuit. Quand il devrait être tué par les loups garous, il en perd une sans en être averti. Le matin, il se réveille avec les autres, mais dévoile sa carte (la seconde fois qu’il est attaqué par les loups garous alors il meurt normalement).\r\n\r\nSi l’ancien est chassé du village par le vote des villageois il meurt directement et tous les rôles des villageois perdent leurs pouvoirs.\r\n\r\n', '/loup-garou-crud/uploads/DALL·E 2024-10-11 13.55.55 - A medieval-style elder standing at the edge of a rustic village, wearing a long, weathered robe with a hood and leaning on a wooden staff. His face is.webp', 'villageois'),
(26, 'Bouc émissaire', 'Si le vote du village amène une égalité, c\'est le Bouc émissaire qui est éliminé à la place des ex æquo. À lui de bien manœuvrer pour éviter cette triste conclusion.\r\n\r\nSi le Bouc émissaire est éliminé, il lui reste une prérogative à exercer. Il désigne qui votera ou ne votera pas durant la prochaine journée.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 13.57.06 - A medieval-style scapegoat figure, depicted as a solemn, solitary man standing in a dark village square under cloudy skies. He wears rough, tattered c.webp', 'villageois'),
(27, 'Salvateur', 'Chaque nuit, le salvateur protège une personne. Cette personne sera protégée et ne pourra donc pas mourir durant la nuit. Le salvateur ne peut pas protéger la même personne deux nuits de suite.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 13.59.36 - A medieval-style savior standing in a misty forest, dressed in flowing robes and armor with intricate designs, exuding a noble and protective aura. Th.webp', 'villageois'),
(28, 'Corbeau', 'Le corbeau fait partie du village, il sera appelé chaque nuit par le Meneur de jeu et désignera une personne qui recevra automatiquement deux votes de plus contre elle lors du vote de la journée suivante.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 13.53.52 - A medieval-style raven standing on a moss-covered branch in a dark, enchanted forest. The raven has glossy black feathers that catch the soft moonligh.webp', 'villageois'),
(29, 'Gitane', 'Pendant la nuit, la gitane dit au meneur de jeu si elle veut utiliser une carte de spiritisme (extension nouvelle lune). Le meneur choisit une des cartes et au matin, le village là joue de même manière que dans «nouvelle lune». Elle peut tuer 1 personne a chaque nuit grâce a son pouvoir de spiritisme.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 14.21.34 - A medieval-style woman with a mystical aura, seated at a wooden table in a dimly lit room, preparing for a spirit ritual. She wears a simple dress wit.webp', 'villageois'),
(30, 'Juge bègue', 'En début de partie, le juge bègue définit un signe avec le meneur de jeu. A tout moment et une fois dans la partie, le juge bègue peut refaire ce signe à l\'intention du meneur. Dans ce cas, un nouveau vote à lieu, immédiatement et sans débat.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 14.24.16 - A medieval-style judge seated in a grand stone hall, facing forward. He wears dark, flowing robes with intricate, ancient patterns, a tall hat symboli.webp', 'villageois'),
(31, 'Montreur d\'ours', 'Chaque matin, si le montreur d\'ours se trouve à droite ou à gauche d\'un loup-garou, l\'ours grogne (le MJ). Si le montreur est infecté par l\'infect père des loups, l\'ours grognera jusqu\'à la fin de la partie.', '/loup-garou-crud/uploads/DALL·E 2024-10-11 14.27.56 - A medieval-style bear trainer standing in a bustling village square, dressed in rugged, colorful clothing. He holds a staff and stands beside a large,.webp', 'villageois');

-- --------------------------------------------------------

--
-- Structure de la table `compositions`
--

DROP TABLE IF EXISTS `compositions`;
CREATE TABLE IF NOT EXISTS `compositions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text,
  `nombre_joueurs` int NOT NULL,
  `utilisateur_id` int DEFAULT NULL,
  `cartes` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `composition_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `type` enum('like','dislike') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `composition_id` (`composition_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','utilisateur') DEFAULT 'utilisateur',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `pseudo`, `email`, `password`, `role`) VALUES
(3, 'admin', 'tom.pascual@aftec.org', '$2y$10$7MhtzTetzork7zJ5GvHjquVY57Orc1Vx.UTb3Sn/WxyYoeKqvg4p6', 'admin');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `compositions`
--
ALTER TABLE `compositions`
  ADD CONSTRAINT `compositions_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`composition_id`) REFERENCES `compositions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
