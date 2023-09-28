<!DOCTYPE html>
<html>
<head>
    <title>תחנות דלק</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
         body {
            font-family: Arial, sans-serif;
        }

        #container {
            display: flex;
            flex-direction: column;
            align-items: center;
            float: right;
        }

        #search-form {
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        #map {
            height: 400px;
            width: 40%;
         
        }
    </style>
</head>
<body>
   
    <div id="container">
        <div id="search-form">
            <h2>חיפוש תחנת דלק</h2>
            <form action="#" method="post">
                <label for="search-input">שם או כתובת:</label>
                <input type="text" id="search-input" name="search-input" required>
                <br>
                <input type="submit" value="חפש">
            </form>
        </div>
        <div id="table-container">
            <table>
                <tbody>
                    <?php
                    $csv_file = 'gas-stations.csv';
                    $file_handle = fopen($csv_file, 'r');
                    $search_text = isset($_POST['search-input']) ? $_POST['search-input'] : '';

                    if ($file_handle !== FALSE) {
                        while (($data = fgetcsv($file_handle, 1000, ',')) !== FALSE) {
                          
                            if (stripos($data[0], $search_text) !== false || stripos($data[1], $search_text) !== false) {
                                echo '<tr>';
                                echo '<td>' . $data[0] . '</td>';
                                echo '<td>' . $data[1] . '</td>';
                                echo '<td>' . $data[2] . '</td>';
                                echo '<td>' . $data[3] . '</td>';
                                echo '<td>' . $data[4] . '</td>';
                                echo '</tr>';
                            }
                        }
                        fclose($file_handle);
                    } else {
                        echo '<p>Failed to open the CSV file.</p>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="map"></div>
    <script>
   
    let map = L.map('map').setView([31.25298788402852, 34.77041623095022], 12); 

   
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

   
    let tableRows = document.querySelectorAll('table tbody tr');
    tableRows.forEach(function(row) {
        let lat = parseFloat(row.querySelector('td:nth-child(4)').textContent);
        let lon = parseFloat(row.querySelector('td:nth-child(5)').textContent);
        let name = row.querySelector('td:nth-child(2)').textContent;
        let address = row.querySelector('td:nth-child(3)').textContent;

        if (!isNaN(lat) && !isNaN(lon)) {
            L.marker([lat, lon]).addTo(map)
                .bindPopup('<b>' + name + '</b><br>' + address);
        }
    });
</script>
</body>
</html>
