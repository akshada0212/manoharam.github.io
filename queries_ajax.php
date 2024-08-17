<?php

if($_SERVER['REQUEST_METHOD']=="POST")
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $user_msg = $_POST['user_msg'];

    // Create a new DOMDocument object
    $doc = new DOMDocument();

    // Check if the XML file already exists
    if(file_exists('queries.xml')) {
        // Load the existing XML file
        $doc->load('queries.xml');
    } else {
        // Create a new XML file
        $doc->formatOutput = true;
        $root = $doc->createElement('queries');
        $doc->appendChild($root);
    }

    // Get the root element
    $root = $doc->documentElement;

    // Create a new query element
    $query = $doc->createElement('query');
    $root->appendChild($query);

    // Create name, email, and user_msg elements
    $nameElement = $doc->createElement('name', $name);
    $emailElement = $doc->createElement('email', $email);
    $userMsgElement = $doc->createElement('user_msg', $user_msg);
    $status = $doc->createElement('user_msg', "Query yet to be resolved");


    // Append the elements to the query element
    $query->appendChild($nameElement);
    $query->appendChild($emailElement);
    $query->appendChild($userMsgElement);
    $query->appendChild($status);

    if (is_dir('queries')) {
        $filename = __DIR__ . '/queries/query' . date("Y-m-d_H-i-s") . '.xml';
    }else{
        mkdir('queries' , 0777, true);
        $filename = __DIR__ . '/queries/query' . date("Y-m-d_H-i-s") . '.xml';
    }

    // Save the XML file
    $doc->save($filename);

    echo "Your query is submitted successfully! Our Team will reach out to you soon";
}

?>