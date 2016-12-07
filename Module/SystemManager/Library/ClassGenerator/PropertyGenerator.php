<?php
namespace X\Module\SystemManager\Library\ClassGenerator;
/**
 * 
 */
class PropertyGenerator {
    /**
     * @var unknown
     */
    private $name = null;
    
    /**
     * @param unknown $name
     */
    public function __construct( $name ) {
        $this->name = $name;
    }
    
    /**
     * @var unknown
     */
    public $visibility = 'public';
    
    /**
     * @var unknown
     */
    public $default = null;
    
    /**
     * @var unknown
     */
    private $comments = array();
    
    /**
     * @param unknown $content
     * @return \X\Module\SystemManager\Library\ClassGenerator\PropertyGenerator
     */
    public function addComment( $content ) {
        $this->comments[] = $content;
        return $this;
    }
    
    /**
     * @param unknown $type
     * @return \X\Module\SystemManager\Library\ClassGenerator\PropertyGenerator
     */
    public function setType( $type ) {
        $this->comments[] = "@type {$type}";
        return $this;
    }
    
    /**
     * @var unknown
     */
    public $tabSize = 0;
    
    /**
     * @return stirng
     */
    public function toString() {
        return implode("\n", $this->getContentArray());
    }
    
    /**
     * @return multitype:
     */
    public function getContentArray() {
        $content = array();
        $tab = implode('', array_fill(0, $this->tabSize, '    '));
        
        if ( !empty($this->comments) ) {
            $content[] = "{$tab}/**";
            foreach ( $this->comments as $comment ) {
                $content[] = "{$tab} * {$comment}";
            }
            $content[] = "{$tab} */";
        }
        
        $content[] = "{$tab}{$this->visibility} \${$this->name} = ".var_export($this->default, true).";";
        return $content;
    }
    
    /**
     * @param unknown $object
     */
    public function attachTo( $object ) {
        $targetClass = new \ReflectionClass($object);
        $targetPath = $targetClass->getFileName();
        $fileContent = file($targetPath, FILE_IGNORE_NEW_LINES);
        
        $endLine = $targetClass->getEndLine();
        $content = $this->getContentArray();
        array_unshift($content, '');
        
        array_splice($fileContent, $endLine, 0, $content);
        
        $fileContent = implode("\n", $fileContent);
        file_put_contents($targetPath, $fileContent);
    }
}