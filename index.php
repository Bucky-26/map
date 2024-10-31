<?php
// Database connection
$mysqli = new mysqli("localhost", "root", "", "maptest");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle POST request for coordinates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $mysqli->prepare("INSERT INTO image_maps (coordinates, title, description, link) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", 
        $data['coordinates'],
        $data['title'],
        $data['description'],
        $data['link']
    );
    
    $stmt->execute();
    $stmt->close();
    
    echo json_encode(['success' => true]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Mapping Tool</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-color: #f7f7f7;
        }
        .image-container {
            width: 90%;
            max-width: 800px;
            height: auto;
            overflow: hidden;
            position: relative;
            border: 1px solid #ccc;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .zoom-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px;
            border-radius: 5px;
        }
        .zoom-controls button {
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 0 3px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        .zoom-controls button:hover {
            background: #f0f0f0;
        }
        .container {
            position: relative;
            display: inline-block;
            transform-origin: 0 0;
            transition: transform 0.3s ease;
        }
        #targetImage {
            max-width: 100%;
            height: auto;
            display: block;
        }
        .selection {
            position: absolute;
            border: 2px dashed red;
            background: rgba(255, 0, 0, 0.2);
            transform-origin: 0 0;
        }
        #controls {
            margin: 20px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            animation: fadeIn 0.3s ease-out;
            z-index: 1001;
        }
        .modal-content h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border 0.3s;
        }
        .form-group input:focus, .form-group textarea:focus {
            border: 1px solid #4CAF50;
        }
        .modal .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 18px;
            color: #aaa;
        }
        .existing-area {
            position: absolute;
            border: 2px solid blue;
            background: rgba(0, 0, 255, 0.1);
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .existing-area:hover {
            opacity: 0.8;
            border-color: darkblue;
        }
        /* Add tooltip styles */
        .existing-area::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s, visibility 0.2s;
        }
        .existing-area:hover::after {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>
<body>
    <div id="controls">
        <button onclick="startMapping()">Add Map</button>
    </div>
    
    <div class="image-container">
        <div class="zoom-controls">
            <button onclick="zoomIn()"><i class="fa fa-search-plus"></i></button>
            <button onclick="zoomOut()"><i class="fa fa-search-minus"></i></button>
            <button onclick="resetZoom()"><i class="fa fa-undo"></i></button>
        </div>
        <div class="container" id="imageWrapper">
            <img id="targetImage" src=" il2mbswbfpg11.png" alt="Target image">
            <?php
            // Display existing mapped areas
            $result = $mysqli->query("SELECT * FROM image_maps");
            while ($row = $result->fetch_assoc()) {
                $coords = json_decode($row['coordinates'], true);
                echo "<div class='existing-area' 
                          style='left:{$coords['x']}px; top:{$coords['y']}px; width:{$coords['width']}px; height:{$coords['height']}px;'
                          title='{$row['title']}'
                          data-description='{$row['description']}'
                          data-link='{$row['link']}'
                          onclick='showAreaDetails(this)'></div>";
            }
            ?>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="mapModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="cancelMap()">&times;</span>
            <h2>Add Map Details</h2>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="link">Link:</label>
                <input type="url" id="link">
            </div>
            <button onclick="saveMap()">Save</button>
            <button onclick="cancelMap()">Cancel</button>
        </div>
    </div>

    <!-- Area Details Modal -->
    <div id="areaDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeAreaDetails()">&times;</span>
            <h2 id="areaTitle"></h2>
            <div class="form-group">
                <label>Description:</label>
                <p id="areaDescription"></p>
            </div>
            <div class="form-group">
                <label>Link:</label>
                <a id="areaLink" href="" target="_blank"></a>
            </div>
        </div>
    </div>

    <script>
        let isDrawing = false;
        let startX, startY;
        let selection = null;
        let isMapping = false;
        let currentZoom = 1;
        const zoomStep = 0.1;
        const maxZoom = 3;
        const minZoom = 0.5;

        function startMapping() {
            isMapping = true;
            const container = document.querySelector('.container');
            
            container.addEventListener('mousedown', startDrawing);
            container.addEventListener('mousemove', draw);
            container.addEventListener('mouseup', stopDrawing);
        }

        function startDrawing(e) {
            if (!isMapping || e.target.tagName !== 'IMG') return;
            isDrawing = true;
            const rect = e.target.getBoundingClientRect();
            startX = (e.clientX - rect.left) / currentZoom;
            startY = (e.clientY - rect.top) / currentZoom;

            if (selection) {
                selection.remove();
            }

            selection = document.createElement('div');
            selection.className = 'selection';
            selection.style.left = startX + 'px';
            selection.style.top = startY + 'px';
            document.querySelector('.container').appendChild(selection);
        }

        function draw(e) {
            if (!isDrawing || !selection) return;
            
            const rect = e.target.getBoundingClientRect();
            const currentX = (e.clientX - rect.left) / currentZoom;
            const currentY = (e.clientY - rect.top) / currentZoom;
            
            selection.style.width = Math.abs(currentX - startX) + 'px';
            selection.style.height = Math.abs(currentY - startY) + 'px';
            selection.style.left = Math.min(currentX, startX) + 'px';
            selection.style.top = Math.min(currentY, startY) + 'px';
        }

        function stopDrawing() {
            if (!isDrawing) return;
            isDrawing = false;
            showModal();
        }

        function showModal() {
            document.getElementById('mapModal').style.display = 'block';
        }

        function saveMap() {
            const coords = {
                x: parseFloat(selection.style.left),
                y: parseFloat(selection.style.top),
                width: parseFloat(selection.style.width),
                height: parseFloat(selection.style.height),
            };
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            const link = document.getElementById('link').value;

            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ coordinates: JSON.stringify(coords), title, description, link }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });

            cancelMap();
        }

        function cancelMap() {
            document.getElementById('mapModal').style.display = 'none';
            if (selection) selection.remove();
            selection = null;
            isMapping = false;
        }

        function zoomIn() {
            if (currentZoom < maxZoom) {
                currentZoom += zoomStep;
                applyZoom();
            }
        }

        function zoomOut() {
            if (currentZoom > minZoom) {
                currentZoom -= zoomStep;
                applyZoom();
            }
        }

        function resetZoom() {
            currentZoom = 1;
            applyZoom();
        }

        function applyZoom() {
            const container = document.getElementById('imageWrapper');
            container.style.transform = `scale(${currentZoom})`;
        }

        function showAreaDetails(element) {
            const modal = document.getElementById('areaDetailsModal');
            const title = document.getElementById('areaTitle');
            const description = document.getElementById('areaDescription');
            const link = document.getElementById('areaLink');

            title.textContent = element.getAttribute('title');
            description.textContent = element.getAttribute('data-description');
            
            const linkUrl = element.getAttribute('data-link');
            if (linkUrl) {
                link.href = linkUrl;
                link.textContent = linkUrl;
                link.style.display = 'inline';
            } else {
                link.style.display = 'none';
            }

            modal.style.display = 'block';
        }

        function closeAreaDetails() {
            document.getElementById('areaDetailsModal').style.display = 'none';
        }
    </script>
</body>
</html>
