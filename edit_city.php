<?php
// Démarrage de la session pour utiliser ou sauvegarder des données spécifiques à l'utilisateur
session_start();
$cityData = $_SESSION['edit_city_data'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter Ville</title>
  <link rel="stylesheet" href="css/styles.css?v=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    .add-city-form label {
      display: block;
      margin: 10px 0 5px;
      font-weight: bold;
    }

    .add-city-form input[type="text"],
    .add-city-form textarea,
    .add-city-form input[type="file"] {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .add-city-form textarea {
      resize: vertical;
    }

    .add-city-form button {
      background-color: #007bff;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
    }

    .add-city-form button:hover {
      background-color: #0056b3;
    }

    .add-more {
      cursor: pointer;
      color: green;
      font-size: 1.2rem;
    }

    .remove {
      cursor: pointer;
      color: red;
      font-size: 1.2rem;
      margin-left: 10px;
    }

    .dynamic-field {
      margin-bottom: 10px;
    }

    .dynamic-field input[type="file"] {
      display: inline-block;
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <header>
    <h1 class="ajouter">Ajouter Ville</h1>
  </header>

  <main>
    <form action="submit_form.php" method="POST" class="add-city-form" enctype="multipart/form-data">
      
      <!-- Nom de la ville -->
      <label for="city_name">Ville:</label>
      <input type="text" id="city_name" name="city_name" required placeholder="Entrez le nom de la ville" size="40" value="<?php echo htmlspecialchars($cityData['city_name'] ?? ''); ?>">

      <!-- Pays -->
      <label for="country">Pays:</label>
      <input type="text" id="country" name="country" required placeholder="Entrez le pays" size="40" value="<?php echo htmlspecialchars($cityData['country'] ?? ''); ?>">

      <!-- Continent -->
      <label for="continent">Continent:</label>
      <input type="text" id="continent" name="continent" required placeholder="Entrez le continent" size="40" value="<?php echo htmlspecialchars($cityData['continent'] ?? ''); ?>">

      <!-- Description de la ville -->
      <label for="description">Descriptif:</label>
      <textarea id="description" name="description" required placeholder="Entrez la description de la ville" rows="5" cols="40"><?php echo htmlspecialchars($cityData['description'] ?? ''); ?></textarea>

      <!-- Sites touristiques -->
      <label for="sites">Sites (1 ou plusieurs):</label>
      <div id="sites-container">
        <?php if (!empty($cityData['sites'])): ?>
          <?php foreach ($cityData['sites'] as $site): ?>
            <div class="dynamic-field">
              <input type="text" name="sites[]" placeholder="Entrez un site" required value="<?php echo htmlspecialchars($site['name']); ?>">
              <input type="file" name="site_photos[]" accept="image/*">
              <span class="remove" onclick="removeField(this)">X</span>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="dynamic-field">
            <input type="text" name="sites[]" placeholder="Entrez un site" required>
            <input type="file" name="site_photos[]" accept="image/*" required>
            <span class="add-more" onclick="addSiteField()">+ Ajouter un autre site</span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Hôtels -->
      <label for="hotels">Hôtels (0 ou plusieurs):</label>
      <div id="hotels-container">
        <?php if (!empty($cityData['hotels'])): ?>
          <?php foreach ($cityData['hotels'] as $hotel): ?>
            <div class="dynamic-field">
              <input type="text" name="hotels[]" placeholder="Entrez un hôtel" size="40" value="<?php echo htmlspecialchars($hotel); ?>">
              <span class="remove" onclick="removeField(this)">X</span>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="dynamic-field">
            <input type="text" name="hotels[]" placeholder="Entrez un hôtel" size="40">
            <span class="add-more" onclick="addHotelField()">+ Ajouter un autre hôtel</span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Restaurants -->
      <label for="restaurants">Restaurants (0 ou plusieurs):</label>
      <div id="restaurants-container">
        <?php if (!empty($cityData['restaurants'])): ?>
          <?php foreach ($cityData['restaurants'] as $restaurant): ?>
            <div class="dynamic-field">
              <input type="text" name="restaurants[]" placeholder="Entrez un restaurant" size="40" value="<?php echo htmlspecialchars($restaurant); ?>">
              <span class="remove" onclick="removeField(this)">X</span>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="dynamic-field">
            <input type="text" name="restaurants[]" placeholder="Entrez un restaurant" size="40">
            <span class="add-more" onclick="addRestaurantField()">+ Ajouter un autre restaurant</span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Gares -->
      <label for="stations">Gares (0 ou plusieurs):</label>
      <div id="stations-container">
        <?php if (!empty($cityData['stations'])): ?>
          <?php foreach ($cityData['stations'] as $station): ?>
            <div class="dynamic-field">
              <input type="text" name="stations[]" placeholder="Entrez une gare" size="40" value="<?php echo htmlspecialchars($station); ?>">
              <span class="remove" onclick="removeField(this)">X</span>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="dynamic-field">
            <input type="text" name="stations[]" placeholder="Entrez une gare" size="40">
            <span class="add-more" onclick="addStationField()">+ Ajouter une autre gare</span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Airports -->
      <label for="airports">Aéroports (0 ou plusieurs):</label>
      <div id="airports-container">
        <?php if (!empty($cityData['airports'])): ?>
          <?php foreach ($cityData['airports'] as $airport): ?>
            <div class="dynamic-field">
              <input type="text" name="airports[]" placeholder="Entrez un aéroport" size="40" value="<?php echo htmlspecialchars($airport); ?>">
              <span class="remove" onclick="removeField(this)">X</span>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="dynamic-field">
            <input type="text" name="airports[]" placeholder="Entrez un aéroport" size="40">
            <span class="add-more" onclick="addAirportField()">+ Ajouter un autre aéroport</span>
          </div>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn">Soumettre</button>
    </form>
  </main>

  <script>
    // Fonction pour ajouter un champ dynamique pour les sites
    function addSiteField() {
      const siteContainer = document.getElementById('sites-container');
      const newField = document.createElement('div');
      newField.classList.add('dynamic-field');
      newField.innerHTML = `
        <input type="text" name="sites[]" placeholder="Entrez un site" required>
        <input type="file" name="site_photos[]" accept="image/*" required>
        <span class="remove" onclick="removeField(this)">X</span>
      `;
      siteContainer.appendChild(newField);
    }

    // Fonction pour ajouter un champ dynamique pour les hotel
    function addHotelField() {
      const hotelContainer = document.getElementById('hotels-container');
      const newField = document.createElement('div');
      newField.classList.add('dynamic-field');
      newField.innerHTML = `
        <input type="text" name="hotels[]" placeholder="Entrez un hôtel" size="40">
        <span class="remove" onclick="removeField(this)">X</span>
      `;
      hotelContainer.appendChild(newField);
    }

    // Fonction pour ajouter un champ dynamique pour les restaurant
    function addRestaurantField() {
      const restaurantContainer = document.getElementById('restaurants-container');
      const newField = document.createElement('div');
      newField.classList.add('dynamic-field');
      newField.innerHTML = `
        <input type="text" name="restaurants[]" placeholder="Entrez un restaurant" size="40">
        <span class="remove" onclick="removeField(this)">X</span>
      `;
      restaurantContainer.appendChild(newField);
    }

    // Fonction pour ajouter un champ dynamique pour les gare
    function addStationField() {
      const stationContainer = document.getElementById('stations-container');
      const newField = document.createElement('div');
      newField.classList.add('dynamic-field');
      newField.innerHTML = `
        <input type="text" name="stations[]" placeholder="Entrez une gare" size="40">
        <span class="remove" onclick="removeField(this)">X</span>
      `;
      stationContainer.appendChild(newField);
    }

    // Fonction pour ajouter un champ dynamique pour les aeroport
    function addAirportField() {
      const airportContainer = document.getElementById('airports-container');
      const newField = document.createElement('div');
      newField.classList.add('dynamic-field');
      newField.innerHTML = `
        <input type="text" name="airports[]" placeholder="Entrez un aéroport" size="40">
        <span class="remove" onclick="removeField(this)">X</span>
      `;
      airportContainer.appendChild(newField);
    }

    // Fonction pour supprimer un champ dynamique
    function removeField(element) {
      element.parentElement.remove();
    }
  </script>
</body>
</html>
