<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a directory for storing orders and images
    $orderDir = 'orders/';
    $imageDir = 'orders/images/';
    
    if (!file_exists($orderDir)) {
        mkdir($orderDir, 0777, true); // Create the orders directory
    }
    if (!file_exists($imageDir)) {
        mkdir($imageDir, 0777, true); // Create the images directory
    }

    // Get form data
    $name = $_POST['name-of-user'] ?? '';
    $occasion = $_POST['occasion'] ?? '';
    $description = $_POST['description'] ?? '';
    $size = $_POST['size'] ?? '';
    $boxColor = $_POST['boxColor'] ?? '';
    $theme = $_POST['theme'] ?? '';
    $hamperItems = $_POST['hamperItems'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';

    // Create a unique file name for the order (using timestamp)
    $orderId = time();
    $orderFile = $orderDir . 'order_' . $orderId . '_' . $name . '.xml';

    // Handle file uploads (images)
    $uploadedImages = [];
    if (!empty($_FILES['sampleImages']['name'][0])) {
        foreach ($_FILES['sampleImages']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['sampleImages']['name'][$key]);
            $targetFilePath = $imageDir . $fileName;
            if (move_uploaded_file($tmpName, $targetFilePath)) {
                $uploadedImages[] = $targetFilePath; // Store uploaded image paths
            }
        }
    }

    // Create an XML document
    $xml = new SimpleXMLElement('<order></order>');

    // Add form data to XML
    $xml->addChild('ordered_on' , date("Y-m-d H:i:s"));
    $xml->addChild('occasion', $occasion);
    $xml->addChild('description', $description);
    $xml->addChild('size', $size);
    $xml->addChild('boxColor', $boxColor);
    $xml->addChild('theme', $theme);
    $xml->addChild('hamperItems', $hamperItems);
    $xml->addChild('address', $address);
    $xml->addChild('phone', $phone);
    $xml->addChild('email', $email);

    // Add images to XML
    if (!empty($uploadedImages)) {
        $imagesNode = $xml->addChild('images');
        foreach ($uploadedImages as $image) {
            $imagesNode->addChild('image', $image);
        }
    }

    // Save XML file
    $xml->asXML($orderFile);

    // Response back to AJAX
    echo "Order placed successfully!";
} else {
    echo "Invalid request!";
}
?>
