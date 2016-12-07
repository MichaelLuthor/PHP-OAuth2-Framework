<?php
namespace X\Service\XView\Core\Util;
/**
 * 
 */
class HtmlFormatter {
    /**
     * @var unknown
     */
    const FORMAT_PRETTY = 'pretty';
    
    /**
     * @var unknown
     */
    private static $noEndTagNames = array('!DOCTYPE', 'meta', 'link', 'input', 'hr', 'br', 'img', 'option');
    
    /**
     * @var unknown
     */
    private static $oneLineTagNames = array('title', 'a', 'span', 'textarea');
    
    /**
     * @var unknown
     */
    private static $SPECIAL_TAG_COMMENT = 'specialTagComment';
    
    /**
     * @var unknown
     */
    private $originalHTMLString = '';
    
    /**
     * @var unknown
     */
    private $originalHTMLArray = array();
    
    /**
     * @var unknown
     */
    private $parsingPosition = 0;
    
    /**
     * @var unknown
     */
    private $result = array();
    
    /**
     * @var unknown
     */
    private $compileResult = '';
    
    /**
     * @var unknown
     */
    private $compilingLevel = 0;
    
    /**
     * @param unknown $html
     */
    private function __construct( $html ) {
        $this->originalHTMLString = $html;
    }
    
    /**
     * @return string
     */
    private function doFormation() {
        $this->originalHTMLString = trim($this->originalHTMLString);
        $originalHtml = str_split($this->originalHTMLString);
        $this->originalHTMLArray = $originalHtml;
        $originalLength = count($originalHtml);
        $tags = array();
        
        while ( null !== ($char = $this->getChar() ) ) {
            if ( '<' !== $char ) {
                continue;
            }
            
            $tagName = $this->getTagName();
            $tags[] = $this->parseTag($tagName);
            $this->cleanBlankChars();
        }
        
        $this->result = $tags;
        return $this->compileTags();
    }
    
    /**
     * @return string
     */
    private function compileTags() {
        $this->compileResult = array();
        foreach ( $this->result as $tag ) {
            $this->compileTag($tag);
        }
        $this->compileResult = implode("\n", $this->compileResult);
        return $this->compileResult;
    }
    
    /**
     * @param unknown $tag
     */
    private function compileTag( $tag ) {
        $tagName = $tag['name'];
        $handler = 'compileTag'.ucfirst($tagName);
        if ( is_callable(array($this, $handler)) ) {
            call_user_func_array(array($this, $handler), array($tag));
            return;
        }
        
        $startTag = $this->compileStartTag($tag);
        $startSpaces = $this->compileLineStartSpaces();
        if ( in_array($tagName, self::$noEndTagNames) ) {
            $this->compileResult[] = $startSpaces.$startTag;
            return;
        }
        
        if ( empty($tag['children']) ) {
            $this->compileResult[] = $startSpaces.$startTag.'</'.$tagName.'>';
            return;
        }
        
        if ( 1==count($tag['children']) && is_string($tag['children'][0]) && in_array($tagName, self::$oneLineTagNames) ) {
            $this->compileResult[] = $startSpaces.$startTag.$tag['children'][0].'</'.$tagName.'>';
            return;
        }
        
        $this->compileResult[] = $startSpaces.$startTag;
        if ( is_string($tag['children']) ) {
            $this->compileResult[] = $startSpaces.$tag['children'];
            $this->compileResult[] = $startSpaces.'</'.$tagName.'>';
            return;
        }
        
        foreach ( $tag['children'] as $child ) {
            if ( is_string($child) ) {
                $this->compileResult[] = $startSpaces.'  '.$child;
            } else {
                $this->compilingLevel ++;
                $this->compileTag($child);
                $this->compilingLevel --;
            }
        }
        $this->compileResult[] = $startSpaces.'</'.$tagName.'>';
    }
    
    /**
     * @param unknown $tag
     */
    private function compileTagSpecialTagComment( $tag ) {
        $startSpaces = $this->compileLineStartSpaces();
        $this->compileResult[] = $startSpaces.'<!-- '.$tag['children'].' -->';
    }
    
