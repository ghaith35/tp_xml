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
            <label for="city_name">Vile:</label>
            <input type="text" id="city_name" name="city_name" required placeholder="Entrez le nom de la ville" size="40" default="Paris">

            <!-- Pays -->
            <label for="country">Pays:</label>
            <input type="text" id="country" name="country" required placeholder="Entrez le pays" size="40">

            <!-- Continent -->
            <label for="continent">Continent:</label>
            <input type="text" id="continent" name="continent" required placeholder="Entrez le continent" size="40">

            <!-- Description de la ville -->
            <label for="description">Descriptif:</label>
            <textarea id="description" name="description" required placeholder="Entrez la description de la ville" rows="5" cols="40"></textarea>

            <!-- Sites touristiques -->
            <label for="sites">Sites (1 ou plusieurs):</label>
            <div id="sites-container">
                <div class="dynamic-field">
                    <input type="text" name="sites[]" placeholder="Entrez un site" required>
                    <input type="file" name="site_photos[]" accept="image/*" required>
                    <span class="add-more" onclick="addSiteField()">+ Ajouter un autre site</span>
                </div>
            </div>

            <!-- Hôtels -->
            <label for="hotels">Hôtels (0 ou plusieurs):</label>
            <div id="hotels-container">
                <div class="dynamic-field">
                    <input type="text" name="hotels[]" placeholder="Entrez un hôtel" size="40">
                    <span class="add-more" onclick="addHotelField()">+ Ajouter un autre hôtel</span>
                </div>
            </div>

            <!-- Restaurants -->
            <label for="restaurants">Restaurants (0 ou plusieurs):</label>
            <div id="restaurants-container">
                <div class="dynamic-field">
                    <input type="text" name="restaurants[]" placeholder="Entrez un restaurant" size="40">
                    <span class="add-more" onclick="addRestaurantField()">+ Ajouter un autre restaurant</span>
                </div>
            </div>

            <!-- Gares -->
            <label for="stations">Gares (0 ou plusieurs):</label>
            <div id="stations-container">
                <div class="dynamic-field">
                    <input type="text" name="stations[]" placeholder="Entrez une gare" size="40">
                    <span class="add-more" onclick="addStationField()">+ Ajouter une autre gare</span>
                </div>
            </div>

            <!-- Airports -->
            <label for="airports">Aéroports (0 ou plusieurs):</label>
            <div id="airports-container">
                <div class="dynamic-field">
                    <input type="text" name="airports[]" placeholder="Entrez un aéroport" size="40">
                    <span class="add-more" onclick="addAirportField()">+ Ajouter un autre aéroport</span>
                </div>
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