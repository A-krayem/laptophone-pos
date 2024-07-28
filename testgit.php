<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
//$_GET["v"]="live.1.6.1";

// Configuration
$repositoryPath = './'; // Path to your local Git repository
$branchOrTag = $_GET["v"]; // The branch or tag to checkout

// Function to execute shell commands and return the output
function executeCommand($command) {
    $output = [];
    $returnVar = null;
    exec($command, $output, $returnVar);
    return ['output' => $output, 'return_var' => $returnVar];
}

// Change to the repository directory
chdir($repositoryPath);

// Fetch the latest changes from the remote repository
$fetchResult = executeCommand('git fetch --all');
if ($fetchResult['return_var'] !== 0) {
    echo "Error fetching changes:\n";
    echo implode("\n", $fetchResult['output']);
    exit(1);
}

// Checkout the specified branch or tag
$checkoutResult = executeCommand("git checkout $branchOrTag");
if ($checkoutResult['return_var'] !== 0) {
    echo "Error checking out branch or tag '$branchOrTag':\n";
    echo implode("\n", $checkoutResult['output']);
    exit(1);
}

// Pull the latest changes
$pullResult = executeCommand('git pull');
if ($pullResult['return_var'] !== 0) {
    echo "Error pulling changes:\n";
    echo implode("\n", $pullResult['output']);
    exit(1);
}

echo "Successfully checked out and pulled the latest changes for '$branchOrTag'.\n";

