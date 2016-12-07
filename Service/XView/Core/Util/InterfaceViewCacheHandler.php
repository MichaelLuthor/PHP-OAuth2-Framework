<?php
namespace X\Service\XView\Core\Util;
interface InterfaceViewCacheHandler {
    public function isCacheAvailable($name, $mark=null);
    public function cacheContent($name,$content,$mark=null,$lifetime=null);
    public function clean($name, $mark=null);
    public function cleanAll();
    public function getContent($name, $mark=null);
}