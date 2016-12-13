<?php
namespace X\Module\Workspace\Util;
use X\Core\X;
use X\Module\API\Module as APIModule;
use phpDocumentor\Reflection\DocBlockFactory;
require_once dirname(__FILE__).'/../Library/ReflectionDocBlock/Autoloader.php';
class API {
    /** @return array */
    public static function getAll( ) {
        $apiModule = X::system()->getModuleManager()->get(APIModule::getModuleName());
        $actionPath = $apiModule->getPath('Action');
        return self::fetchActionPath($actionPath, $actionPath);
    }
    
    /**
     * @param string $path
     * @return array
     */
    private static function fetchActionPath($basePath, $path) {
        $actions = array();
        if ( is_file($path) ) {
            $apiInfo = self::readActionFile($path, $basePath);
            $actions[$apiInfo['name']] = $apiInfo;
        } else {
            $files = scandir($path);
            foreach ( $files as $file ) {
                if ( '.' === $file[0] ) { continue; }
                $newPath = $path.DIRECTORY_SEPARATOR.$file;
                $subAction = self::fetchActionPath($basePath, $newPath);
                $actions = array_merge($actions,$subAction);
            }
        }
        return $actions;
    }
    
    /**
     * @param string $path
     * @param string $basePath
     * @return array
     */
    private static function readActionFile( $path, $basePath ) {
        $apiInfo = array();
    
        $className = str_replace(array($basePath, '.php'), '', $path);
        if ( DIRECTORY_SEPARATOR === $className[0] ) {
            $className = substr($className, 1);
        }
        $apiInfo['name'] = $className;
        $className = "\\X\\Module\\API\\Action\\{$className}";
    
        $classInfo = new \ReflectionClass($className);
        $classComment = self::parseDocComment($classInfo->getDocComment());
        $apiInfo['description'] = $classComment['description'];
        
        $apiInfo['params'] = array();
        $runMethod = $classInfo->getMethod('runAction');
        $params = $runMethod->getParameters();
        if ( !empty($params) ) {
            $runDoc = $runMethod->getDocComment();
            $runDoc = self::parseDocComment($runDoc);
            if ( !empty($runDoc['params']) ) {
                $apiInfo['params'] = $runDoc['params'];
            }
            foreach ( $params as $param ) {
                /** @var $param \ReflectionParameter */
                $name = $param->getName();
                if ( !isset($apiInfo['params'][$name]) ) {
                    $apiInfo['params'][$name] = array(
                        'type' => '',
                        'description' => '',
                        'default' => null,
                    );
                    $apiInfo['params'][$name]['default'] = $param->getDefaultValue();
                }
            }
        }
        
        return $apiInfo;
    }
    
    /**
     * @param string $content
     * @return array
     */
    private static function parseDocComment( $content ) {
        $parser = DocBlockFactory::createInstance();
        $docblock = $parser->create($content);
        
        $commentKV = array();
        $commentKV['description'] = $docblock->getSummary();
        if ( $docblock->hasTag('author') ) {
            $commentKV['author'] = $docblock->getTagsByName('author');
            $commentKV['author'] = $commentKV['author'][0]->getAuthorName();
        }
        if ( $docblock->hasTag('param') ) {
            $commentKV['params'] = array();
            $params = $docblock->getTagsByName('param');
            foreach ( $params as $param ) {
                /** @var $param \phpDocumentor\Reflection\DocBlock\Tags\Param */
                $commentKV['params'][$param->getVariableName()] = array(
                    'type' => $param->getType()->__toString(),
                    'description' => $param->getDescription()->__toString(),
                    'default' => null,
                );
            }
        }
        return $commentKV;
    }
}