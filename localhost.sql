-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 12 août 2023 à 18:58
-- Version du serveur : 5.7.24
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog_p5`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `status` enum('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `content`, `title`, `created_at`, `created_by`, `post_id`, `status`) VALUES
(1, 'Merci, pour toutes ses information', 'Super info', '2023-07-09 18:30:01', 6, 9, 'APPROVED'),
(2, 'World', 'Hello', '2023-07-09 18:53:51', 6, 9, 'PENDING'),
(3, 'This page ?', 'Refresh', '2023-07-09 19:00:04', 6, 9, 'APPROVED'),
(4, 'It&#039;work ?', 'Try again', '2023-07-09 19:02:48', 6, 9, 'PENDING'),
(5, 'Workkkk !!!', 'Now it&#039;s', '2023-07-09 19:09:00', 6, 9, 'REJECTED'),
(6, 'This id of commentaire ', 'You can see', '2023-07-09 19:11:28', 6, 9, 'PENDING'),
(7, 'Again', 'Try again', '2023-07-09 19:11:56', 6, 9, 'APPROVED'),
(8, 'Comme ici', 'J&#039;essaye ailleur ', '2023-07-09 19:15:33', 6, 8, 'PENDING'),
(9, 'Cela fu un plaisir de vous lire', 'Merci', '2023-07-09 19:17:20', 6, 8, 'PENDING'),
(10, 'It&#039;s hard but you can', 'Don&#039;t give up', '2023-07-09 19:19:59', 6, 7, 'PENDING'),
(11, 'This url', 'What is :', '2023-07-09 19:21:23', 6, 7, 'PENDING'),
(12, 'not work', 'Again', '2023-07-09 19:22:15', 6, 7, 'REJECTED'),
(13, 'just nothing', 'What is :', '2023-07-09 19:24:06', 6, 7, 'PENDING'),
(14, 'we are close', 'What is :', '2023-07-09 19:24:54', 6, 7, 'REJECTED'),
(15, 'What is the number of this post ?', 'Can you repeat ', '2023-07-09 19:26:26', 6, 6, 'PENDING'),
(16, 'We can do it', 'I think ', '2023-07-09 19:29:19', 6, 6, 'APPROVED'),
(17, 'We go see all posts', 'Try new test', '2023-07-09 19:43:48', 6, 5, 'APPROVED'),
(18, 'Il suffit de mettre ../post', 'Je suis sur la bonne piste', '2023-07-09 19:59:04', 6, 6, 'APPROVED'),
(19, 'World', 'Hello', '2023-07-14 15:05:57', 5, 2, 'APPROVED'),
(20, 'World', 'Hello', '2023-07-14 15:31:14', 1, 9, 'APPROVED'),
(21, 'Il est trop beau', 'Mon chat', '2023-08-04 15:44:25', 15, 8, 'APPROVED'),
(23, 'Turn around the world in the night it&#039;s so wonderfull', 'I love that', '2023-08-04 16:13:58', 15, 1, 'PENDING'),
(24, 'Do you see me ?', 'Do you see that ?', '2023-08-04 16:14:38', 15, 1, 'PENDING'),
(25, 'Et gros minet ne la pas vue', 'Titi est passer par là', '2023-08-04 17:46:09', 18, 2, 'APPROVED'),
(26, 'Il est magnifique', 'Mon commentaire', '2023-08-11 15:29:17', 1, 10, 'PENDING');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` mediumtext CHARACTER SET utf8mb4 NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `lead_sentence` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `image`, `created_at`, `created_by`, `lead_sentence`, `updated_at`) VALUES
(1, 'Man must explore, and this is exploration at its greatest', 'Never in all their history have men been able truly to conceive of the world as one: a single sphere, a globe, having the qualities of a globe, a round earth in which all the directions eventually meet, in which there is no center because every point, or none, is center — an equal earth which all men occupy as equals. The airman\'s earth, if free men make it, will be truly round: a globe in practice, not in theory.\r\n\r\nScience cuts two ways, of course; its products can be used for both good and evil. But there\'s no turning back from science. The early warnings about technological dangers also come from science.\r\n\r\nWhat was most significant about the lunar voyage was not that man set foot on the Moon but that they set eye on the earth.\r\n\r\nA Chinese tale tells of some men sent to harm a young girl who, upon seeing her beauty, become her protectors rather than her violators. That\'s how I felt seeing the Earth for the first time. I could not help but love and cherish her.\r\n\r\nFor those who have seen the Earth from space, and for the hundreds and perhaps thousands more who will, the experience most certainly changes your perspective. The things that we share in our world are far more valuable than those which divide us.\r\n\r\nThe Final Frontier\r\nThere can be no thought of finishing for ‘aiming for the stars.’ Both figuratively and literally, it is a task to occupy the generations. And no matter how much progress one makes, there is always the thrill of just beginning.\r\n\r\nThere can be no thought of finishing for ‘aiming for the stars.’ Both figuratively and literally, it is a task to occupy the generations. And no matter how much progress one makes, there is always the thrill of just beginning.\r\n\r\nThe dreams of yesterday are the hopes of today and the reality of tomorrow. Science has not yet mastered prophecy. We predict too much for the next year and yet far too little for the next ten.\r\nSpaceflights cannot be stopped. This is not the work of any one man or even a group of men. It is a historical process which mankind is carrying out in accordance with the natural laws of human development.\r\n\r\nReaching for the Stars\r\nAs we got further and further away, it [the Earth] diminished in size. Finally it shrank to the size of a marble, the most beautiful you can imagine. That beautiful, warm, living object looked so fragile, so delicate, that if you touched it with a finger it would crumble and fall apart. Seeing this has to change a man.\r\n\r\n...\r\nTo go places and do things that have never been done before – that’s what living is all about.\r\nSpace, the final frontier. These are the voyages of the Starship Enterprise. Its five-year mission: to explore strange new worlds, to seek out new life and new civilizations, to boldly go where no man has gone before.\r\n\r\nAs I stand out here in the wonders of the unknown at Hadley, I sort of realize there’s a fundamental truth to our nature, Man must explore, and this is exploration at its greatest.\r\n\r\nPlaceholder text by Space Ipsum · Images by NASA on The Commons', 'https://images.pexels.com/photos/1025469/pexels-photo-1025469.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', '2023-06-04 15:26:32', 1, 'Problems look mighty small from 150 miles up\r\n', NULL),
(2, '“1 vaut mieux que 0”', 'Ne préjugez jamais de la qualité de ce que vous allez faire ou des retombées. Partez simplement du principe que ça sera toujours plus que de n’avoir rien fait.\r\n\r\n**Le travail ne paie pas toujours  mais la masse de travail paie toujours un minimum**', 'https://img.freepik.com/photos-gratuite/bougie-numero-rouge-entouree-bougies-colorees-gemmes-fond-jaune_23-2148190412.jpg?w=1380&t=st=1686925130~exp=1686925730~hmac=ccb09261f89b13cf581f95d3f6f815c7838eecb21e6d9b840b5d01428de30cab', '2023-06-16 14:10:58', 1, 'Le travail ne paie pas toujours  mais la masse de travail paie toujours un minimum\r\n					\r\n					\r\n						\r\n						', '2023-08-11 15:28:20'),
(3, 'Pourquoi la Chine est un pays si attirant', 'La Chine est un pays attirant pour plusieurs raisons :\n\nHistoire et culture : La Chine possède une histoire et une culture richement ancrées, remontant à plusieurs milliers d\'années. Ses anciennes dynasties, ses sites historiques, sa langue, sa philosophie, sa cuisine et ses traditions attirent les visiteurs qui cherchent à explorer cette riche histoire et cette culture unique.\n\nPatrimoine naturel : La Chine est dotée d\'une grande diversité de paysages naturels époustouflants, allant des montagnes majestueuses (comme l\'Himalaya et les monts Kunlun) aux vastes plaines, en passant par les déserts, les rivières, les lacs et les côtes. La beauté naturelle du pays, y compris des sites emblématiques tels que la Grande Muraille et les rizières en terrasses de Longji, attire de nombreux amateurs de nature et de paysages.\n\nÉconomie dynamique : La Chine est la deuxième plus grande économie du monde et joue un rôle de plus en plus important sur la scène mondiale. Elle offre de nombreuses opportunités économiques, notamment en termes de commerce, d\'investissement, d\'emploi et de développement technologique. De nombreuses entreprises et professionnels sont attirés par le dynamisme économique du pays.\n\nVoyage et tourisme : La Chine offre une grande variété de destinations touristiques attrayantes, allant des villes cosmopolites modernes comme Pékin et Shanghai aux villes historiques préservées comme Xi\'an et Chengdu, en passant par les magnifiques paysages de la région du Yunnan ou du Sichuan. Les diverses attractions touristiques, la gastronomie, les festivals et la facilité de déplacement à l\'intérieur du pays font de la Chine une destination intéressante pour les voyageurs.\n\nCélébrations culturelles : La Chine est réputée pour ses célébrations culturelles vibrantes telles que le Nouvel An chinois, la Fête des Lanternes, le Festival des bateaux-dragons et d\'autres événements colorés et festifs. Ces célébrations offrent aux visiteurs l\'occasion de découvrir et de participer à la culture chinoise traditionnelle de première main.\n\nIl convient de noter que chaque personne peut avoir des raisons différentes d\'être attirée par la Chine en fonction de ses intérêts personnels, de ses objectifs et de ses préférences.\n\n', 'public/assets/img/pexels-jiawei-cui-2310876.jpg', '2023-07-03 19:53:56', 1, 'Apprendre à implémenter un code en MVC', NULL),
(4, 'Méthodologie d’implémentation', 'La méthode MVC veut qu’on crée \r\n\r\n1. La Vue = Le visuelle qu’aura le visiteur (Statique) \r\n2. La Class = Table de la BDD\r\n3. Le Model = Les fonctions qu’on appellera (Appelle la CLASS)\r\n4. Le Controller = La logique qu’on souhaite mettre en place entre chaque Modèle (Appelle le MODEL  et  VUE )\r\n5. Vérification de la Vue = Le visuelle qu’aura le visiteur (Dynamique) (Appelle le CONTROLLER)', 'public/assets/img/pexels-photo-5483070.webp', '2023-07-03 20:49:13', 1, 'Le Guide du MVC', NULL),
(5, 'Apprendre la photo', '1 - Prendre un APF\n2 - Trouver une cible \n3- Shooter 😉', 'public/assets/img/free-photo-of-juste-un-portrait.jpeg', '2023-07-03 20:55:55', 1, 'Photo', NULL),
(6, 'Apprendre a voyager', 'Apprendre les rudimant des langues et sortir de sa zone de confort', 'public/assets/img/pexels-elias-strale-6048605.jpg', '2023-07-03 21:11:48', 1, 'Pret pour votre prochain voyage ? ', NULL),
(7, 'Apprendre l\'opera', '1- Imaginez une histoire\r\n2- Planter le décors\r\n3- Jouer', 'public/assets/img/pexels-cottonbro-studio-6895842.jpg', '2023-07-03 21:19:42', 1, 'Vous aimez jouer des rôles et improvisé ?', NULL),
(8, 'Le chat le meilleur amie de ...', 'Eh oui comme je vous le disais le chat est considérer comme le meilleur amie du chien car lorsqu&#039;on les voit grandir ensemble il s&#039;avère qu&#039;il arrivent à s&#039;entendre parfaitement.', 'public/assets/img/pexels-sajid-iqbal-16638212.jpg', '2023-07-03 22:02:04', 1, 'Pensez vous que le Chat et le meilleur amie de ....', '2023-07-15 10:17:31'),
(9, 'Apprendre le javascript', 'JavaScript (souvent abrégé en \"JS\") est un langage de programmation pensé pour rendre les pages web interactives mais qui peut aussi être utilisé en dehors du navigateur gràce à des technologies comme NodeJS et Deno.\n', 'public/assets/img/th.jfif', '2023-07-07 15:07:54', 1, 'Apprendre le JS simplement', '2023-07-21 17:16:00'),
(10, 'Ratatouille', 'Super Film', 'public/assets/img/IMG_4464.jpg', '2023-08-11 15:28:51', 1, 'Film', '2023-08-11 15:28:51');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `first_name`, `last_name`, `password`, `role`, `deleted`, `picture`) VALUES
(1, 'Getssone', 'getssone@mailo.com', 'Gaetan', 'Solis', '$2y$10$3zBfOudZrqhAYTuNuwfKheK9sApCPThkrw4iuxebv.xRlh3f1xLKi', 1, 0, 'https://images.pexels.com/photos/462162/pexels-photo-462162.jpeg'),
(3, 'test2', 'test2@test.fr', 'test2', 'test2', '098f6bcd4621d373cade4e832627b4f6', 2, 0, 'https://picsum.photos/200/300'),
(4, 'Emo', 'emo.ji@test.fr', 'Emo', 'Ji', '202cb962ac59075b964b07152d234b70', 0, 0, 'https://picsum.photos/200/300'),
(5, 'test4', 'test4@test.fr', 'test4', 'test4', '$2y$10$ewS/3XgpZKGqGlwV/l77A.HNUuRr1pnotokA76ljG7hP3K4MmJs/G', 0, 0, 'https://picsum.photos/200/300'),
(6, 'test5', 'test5@test.fr', 'test5', 'test5', '$2y$10$wS6ZzpwzbS2d/J8P1.mXdOQ3QUJ0BdzdIe9ey7.M4tQNu2n73wTkW', 1, 0, 'https://picsum.photos/200/300'),
(7, 'test6', 'test6@test.fr', 'test6', 'test6', '$2y$10$aSy34p6kulEGCHEjHl7iQeZ1oV5xJ31.ZlNRvQVhfpGhuLegU2/HO', 0, 0, 'https://picsum.photos/200/300'),
(8, 'test7', 'test7@test.fr', 'test7', 'test7', '$2y$10$aSy34p6kulEGCHEjHl7iQeZ1oV5xJ31.ZlNRvQVhfpGhuLegU2/HO', 0, 0, 'https://picsum.photos/200/300'),
(9, 'test8', 'test8@test.fr', 'test8', 'test8', '$2y$10$aSy34p6kulEGCHEjHl7iQeZ1oV5xJ31.ZlNRvQVhfpGhuLegU2/HO', 0, 0, 'https://picsum.photos/200/300'),
(10, 'test9', 'test9@test.fr', 'test9', 'test9', '$2y$10$aSy34p6kulEGCHEjHl7iQeZ1oV5xJ31.ZlNRvQVhfpGhuLegU2/HO', 0, 0, 'https://picsum.photos/200/300'),
(11, 'test10', 'test10@test.fr', 'test10', 'test10', '$2y$10$aSy34p6kulEGCHEjHl7iQeZ1oV5xJ31.ZlNRvQVhfpGhuLegU2/HO', 0, 0, 'https://picsum.photos/200/300'),
(12, 'test11', 'test11@test.fr', 'test11', 'test11', '$2y$10$JhN/8o/zrUY3aR1LzN/fy.y.iE7PefZ2mqZhfaSeRChTj4LhlxgNi', 0, 0, 'https://picsum.photos/200/300'),
(13, 'hello', 'hello@hello.fr', 'hello', 'olleh', '$2y$10$HefJeCoRpK3OlRsQTUqnsOdzsZMRlk9mDwCIDttT8WX.F9QJLthWe', 3, 0, 'https://picsum.photos/200/300'),
(14, 'Magie', 'magie@magie.fr', 'Magie', 'Eigam', '$2y$10$uyRrNngBCfTmAlm6gQNAa.6K67mOfehcV/4zirBeWQuETQ9doYlcq', 3, 0, 'https://picsum.photos/200/300'),
(15, 'Souris', 'souris@grise.fr', 'Souris', 'Grise', '$2y$10$t3YG/2/NWotllM7rysTBEudBW/XxCHWjUEcyvX3sFZnrLOdQICoYW', 0, 0, 'https://picsum.photos/200/300'),
(17, 'Love', 'love@you.fr', 'Love', 'You', '$2y$10$M7m9ec1nOMOSEHlXD3edverg8NwgFZ1/Ux1V6rez7GpOY0BZV.D.K', 0, 0, 'https://picsum.photos/id/237/200/300'),
(18, 'Titi', 'titi@titi.fr', 'Titit', 'Grosminet', '$2y$10$gYzaSa7gWxbBg0hMd3NCmO1vtrK3n3olu4m/LuHR5ZxWmvhzUsu9K', 0, 0, 'https://picsum.photos/id/237/200/300'),
(20, 'Rat', 'rat@rat.fr', 'Rat', 'Rat', '$2y$10$zbMgDTTh957N0y3rG9f.ZOFXbbdez0J4BuiyzGTBCuslVYeCs6l4K', 2, 0, 'https://picsum.photos/id/237/200/300');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`created_by`),
  ADD KEY `post_id` (`post_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
