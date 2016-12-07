<?php
namespace X\Module\SystemManager\Util;
/**
 * 
 */
class SystemManagerHelper {
    /**
     * @param unknown $path
     */
    public static function deletePath( $path ) {
        if ( is_dir($path) ) {
            $files = scandir($path);
            foreach ( $files as $file ) {
                if ( '.' === $file[0] ) {
                    continue;
                }
                self::deletePath($path.DIRECTORY_SEPARATOR.$file);
            }
            rmdir($path);
        } else {
            unlink($path);
        }
    }
    
    /**
     * @param unknown $path
     * @return string
     */
    public static function zip( $path ) {
        $zip = new \ZipArchive();
        $tmpName = tempnam(sys_get_temp_dir(), 'MODEXPRT');
        if ( true !== $zip->open($tmpName, \ZipArchive::OVERWRITE) ) {
            HttpError500Handler::run('could not create zip file.');
        }
        
        self::addFileToZip($zip, $path);
        $zip->close();
        return $tmpName;
    }
    
    /**
     * @param unknown $path
     * @param \ZipArchive $zip
     */
    private static function addFileToZip( $zip, $path, $targetPath='') {
        if ( is_dir($path) ) {
            $basename = basename($path);
            $targetPath = (empty($targetPath) ? '' : $targetPath.'/').$basename;
            $zip->addEmptyDir($targetPath);
    
            $files = scandir($path);
            foreach ( $files as $file ) {
                if ( '.' === $file[0] ) {
                    continue;
                }
    
                $filePath = $path.DIRECTORY_SEPARATOR.$file;
                if ( is_dir($filePath) ) {
                    self::addFileToZip($zip, $filePath, $targetPath);
                } else {
                    self::addFileToZip($zip, $filePath, $targetPath.'/'.$file);
                }
            }
        } else {
            $zip->addFile($path, $targetPath);
        }
    }
    
    /**
     * @param unknown $name
     * @param unknown $path
     * @param unknown $type
     */
    public static function sendFileToBrowser( $name, $path, $type ) {
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename='.$name);
        header("Content-Type: $type");
        header("Content-Transfer-Encoding: binary");
        header('Content-Length: '. filesize($path));
        readfile($path);
    }
    
    /**
     * @param \X\Core\Service\XService $service
     * @return \X\Module\SystemManager\Library\ServiceManager
     */
    public static function getServiceManagementManager( $service ) {
        $serviceNamespace = self::getClassNamespaceName($service);
        $manager = $serviceNamespace.'\\Management\\Manager';
        if ( class_exists($manager, true) ) {
            $manager = new $manager($service);
        } else {
            $manager = null;
        }
        return $manager;
    }
    
    /**
     * @param unknown $object
     * @return string
     */
    public static function getClassNamespaceName( $object ) {
        $namespace = get_class($object);
        $namespace = substr($namespace, 0, strrpos($namespace, '\\'));
        return $namespace;
    }
    
    /**
     * @param unknown $path
     */
    public static function createFolder( $path ) {
        if ( DIRECTORY_SEPARATOR === $path[strlen($path)-1] ) {
            $path = substr($path, 0, strlen($path)-1);
        }
        
        if ( is_dir($path) ) {
            return;
        }
        
        $basepath = dirname($path);
        self::createFolder($basepath);
        mkdir($path);
    }
    
    /**
     * @param unknown $dir
     * @return multitype:|Ambigous <multitype:string , multitype:>
     */
    public static function getFileListOfDirectory( $dir ) {
        if ( !is_dir($dir) ) {
            return array();
        }
        
        $ds = DIRECTORY_SEPARATOR;
        if ( $dir[strlen($dir)-1] !== $ds ) {
            $dir .= $ds;
        }
        
        $list = array();
        $files = scandir($dir);
        foreach ( $files as $index => $name ) {
            if ( '.'===$name || '..'===$name ) {
                unset($files[$index]);
                continue;
            }
            $list[] = $dir.$name;
            if ( is_dir($dir.$name) ) {
                $list = array_merge($list, self::getFileListOfDirectory($dir.$name));
            }
        }
        
        return $list;
    }
}