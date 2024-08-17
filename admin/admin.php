<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manoharam Orders</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        // Check if the user is already logged in
        window.onload = function() {
            if (!sessionStorage.getItem('adminLoggedIn')) {
                let password = prompt("Please enter the admin password:");
                if (password === "admin123") { // Change this to your actual password
                    sessionStorage.setItem('adminLoggedIn', 'true');
                    alert('Access granted!');
                } else {
                    alert('Access denied!');
                    window.location.href = "index.html"; // Redirect to home page on wrong password
                }
            }
        }
    </script>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Manoharam Admin Panel</a>
        </div>
    </nav>

    <div class="container mt-4">
        <?php
            // Define the orders directory
            $ordersDirectory = '../orders/';

            // Check if the directory exists
            if (is_dir($ordersDirectory)) {
                // Get all files from the directory
                $files = scandir($ordersDirectory);
                
                // Create an array to store the files with their creation time
                $filesWithTime = [];

                // Loop through each file
                foreach ($files as $file) {
                    // Skip the . and .. directories
                    if ($file != '.' && $file != '..') {
                        // Full path to the file
                        $filePath = $ordersDirectory . $file;

                        // Store the file path, name, and creation time in the array
                        $filesWithTime[] = [
                            'file' => $file,
                            'path' => $filePath,
                            'creation_time' => filemtime($filePath) // File modification time (close to creation time)
                        ];
                    }
                }

                // Sort the files array by creation time in descending order
                usort($filesWithTime, function($a, $b) {
                    return $b['creation_time'] - $a['creation_time'];
                });
                
                // Start the table
                echo "<h2>Order Files and Images</h2>";
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>File Name</th><th>Creation Date</th><th>File Link</th></tr></thead>";
                echo "<tbody>";

                // Loop through the sorted files and display them in the table
                foreach ($filesWithTime as $fileInfo) {
                    $file = $fileInfo['file'];
                    $filePath = $fileInfo['path'];
                    $creationTime = date("Y-m-d H:i:s", $fileInfo['creation_time']);

                    // Check if the file is an XML file
                    if (pathinfo($filePath, PATHINFO_EXTENSION) == 'xml') {
                        // Display the XML file in the table
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($file) . "</td>";
                        echo "<td>" . htmlspecialchars($creationTime) . "</td>";
                        echo "<td><a href='" . htmlspecialchars($filePath) . "' target='_blank'>View XML</a></td>";
                        echo "</tr>";
                    }
                }

                // End the table
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<div class='alert alert-danger'>The orders directory does not exist.</div>";
            }
        ?>

        <br><br>

        <?php
echo "<h2>User Queries</h2>";

$query_dir = "../queries/";

if (is_dir($query_dir)) {
    // Scan the directory for files
    $files = scandir($query_dir);
    
    // Filter out '.' and '..' and only keep XML files
    $xml_files = array_filter($files, function($file) use ($query_dir) {
        return (pathinfo($file, PATHINFO_EXTENSION) === 'xml' && $file !== '.' && $file !== '..');
    });

    // Sort files by date created (last modified)
    usort($xml_files, function($a, $b) use ($query_dir) {
        return filemtime($query_dir . $b) - filemtime($query_dir . $a);
    });

    echo "<table class='table table-bordered'>";
    echo "<thead>
            <tr>
                <th>File Name</th>
                <th>Date Created</th>
                <th>Content</th>
            </tr>
          </thead>";
    echo "<tbody>";

    foreach ($xml_files as $file) {
        $file_path = $query_dir . $file;
        $date_created = date("Y-m-d H:i:s", filemtime($file_path));
        
        // Load XML content
        $xml = new DOMDocument();
        $xml->load($file_path);
        $xml_content = $xml->saveXML(); // Get the XML content as a string
        
        echo "<tr>";
        echo "<td><a href='" . $file_path . "'>" . htmlspecialchars($file) . "</a></td>";
        echo "<td>" . $date_created . "</td>";
        echo "<td><pre>" . htmlspecialchars($xml_content) . "</pre></td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "The directory does not exist.";
}
?>


        <br><br>

    <?php
        
        echo "<h2>Images in Image Folder</h2>";
        
        $img_dir = "../orders/images/";
        if (is_dir($img_dir)) {
            $images = scandir($img_dir);
            echo "<div class='container'>";
            echo "<div class='row'>"; // Start the row for images
        
            foreach ($images as $img) {
                // Skip '.' and '..' entries
                if ($img != '.' && $img != '..') {
                    echo "<div class='col-md-3 mb-4'>"; // Adjust column size as needed
                    echo "<div class='card'>";
                    echo "<div class='card-body text-center'>";
                    echo "<img src='" . $img_dir . $img . "' style='height:200px;width:200px' class='img-fluid mb-2'>";
                    echo "<br><b><a href='" . $img_dir . $img . "'>" . htmlspecialchars($img) . "</a></b>";
                    echo "</div></div>";
                    echo "</div>"; // End the column
                }
            }
            echo "</div>"; // End the row
            echo "</div>"; // End the container
        }
        ?>
        

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
