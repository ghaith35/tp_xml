<?php
// Chemin vers le fichier Villes.xml
// Ce fichier contient les informations sur les villes
$villesFile = 'xml/Villes.xml';

// Vérifier si le fichier existe
// Si le fichier n'est pas trouvé, le script s'arrête avec un message d'erreur
if (!file_exists($villesFile)) {
    die("Erreur : Fichier introuvable.");
}

// Charger le fichier XML
// Utilisation de DOMDocument pour manipuler le fichier XML
$dom = new DOMDocument();
$dom->preserveWhiteSpace = false; // Ignorer les espaces inutiles dans le fichier XML
$dom->formatOutput = true; // Activer l'affichage formaté pour une meilleure lisibilité
$dom->load($villesFile); // Charger le contenu du fichier XML

// Récupérer le nom de la ville depuis la requête POST
// Si la clé "city_name" n'est pas définie dans POST, elle sera null
$city_name = $_POST['city_name'] ?? null;

// Vérifier si le nom de la ville est fourni
// Si ce paramètre est manquant, le script s'arrête avec un message d'erreur
if (!$city_name) {
    die("Erreur : Nom de la ville requis manquant.");
}

// Localiser et supprimer la ville correspondante
// Utilisation de DOMXPath pour effectuer une recherche XPath sur le fichier XML
$xpath = new DOMXPath($dom);
// Recherche la ville avec l'attribut 'nom' égal à $city_name
$villeNode = $xpath->query("//ville[@nom='$city_name']")->item(0); 

if ($villeNode) {
    // Si la ville est trouvée, obtenir son nœud parent (balise <villes>)
    $parentNode = $villeNode->parentNode;

    // Supprimer le nœud de la ville du nœud parent
    $parentNode->removeChild($villeNode);

    // Sauvegarder le fichier XML mis à jour
    $dom->save($villesFile);

    // Confirmation de la suppression
    echo "La ville '$city_name' a été supprimée avec succès.";
} else {
    // Si la ville n'est pas trouvée, afficher un message d'erreur
    echo "Erreur : La ville '$city_name' est introuvable.";
}
?>
