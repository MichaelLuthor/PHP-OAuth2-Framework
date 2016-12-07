<?php
namespace X\Module\SystemManager\Util;
/**
 * 
 */
class HttpError500Handler {
    /**
     * @return void
     */
    public static function run($message) {
        die(self::getHtmlErrorMessage($message));
    }
    
    /**
     * 
     */
    private static function getHtmlErrorMessage($message) {
        $message = <<<EOD
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
  <meta charset="utf-8">
</head>
<body>
$message
</body>
</html>
EOD;
        return $message;
    }
}