<?php
// Vérifie si le paramètre "file" est présent dans l'URL
if (!isset($_GET['file'])) {
    die("Erreur : Aucun fichier de ville spécifié.");
}

$cityFile = $_GET['file'];

// Définit les chemins des fichiers XML et XSL
$xmlPath = __DIR__ . "/xml/" . basename($cityFile); // Chemin relatif vers le fichier XML
$xslPath = __DIR__ . "/xsl/city_to_pdf.xsl"; // Chemin relatif vers le fichier XSL

// Vérifie si le fichier XML existe
if (!file_exists($xmlPath)) {
    die("Erreur : Fichier XML de la ville introuvable.");
}

// Chargement des fichiers XML et XSL
$xml = new DOMDocument();
$xsl = new DOMDocument();

if (!$xml->load($xmlPath)) {
    die("Erreur : Impossible de charger le fichier XML.");
}

if (!$xsl->load($xslPath)) {
    die("Erreur : Impossible de charger le fichier XSL.");
}

// Configure le processeur XSLT
$proc = new XSLTProcessor();
$proc->importStyleSheet($xsl); // Importe le fichier XSL

// Transforme le fichier XML en contenu HTML
$htmlContent = $proc->transformToXML($xml);

// Inclut la bibliothèque TCPDF pour générer le PDF
require_once('vendor/autoload.php');

// Crée un nouveau document PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configure les métadonnées du document PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Votre Nom');
$pdf->SetTitle('PDF généré');
$pdf->SetSubject('Génération de PDF');
$pdf->SetKeywords('TCPDF, PDF, exemple, test, guide');

// Ajoute une page au PDF
$pdf->AddPage();

// Ajoute le contenu HTML transformé au PDF
$pdf->writeHTML($htmlContent, true, false, true, false, '');

// Ferme et affiche le document PDF
$pdf->Output('city_pdf.pdf', 'I'); // Affiche le PDF dans le navigateur

exit; // Terminer le script
?>
