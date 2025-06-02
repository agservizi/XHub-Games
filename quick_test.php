<?php
echo "PHP is working!\n";
echo "Current directory: " . getcwd() . "\n";

if (file_exists('.env')) {
    echo ".env file exists\n";
    $content = file_get_contents('.env');
    echo "First 100 characters:\n";
    echo substr($content, 0, 100) . "\n";
} else {
    echo ".env file NOT found\n";
}
?>
