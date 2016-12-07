<?php
namespace X\Module\SystemManager\Util;
/**
 * 
 */
class HttpError404Handler {
    /**
     * @return void
     */
    public static function run() {
        die(self::getHtmlErrorMessage());
    }
    
    /**
     * 
     */
    private static function getHtmlErrorMessage() {
        $message = <<<EOD
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
  <meta charset="utf-8">
</head>
<body>
请求页面不存在。
</body>
</html>
EOD;
        return $message;
    }
}