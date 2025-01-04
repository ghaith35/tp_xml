<?php
// Vérifie si un fichier de ville est spécifié dans les paramètres GET
if (!isset($_GET['file'])) {
    die("Erreur : Aucun fichier de ville spécifié.");
}

$cityFile = $_GET['file'];
$xmlPath = __DIR__ . "/xml/" . basename($cityFile); // Chemin complet du fichier XML
$htmlPath = __DIR__ . "/city_pages/" . pathinfo($cityFile, PATHINFO_FILENAME) . ".html"; // Chemin complet du fichier HTML généré

// Vérifie si le fichier XML existe
if (!file_exists($xmlPath)) {
    die("Erreur : Fichier XML de la ville introuvable.");
}

// Chargement du fichier XML
$dom = new DOMDocument();
libxml_use_internal_errors(true); // Permet de gérer les erreurs XML sans arrêt du script
if (!$dom->load($xmlPath)) {
    die("Erreur : Fichier XML invalide.");
}

// Extraction des détails de la ville depuis le fichier XML
$city = $dom->documentElement; // Racine de l'élément XML
$cityName = $city->getAttribute('nom'); // Récupère l'attribut "nom" de la ville
$description = $city->getElementsByTagName('descriptif')->item(0)->nodeValue; // Description de la ville
$sites = $city->getElementsByTagName('site'); // Liste des sites touristiques
$hotels = $city->getElementsByTagName('hotel'); // Liste des hôtels
$restaurantsa = $city->getElementsByTagName('restaurant'); // Liste des restaurants
$gares = $city->getElementsByTagName('gare'); // Liste des gares
$aeroports = $city->getElementsByTagName('aeroport'); // Liste des aéroports

// Génération du contenu HTML
$htmlContent = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$cityName}</title>
    <link rel="stylesheet" href="../css/city_css.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <header>
        <h1>{$cityName}</h1>
    </header>
    <section>
        <p>{$description}</p>
        <h2>Sites</h2>
        <ul>
HTML;

// Ajoute les sites touristiques dans le contenu HTML
foreach ($sites as $site) {
    $siteName = $site->getAttribute('nom'); // Nom du site
    $sitePhoto = $site->getElementsByTagName('photo')->item(0)->nodeValue; // Photo associée au site
    $htmlContent .= "<li><strong>{$siteName}</strong>: <img src='../images/{$sitePhoto}' alt='{$siteName}'></li>";
}

$htmlContent .= <<<HTML
        </ul>
    </section>
    <section>
        <h2>Hôtels</h2>
        <ul>
HTML;

// Ajoute les hôtels dans le contenu HTML
foreach ($hotels as $hotel) {
    $htmlContent .= "<li>{$hotel->nodeValue}</li>";
}

$htmlContent .= <<<HTML
        </ul>
    </section>
    <section>
        <h2>Restaurants</h2>
        <ul>
HTML;

// Ajoute les restaurants dans le contenu HTML
foreach ($restaurants as $restaurant) {
    $htmlContent .= "<li>{$restaurant->nodeValue}</li>";
}

$htmlContent .= <<<HTML
        </ul>
    </section>
    <section>
        <h2>Transports</h2>
        <h3>Gares</h3>
        <ul>
HTML;

// Ajoute les gares dans le contenu HTML
foreach ($gares as $gare) {
    $htmlContent .= "<li>{$gare->nodeValue}</li>";
}

$htmlContent .= <<<HTML
        </ul>
        <h3>Aéroports</h3>
        <ul>
HTML;

// Ajoute les aéroports dans le contenu HTML
foreach ($aeroports as $aeroport) {
    $htmlContent .= "<li>{$aeroport->nodeValue}</li>";
}

$htmlContent .= <<<HTML
        </ul>
    </section>
    <!-- Bouton pour générer un fichier PDF -->
    <button id="generate-pdf-btn">Générer PDF</button>

    <script>
        // Ajoute un événement au clic sur le bouton pour générer un PDF
        document.getElementById('generate-pdf-btn').addEventListener('click', function() {
            const cityFile = "$cityFile"; // Récupère le nom du fichier de la ville
            window.location.href = '/tp_amir/generate_pdf.php?file=' + cityFile; // Redirige vers la génération du PDF
        });
    </script>
</body>
</html>
HTML;

// Sauvegarde le contenu HTML généré dans un fichier
if (file_put_contents($htmlPath, $htmlContent) === false) {
    die("Erreur : Impossible de créer le fichier HTML.");
}

// Redirige vers le fichier HTML généré
header("Location: city_pages/" . basename($htmlPath));
exit;
?>
