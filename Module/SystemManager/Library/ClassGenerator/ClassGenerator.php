<?php
namespace X\Module\SystemManager\Library\ClassGenerator;
use X\Core\X;
use X\Module\SystemManager\Util\SystemManagerHelper;

class ClassGenerator {
    private $name = null;
    public function __construct( $name ) {
        $this->name = $name;
    }
    
    /**
     * @var string
     */
    public $namespace = null;
    
    /**
     * @param unknown $namespace
     * @return \X\Module\SystemManager\Library\ClassGenerator\ClassGenerator
     */
    public function setNamespace( $namespace ) {
        $this->namespace = $namespace;
        return $this;
    }
    
    /**
     * @var array
     */
    private $useStatements = array();
    
    /**
     * @param unknown $name
     * @param string $alias
     * @return \X\Module\SystemManager\Library\ClassGenerator\ClassGenerator
     */
    public function addUseStatement( $name, $alias=null ) {
        $useStatement = 'use '.$name;
        if ( null !== $alias ) {
            $useStatement .= (' as '.$alias);
        }
        $useStatement .= ';';
        $this->useStatements[] = $useStatement;
        return $this;
    }
    
    /**
     * @var string
     */
    public $extendsName = null;
    
    /**
     * @param unknown $name
     * @return \X\Module\SystemManager\Library\ClassGenerator\ClassGenerator
     */
    public function setExtendsName( $name ) {
        $this->extendsName = $name;
        return $this;
    }
    
    /**
     * @var MethodGenerator[]
     */
    private $methods = array();
    
    /**
     * @param unknown $name
     * @return \X\Module\SystemManager\Library\ClassGenerator\MethodGenerator
     */
    public function addMethod( $name ) {
        $method = new MethodGenerator($name);
        $method->tabSize = 1;
        $this->methods[$name] = $method;
        return $method;
    }
    
    /**
     * @var array
     */
    private $comments = array();
    
    /**
     * @param unknown $content
     * @return \X\Module\SystemManager\Library\ClassGenerator\ClassGenerator
     */
    public function addComment( $content ) {
        $this->comments[] = $content;
        return $this;
    }
    
    /**
     * @return string
     */
    public function toString() {
        $content = array();
        
        if ( null !== $this->namespace ) {
            $content[] = "namespace {$this->namespace};";
        }
        
        if ( !empty($this->useStatements) ) {
            foreach ( $this->useStatements as $useStatemet ) {
                $content[] = $useStatemet;
            }
        }
        $content[] = '';
        
        #setup comments
        $content[] = '/**';
        foreach ( $this->comments as $comment ) {
            $content[] = " * {$comment}";
        }
        $content[] = ' */';
        
        # setup class header.
        $classHead = "class {$this->name}";
        if ( null !== $this->extendsName ) {
            $classHead .= (' extends '.$this->extendsName);
        }
        $classHead .= ' {';
        $content[] = $classHead;
        
        # add methods to class body.
        foreach ( $this->methods as $method ) {
            $content = array_merge($content, $method->getContentArray());
            $content[] = '';
        }
        
        if ( !empty($this->methods) ) {
            array_pop($content);
        }
        
        $content[] = '}';
        return implode("\n", $content);
    }
    
    /**
     * @return string The class file path.
     */
    public function save() {
        $path = explode('\\', $this->namespace);
        array_shift($path);
        $path = implode('/', $path);
        $path = X::system()->getPath($path);
        
        SystemManagerHelper::createFolder($path);
        $path = $path.DIRECTORY_SEPARATOR.$this->name.'.php';
        $content = "<?php\n".$this->toString();
        file_put_contents($path, $content);
        return $path;
    }
}