    /**
     * @return string
     */
    private function compileLineStartSpaces() {
        $content = '';
        $level = $this->compilingLevel;
        while ( 0 < $level ) {
            $content .= '  ';
            $level --;
        }
        return $content;
    }
    
    /**
     * @param unknown $tag
     * @return string
     */
    private function compileStartTag( $tag ) {
        $content = array('<'.$tag['name']);
        foreach ( $tag['attributes'] as $key => $value ) {
            if ( is_numeric($key) ) {
                $content[] = $value;
            } else {
                $content[] = $key.'='.$value;
            }
        }
        $content = implode(' ', $content);
        $content .= '>';
        return $content;
    }
    /**
     * 解析标签。
     * @param string $tagName
     * @return array
     */
    private function parseTag ( $tagName ) {
        $tag = array(
            'name'          => $tagName,
            'attributes'    => array(),
            'children'      => array(),
        );
        
        $tag['attributes'] = $this->parseAttributes();
        if ( !in_array($tagName, self::$noEndTagNames) ) {
            $tag['children'] = $this->parseChildren($tagName);
        }
        return $tag;
    }
    
    /**
     * 解析子标签以及文本。
     * @param string $tagName
     * @return array
     */
    private function parseChildren( $tagName ) {
        $handler = 'parseTag'.ucfirst($tagName);
        if ( is_callable(array($this, $handler)) ) {
            return call_user_func(array($this, $handler));
        }
        
        $children = array();
        $this->cleanBlankChars();
        while ( null !== ($char = $this->getChar() ) ) {
            if ( '<' !== $char ) {
                $this->movePointer(-1);
                $children[] = trim($this->getStringBlock("<"));
                continue;
            }
            
            $childTagName = $this->getTagName();
            if ( $childTagName === '/'.$tagName ) {
                $this->getStringBlock('>');
                $this->movePointer(1);
                break;
            }
            
            if ( '/' ===$childTagName[strlen($childTagName)-1] ) {
                $childTagName = substr($childTagName, 0, strlen($childTagName)-1);
            }
            
            $children[] = $this->parseTag($childTagName);
            $this->cleanBlankChars();
        }
        return $children;
    }
    
    /**
     * 解析注释标签。
     * @return string
     */
    private function parseTagSpecialTagComment() {
        $parsed = substr($this->originalHTMLString, 0, $this->getPos());
        $commentStartPos = strrpos($parsed, '!--');
        $this->setPos($commentStartPos+3);
        
        $content = '';
        do {
            $content .= $this->getStringBlock('-');
            
            $endString = $this->getChars(3);
            if ( '-->' === $endString ) {
                break;
            }
            $content .= $endString;
        } while( true );
        return $content;
    }
    
    /**
     * 解析Script标签。
     * @return string
     */
    private function parseTagScript() {
        $content = '';
        do {
            $content .= $this->getStringBlock('<');
            
            $oldPos = $this->getPos();
            $this->movePointer(1);
            $tagName = $this->getTagName();
            if ( '/script' === $tagName ) {
                $this->getStringBlock('>');
                $this->movePointer(1);
                break;
            } else {
                $content .= '<';
                $this->setPos($oldPos+1);
            }
        } while( true );
        return trim($content);
    }
    
    /**
     * 从当前位置开始解析标签的所有属性。
     * @return array
     */
    private function parseAttributes() {
        $attributes = array();
        $this->cleanBlankChars();
        while ( '>' != ($char=$this->getChar()) ) {
            if ( '/' === $char ) {
                $this->getStringBlock(">");
                $this->movePointer(1);
                break;
            }
            
            $this->movePointer(-1);
            $attribute = $this->getAttribute();
            if ( is_array($attribute) ) {
                $attributes[$attribute['key']] = $attribute['value'];
            } else {
                $attributes[] = $attribute;
            }
            $this->cleanBlankChars();
        }
        return $attributes;
    }
    
