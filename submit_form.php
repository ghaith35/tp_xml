<?php
// Define the directory where the XML files will be saved
$xmlDirectory = 'xml/';

// Check if the directory exists, if not, create it
if (!is_dir($xmlDirectory)) {
    mkdir($xmlDirectory, 0777, true);  // Create the directory with appropriate permissions
}

// Process form data when submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $city_name = $_POST['city_name'];
    $country = $_POST['country'];
    $continent = $_POST['continent'];
    $description = $_POST['description'];
    $sites = $_POST['sites'];
    $hotels = isset($_POST['hotels']) ? $_POST['hotels'] : [];
    $restaurants = isset($_POST['restaurants']) ? $_POST['restaurants'] : [];
    $stations = isset($_POST['stations']) ? $_POST['stations'] : [];
    $airports = isset($_POST['airports']) ? $_POST['airports'] : [];

    // Step 1: Create or update NomVille.xml (city-specific XML)
    $cityFile = $xmlDirectory . $city_name . '.xml';
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true; // Optional: formats the XML nicely

    $root = $dom->createElement('ville');
    $root->setAttribute('nom', $city_name);
    
    // Add descriptif
    $descriptif = $dom->createElement('descriptif', $description);
    $root->appendChild($descriptif);
    
    // Add sites
    $sitesElement = $dom->createElement('sites');
    foreach ($sites as $index => $site) {
        $siteElement = $dom->createElement('site');
        $siteElement->setAttribute('nom', $site);
        // Assuming the file upload for site photos is handled separately
        if (isset($_FILES['site_photos']['name'][$index]) && $_FILES['site_photos']['error'][$index] == 0) {
            $photo = $_FILES['site_photos']['name'][$index];
            $photoElement = $dom->createElement('photo', $photo);
            $siteElement->appendChild($photoElement);
        }
        $sitesElement->appendChild($siteElement);
    }
    $root->appendChild($sitesElement);

    // Add hotels
    $hotelsElement = $dom->createElement('hotels');
    foreach ($hotels as $hotel) {
        $hotelElement = $dom->createElement('hotel', $hotel);
        $hotelsElement->appendChild($hotelElement);
    }
    $root->appendChild($hotelsElement);

    // Add restaurants
    $restaurantsElement = $dom->createElement('restaurants');
    foreach ($restaurants as $restaurant) {
        $restaurantElement = $dom->createElement('restaurant', $restaurant);
        $restaurantsElement->appendChild($restaurantElement);
    }
    $root->appendChild($restaurantsElement);

    // Add stations
    $stationsElement = $dom->createElement('gares');
    foreach ($stations as $station) {
        $stationElement = $dom->createElement('gare', $station);
        $stationsElement->appendChild($stationElement);
    }
    $root->appendChild($stationsElement);

    // Add airports
    $airportsElement = $dom->createElement('aeroports');
    foreach ($airports as $airport) {
        $airportElement = $dom->createElement('aeroport', $airport);
        $airportsElement->appendChild($airportElement);
    }
    $root->appendChild($airportsElement);

    // Save the city-specific XML file in the xml directory
    $dom->appendChild($root);
    $dom->save($cityFile);

    // Step 2: Update Villes.xml (central registry XML)
    $villesFile = $xmlDirectory . 'Villes.xml';
    if (!file_exists($villesFile)) {
        // Create Villes.xml if it doesn't exist
        $villesDom = new DOMDocument('1.0', 'UTF-8');
        $villesDom->formatOutput = true;
        $rechercheElement = $villesDom->createElement('recherche');
        $villesDom->appendChild($rechercheElement);
    } else {
        $villesDom = new DOMDocument();
        $villesDom->load($villesFile);
    }

    // Check if the country already exists
    $countries = $villesDom->getElementsByTagName('pays');
    $existingCountry = null;
    foreach ($countries as $paysElement) {
        if ($paysElement->getAttribute('nom') === $country) {
            $existingCountry = $paysElement;
            break;
        }
    }

    if (!$existingCountry) {
        // If country does not exist, create a new country element
        $existingCountry = $villesDom->createElement('pays');
        $existingCountry->setAttribute('nom', $country);
        $villesDom->getElementsByTagName('recherche')->item(0)->appendChild($existingCountry);
    }

// Create the city element
$villeElement = $villesDom->createElement('ville');
$villeElement->setAttribute('nom', $city_name);

// Add sites for search
$sitesElement = $villesDom->createElement('sites');
foreach ($sites as $index => $site) {
    $siteElement = $villesDom->createElement('site');
    $siteElement->setAttribute('nom', $site);
    if (isset($_FILES['site_photos']['name'][$index]) && $_FILES['site_photos']['error'][$index] == 0) {
        $photoName = $_FILES['site_photos']['name'][$index];
        $uploadDir = 'images/'; // directory where photos will be uploaded
        $uploadPath = $uploadDir. $photoName;
        move_uploaded_file($_FILES['site_photos']['tmp_name'][$index], $uploadPath);
        $siteElement->setAttribute('photo', $uploadPath);
    }
    $sitesElement->appendChild($siteElement);
}
$villeElement->appendChild($sitesElement);
// Check if villes element already exists in the country
$villesElement = $existingCountry->getElementsByTagName('villes')->item(0);
if (!$villesElement) {
    // If villes element does not exist, create it
    $villesElement = $villesDom->createElement('villes');
    $existingCountry->appendChild($villesElement);
}

// Append the city to the villes element
$villesElement->appendChild($villeElement);

// Save the updated Villes.xml in the xml directory
$villesDom->save($villesFile);

// Redirect or display success message
echo "Ville ajoutée avec succès!";
}
?>
