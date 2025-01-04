<?php
// Récupérer le nom de la ville depuis le paramètre de l'URL
// Ce paramètre est envoyé via une requête GET
$city_name = $_GET['city_name'];

// Charger le fichier XML correspondant à la ville
// Le fichier XML est situé dans le dossier "xml" et porte le nom de la ville
$xml = new DOMDocument();
$xml->load("xml/$city_name.xml");

// Charger la feuille de style XSLT pour transformer le XML
// La feuille de style est située dans le dossier "xsl" et s'appelle "Ville.xsl"
$xsl = new DOMDocument();
$xsl->load('xsl/Ville.xsl');

// Configurer le processeur XSLT pour appliquer la transformation
// Importer la feuille de style XSLT dans le processeur
$proc = new XSLTProcessor();
$proc->importStyleSheet($xsl);

// Transformer le contenu XML en HTML à l'aide de la feuille de style XSLT
// Le résultat de la transformation est directement affiché dans la réponse HTTP
echo $proc->transformToXML($xml);
?>