    /**
     * 从当前位置开始一个获取标签属性。
     * @return string|array
     */
    private function getAttribute() {
        $this->cleanBlankChars();
        
        $startChar = $this->getChar();
        if ( '"' === $startChar ) {
            $name = '"'.$this->getStringBlock('"').$this->getChar();
        } else if ( "'" === $startChar ) {
            $name = "'".$this->getStringBlock("'").$this->getChar();
        } else {
            $this->movePointer(-1);
            $name = $this->getStringBlock("\f\n\r\t\v =>");
        }
        
        $this->cleanBlankChars();
        $nextChar = $this->getChar();
        
        if ( '=' !== $nextChar ) {
            $this->movePointer(-1);
            return $name;
        } else {
            $startChar = $this->getChar();
            if ( '"' === $startChar ) {
                $value = '"'.$this->getStringBlock('"').$this->getChar();
            } else if ( "'" === $startChar ) {
                $value = "'".$this->getStringBlock("'").$this->getChar();
            } else {
                $this->movePointer(-1);
                $value = $this->getStringBlock();
            }
            return array('key'=>$name, 'value'=>$value);
        }
    }
    
    /**
     * 获取下一个字符。
     * @return string
     */
    private function getChar() {
        if ( !isset($this->originalHTMLArray[$this->parsingPosition]) ) {
            return null;
        }
        
        $char = $this->originalHTMLArray[$this->parsingPosition];
        $this->parsingPosition ++;
        return $char;
    }
    
    /**
     * 从当前解析地址获取指定数量的字符。
     * @param integer $count
     * @return string
     */
    private function getChars( $count ) {
        $content = array();
        while ( $count>0 && (null !== ( $char=$this->getChar() )) ) {
            $content[] = $char;
            $count--;
        }
        return implode('', $content);
    }
    
    /**
     * 设置当前解析地址。
     * @param integer $pos
     */
    private function setPos( $pos ) {
        $this->parsingPosition = $pos;
    }
    
    /**
     * 获取当前解析地址
     * @return number
     */
    private function getPos() {
        return $this->parsingPosition;
    }
    
    /**
     * 根据偏移量移动当前解析地址。
     * @param integer $pos
     */
    private function movePointer( $pos ) {
        $this->parsingPosition += $pos;
    }
    
    /**
     * 从当前位置获取一个标签字符串。
     * @return string
     */
    private function getTagName() {
        $name = str_replace(str_split("\f\n\r\t\v >"), '', $this->getStringBlock("\f\n\r\t\v >"));
        if ( '!--' === substr($name, 0, 3) ) {
            $name = self::$SPECIAL_TAG_COMMENT;
        }
        return $name;
    }
    
    /**
     * 获取从当前匹配位置到指定结束字符之间的字符串。
     * @param string $stopChars
     * @return string
     */
    private function getStringBlock( $stopChars="\f\n\r\t\v " ) {
        $stopChars = str_split($stopChars);
        $startPos = $this->parsingPosition;
        $pos = $this->parsingPosition;
        $chars = $this->originalHTMLArray;
        $startChar = $chars[$pos];
        while ( isset($chars[$pos]) && !in_array($chars[$pos], $stopChars) ) {
            $pos ++;
        }
        $this->parsingPosition = $pos;
        return substr($this->originalHTMLString, $startPos, $pos-$startPos);
    }
    
    /**
     * 移动当前解析指针到下一个非空白字符。
     * @return void
     */
    private function cleanBlankChars() {
        $pos = $this->parsingPosition;
        $chars = $this->originalHTMLArray;
        $blankChars = str_split("\f\n\r\t\v ");
        while ( isset($chars[$pos]) && in_array($chars[$pos], $blankChars) ) {
            $pos ++;
        }
        $this->parsingPosition = $pos;
    }
    
    /**
     * @param string $html
     * @return string
     */
    public static function format( $html, $type, $config=array() ) {
        $formatter = new HtmlFormatter( $html );
        return $formatter->doFormation();
    }
}