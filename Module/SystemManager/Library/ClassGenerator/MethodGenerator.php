<?php
namespace X\Module\SystemManager\Library\ClassGenerator;

class MethodGenerator {
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
    private $content = array();
    
    /**
     * @param unknown $content
     */
    public function addLine( $content ) {
        $this->content[] = $content;
    }
    
    /**
     * @var unknown
     */
    public $tabSize = 0;
    
    /**
     * @var unknown
     */
    public $visibility = 'public';
    
    /**
     * @var unknown
     */
    private $comments = array();
    
    /**
     * @param unknown $content
     */
    public function addComment( $content ) {
        $this->comments[] = $content;
    }
    
    /**
     * @return string
     */
    public function toString() {
        return implode("\n", $this->getContentArray());
    }
    
    /**
     * @return array
     */
    public function getContentArray() {
        $content = array();
        $tab = implode('', array_fill(0, $this->tabSize, '    '));
        
        # setup comments
        if ( !empty($this->comments) ) {
            $content[] = "{$tab}/**";
            foreach ( $this->comments as $comment ) {
                $content[] = "{$tab} * {$comment}";
            }
            $content[] = "{$tab} */";
        }
        
        $content[] = "{$tab}{$this->visibility} function {$this->name} () {";
        foreach ( $this->content as $line ) {
            $content[] = $tab.$tab.$line;
        }
        $content[] = "{$tab}}";
        return $content;
    }
    
    /**
     * @param unknown $object
     */
    public function attachTo( $object, $option=array() ) {
        $targetClass = new \ReflectionClass($object);
        $targetPath = $targetClass->getFileName();
        $fileContent = file($targetPath, FILE_IGNORE_NEW_LINES);
        
        $endLine = $targetClass->getEndLine();
        $methodContent = $this->getContentArray();
        array_unshift($methodContent, '');
        
        array_splice($fileContent, $endLine-1, 0, $methodContent);
        
        if ( isset($option['use']) && !empty($option['use']) ) {
            $uses = array();
            foreach ( $option['use'] as $use ) {
                if ( is_string($use) ) {
                    $uses[] = "use {$use};";
                } else if ( is_array($use) ) {
                    $uses[] = "use {$use[0]} as {$use[1]};";
                }
            }
            
            $useStartLine = 0;
            foreach ( $fileContent as $line => $content ) {
                if ( 0 < preg_match('/^namespace\s/', $content) ) {
                    $useStartLine = $line+1;
                    break;
                }
            }
            
            array_splice($fileContent, $useStartLine, 0, $uses);
        }
        
        $fileContent = implode("\n", $fileContent);
        file_put_contents($targetPath, $fileContent);
    }
}