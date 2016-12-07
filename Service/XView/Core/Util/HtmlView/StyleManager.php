<?php
namespace X\Service\XView\Core\Util\HtmlView;
/**
 * 
 */
use X\Service\XView\Core\Util\Exception;
/**
 * 
 */
class StyleManager {
    /**
     * The style list that current page used.
     * @var array
     */
    protected $styles = array(
    /* 'body@screen' => array(
     *   'item'  => 'body'
     *   'style' => array('background-color'=>'red'),
     *   'media' => 'screen',
     * ),
     */
    );
    
    /**
     * Add style into current page.
     * @param string $item The name of item.
     * @param array $style The css attributes of the item.
     */
    public function add( $item, array $style, $media=null ) {
        if ( empty($item) || empty($style) ) {
            throw new Exception('item or style can not be empty.');
        }
        
        $key = $this->getKeyForStyles($item, $media);
        if ( !isset($this->styles[$key]) ) {
            $this->styles[$key]['style'] = $style;
        } else {
            $this->styles[$key]['style'] = array_merge($this->styles[$key]['style'], $style);
        }
        $this->styles[$key]['item'] = $item;
        $this->styles[$key]['media'] = $media;
    }
    
    /**
     * Set the css attribute value on an exists item.
     * @param string $item The name of the item.
     * @param string $attribute The name of the attribute.
     * @param mixed $value The value of of the attribute.
     * @return void
     */
    public function set($item,$attribute,$value,$media=null ) {
        if ( empty($item) || empty($attribute) || empty($value) ) {
            throw new Exception('item, attribute or value can not be empty.');
        }
        
        $key = $this->getKeyForStyles($item, $media);
        if ( !isset($this->styles[$key]) ) {
            $this->styles[$key] = array();
            $this->styles[$key]['item'] = $item;
            $this->styles[$key]['media'] = $media;
        }
        $this->styles[$key]['style'][$attribute] = $value;
    }
    
    /**
     * Get all the definded style information.
     * @return array
     */
    public function getStyles( ) {
        return $this->styles;
    }
    
    /**
     * Get the value of the attribute on given item.
     * @param string $item The name of the item.
     * @param string $attribute The name of the attribute.
     * @return mixed
     */
    public function get($item, $attribute, $media=null) {
        $key = $this->getKeyForStyles($item, $media);
        if ( !isset($this->styles[$key]) ) {
            return null;
        } else if ( !isset($this->styles[$key]['style'][$attribute]) ) {
            return null;
        } else {
            return $this->styles[$key]['style'][$attribute];
        }
    }
    
    /**
     * Remove the style item from current page.
     * @param string $item The item to remove.
     */
    public function remove( $item, $media=null ) {
        $key = $this->getKeyForStyles($item, $media);
        if ( isset($this->styles[$key]) ) {
            unset($this->styles[$key]);
        }
    }
    
    /**
     * Remove attribute from style list.
     * @param string $item The name of item.
     * @param string $name The name of attribute.
     */
    public function removeAttribute( $item, $name, $media=null ) {
        $key = $this->getKeyForStyles($item, $media);
        if ( !isset($this->styles[$key]) ) {
            return;
        }
        
        if ( !isset($this->styles[$key]['style'][$name]) ) {
            return;
        }
        
        unset($this->styles[$key]['style'][$name]);
        if ( 0 === count($this->styles[$key]['style']) ) {
            unset($this->styles[$key]);
        }
    }
    
    /**
     * Get the conetnet of styles.
     * @return string
     */
    public function toString() {
        if ( 0 === count($this->styles) ) {
            return null;
        }
        
        $styleList = array();
        foreach ( $this->styles as $item => $attribute ) {
            $itemStyle = array();
            foreach ( $attribute['style'] as $name => $value ) {
                $itemStyle[] = $name.':'.$value;
            }
            $itemStyle = $attribute['item'].' {'.implode(';', $itemStyle).';}';
            if ( null !== $attribute['media'] ) {
                $itemStyle = '@media '.$attribute['media'].' { '.$itemStyle.' }';
            }
            $styleList[] = $itemStyle;
        }
        $styleList = implode("\n", $styleList);
        $styleList = '<style type="text/css">'."\n".$styleList."\n".'</style>';
        return $styleList;
    }
    
    /**
     * Get the key for getting data from styles array.
     * @param string $item
     * @param string $media
     * @return string
     */
    private function getKeyForStyles( $item, $media ) {
        return is_null($media) ? $item : sprintf('%s@%s', $item, $media);
    }
}