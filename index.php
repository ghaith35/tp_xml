<?php
// Créer une nouvelle instance de DOMDocument
$dom = new DOMDocument();
libxml_use_internal_errors(true); // Activer la gestion interne des erreurs
// Charger le fichier XML en supprimant les erreurs
if ($dom->load('xml/Config.xml') === false) {
    echo "Erreur : Impossible de charger le fichier XML.";
    foreach (libxml_get_errors() as $error) {
        echo "<br>Erreur : ", $error->message; // Afficher chaque erreur
    }
    exit; // Terminer le script si le fichier XML ne peut pas être chargé
}
// Valider le XML contre le DTD (Document Type Definition)
if (!$dom->validate()) {
    echo "Erreur : Le XML n'est pas valide selon le DTD.<br>";
    foreach (libxml_get_errors() as $error) {
        echo "Ligne {$error->line} : {$error->message}<br>"; // Afficher les détails de l'erreur
    }
    libxml_clear_errors(); // Effacer les erreurs après les avoir affichées
    exit; // Terminer le script en cas de validation échouée
}
// Récupérer les éléments 'header' et 'nav' pour un traitement ultérieur
$header = $dom->getElementsByTagName('header')->item(0);
$nav = $dom->getElementsByTagName('nav')->item(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Guide</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- Inclusion de Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <!-- html code  -->
    <div class="container">
        <!-- Barre de navigation latérale -->
        <nav class="sidebar">
            <?php
            // Parcourir les éléments 'student' dans la navigation
            $students = $nav->getElementsByTagName('student');
            foreach ($students as $student):
            ?>
                <div class="student-info">
                    <h2>Étudiant</h2>
                    <!-- Afficher les informations de l'étudiant -->
                    <p><strong>Nom:</strong> <?php echo $student->getElementsByTagName('nom')->item(0)->nodeValue; ?></p>
                    <p><strong>Prénom:</strong> <?php echo $student->getElementsByTagName('prenom')->item(0)->nodeValue; ?></p>
                    <p><strong>Spécialité:</strong> <?php echo $student->getElementsByTagName('specialite')->item(0)->nodeValue; ?></p>
                    <p><strong>Section:</strong> <?php echo $student->getElementsByTagName('section')->item(0)->nodeValue; ?></p>
                    <p><strong>Groupe:</strong> <?php echo $student->getElementsByTagName('groupe')->item(0)->nodeValue; ?></p>
                    <p><strong>Email:</strong> <a href="mailto:<?php echo $student->getElementsByTagName('email')->item(0)->nodeValue; ?>"><?php echo $student->getElementsByTagName('email')->item(0)->nodeValue; ?></a></p>
                    <hr>
                </div>
            <?php endforeach; ?>
            <!-- Bouton pour ajouter une ville -->
            <button class="btn add-city-btn" onclick="window.location.href='form.php';">
                <i class="fa fa-plus-circle"></i> Ajouter Ville
            </button>
        </nav>

        <!-- Contenu principal -->
        <div class="main-content">
            <!-- Section d'en-tête -->
            <header class="header">
                <!-- <img src="/images/banner.jpeg" alt="Bannière du site de voyage" class="banner-image"> -->
                <h1 class="site-title"><?php echo $header->getElementsByTagName('h1')->item(0)->nodeValue; ?></h1>
            </header>

            <!-- Section de recherche -->
            <section class="search-section">
                <h2 class="search-title"><em>Recherche</em></h2>
                <!-- Formulaire de recherche -->
                <form id="search-form" class="search-form">
                    <div class="form-row">
                        <!-- Champ pour entrer le continent -->
                        <div class="input-group">
                            <label for="continent">Continent:</label>
                            <input type="text" id="continent" name="continent" placeholder="Entrez un continent">
                        </div>
                        <!-- Champ pour entrer le pays -->
                        <div class="input-group">
                            <label for="country">Pays:</label>
                            <input type="text" id="country" name="country" placeholder="Entrez un pays">
                        </div>
                    </div>
                    <div class="form-row">
                        <!-- Champ pour entrer la ville -->
                        <div class="input-group">
                            <label for="city">Ville:</label>
                            <input type="text" id="city" name="city" placeholder="Entrez une ville">
                        </div>
                        <!-- Champ pour entrer le site -->
                        <div class="input-group">
                            <label for="site">Site:</label>
                            <input type="text" id="site" name="site" placeholder="Entrez un site">
                        </div>
                    </div>
                    <!-- Bouton pour valider la recherche -->
                    <button type="submit" class="btn"><i class="fa fa-check"></i> Valider</button>
                </form>
            </section>

            <!-- Section des résultats de recherche -->
            <section class="results-section">
                <h2><em>Résultats de la recherche</em></h2>
                <ol class="city-list" id="results">
                    <!-- Les résultats seront affichés dynamiquement ici -->
                </ol>
            </section>
        </div>
    </div>

    <!-- Pied de page -->
    <footer>
        <p>&copy; 2024 Travel Guide. Créé par Ghaith & Hicham, M2-TI, UMBB.</p>
    </footer>

    <!-- JavaScript -->
    <script>
    // Ajouter un écouteur d'événement pour le formulaire de recherche
    document.getElementById('search-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Empêcher le comportement par défaut du formulaire

        // Collecter les valeurs des champs de recherche
        const continent = document.getElementById('continent').value.toLowerCase().trim();
        const country = document.getElementById('country').value.toLowerCase().trim();
        const city = document.getElementById('city').value.toLowerCase().trim();
        const site = document.getElementById('site').value.toLowerCase().trim();

        const results = document.getElementById('results');
        results.innerHTML = ''; // Réinitialiser les résultats

        // Charger et analyser le fichier XML
        fetch('xml/Villes.xml')
            .then(response => response.text())
            .then(xmlText => {
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(xmlText, 'application/xml');

                const villes = xmlDoc.getElementsByTagName('ville');
                let found = false;

                // Parcourir les villes pour trouver des correspondances
                Array.from(villes).forEach(ville => {
                    const villeName = ville.getAttribute('nom');
                    const countryNode = ville.parentNode.parentNode;
                    const countryName = countryNode.getAttribute('nom').toLowerCase();
                    const continentNode = xmlDoc.querySelector(`continent[no="${countryNode.getAttribute('no')}"]`);
                    const continentName = continentNode ? continentNode.getAttribute('nom').toLowerCase() : '';
                    const siteElements = ville.getElementsByTagName('site');
                    const siteNames = Array.from(siteElements).map(site => site.getAttribute('nom').toLowerCase());

                    // Vérifier si les critères de recherche correspondent
                    if (
                        (!city || villeName.startsWith(city)) &&
                        (!country || countryName.startsWith(country)) &&
                        (!continent || continentName.startsWith(continent)) &&
                        (!site || siteNames.some(siteName => siteName.startsWith(site)))
                    ) {
                        found = true;

                        // Créer un élément de résultat
                        const resultItem = document.createElement('li');
                        resultItem.classList.add('city-item');
                        resultItem.innerHTML = `
                            <a href="generate_city.php?file=${villeName}.xml" class="city-link">${villeName} (${countryName})</a>
                            <div class="city-actions">
                                <a href="#" class="edit-btn"><i class="fa fa-edit"></i></a>
                                <a href="#" class="delete-btn"><i class="fa fa-trash"></i></a>
                            </div>
                        `;
                        results.appendChild(resultItem);
                    }
                });

                // Si aucune ville n'est trouvée
                if (!found) {
                    results.innerHTML = '<li>Aucun résultat trouvé.</li>';
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement du fichier XML:', error);
                results.innerHTML = '<li>Impossible de charger les données.</li>';
            });
    });
    </script>
</body>
</html>

