<?php

if ($argc < 3) {
    echo "Usage: composer create-controller ControllerName ModelName\n";
    exit(1);
}

$controllerName = ucfirst($argv[1]);
$modelName = ucfirst($argv[2]);

// Check if the model file exists
$modelPath = getcwd() . "/app/Models/{$modelName}.php";

if (!file_exists($modelPath)) {
    echo "Error: The model '{$modelName}' does not exist in app/Models.\n";
    exit(1);
}

$controllerContent = <<<PHP
<?php

namespace app\Controllers;

use config\DBConnection;
use app\Models\\$modelName;

/**
 * Class {$controllerName}
 *
 * This class serves as a controller for handling requests related to the '$modelName' model.
 * It provides an interface between the application's logic and the database layer.
 *
 * @package app\Controllers
 */
class {$controllerName}Controller
{
    /**
     * @var $modelName Instance of the corresponding model.
     */
    private \$$modelName;

    /**
     * Constructor.
     *
     * Initializes the database connection and model instance.
     */
    public function __construct()
    {
        // Establish database connection
        \$DBConnection = new DBConnection();
        
        // Instantiate the model with the database connection
        \$this->$modelName = new $modelName(\$DBConnection);
    }
    
    // Add your custom controllers below to handle business logic.
}
PHP;

$path = getcwd() . "/app/Controllers/{$controllerName}.php";

if (!file_exists(dirname($path))) {
    mkdir(dirname($path), 0777, true);
}

if (!file_exists($path)) {
    file_put_contents($path, $controllerContent);
    echo "{$controllerName}.php created successfully in app/Controllers\n";
} else {
    echo "{$controllerName}.php already exists. Skipping...\n";
}
