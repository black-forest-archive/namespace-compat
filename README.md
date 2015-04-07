# Composer - Namespace Compat

This composer plugin provides namespace compat.
After run of the composer this plugin compiled a new file.
The new file provides the old to the new namespace.

## Usage

1. Define a requirement for this package in your own composer.json.
    ```json
    {
        "require": {
            "blackforest/namespace-compat": "~1.0"
        }
    }
    ```
   
2. Add this to your own package in the composer.json.
    ```json
    {
        "extra": {
            "namespace-compat": {
                "OldNamespace": "NewNamespace"
            }
        }
    }
    ```
    
3. Run Composer Update: `php composer.phar update`

## Testing

You can test this plugin with [blackforest/namespace-compat-tests](https://github.com/black-forest-archive/namespace-compat-tests).
