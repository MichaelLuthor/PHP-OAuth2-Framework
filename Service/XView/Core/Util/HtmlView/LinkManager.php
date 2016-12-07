<?php
namespace X\Service\XView\Core\Util\HtmlView;
/**
 * 
 */
use X\Service\XView\Core\Util\Exception;
/**
 * 
 */
class LinkManager {
    /**
     * @var \X\Service\XView\Core\Handler\Html
     */
    private $view = null;
    
    /**
     * @param \X\Service\XView\Core\Handler\Html $view
     */
    public function __construct( $view ) {
        $this->view = $view;
    }
    
    /**
     * The links list in head element.
     * @var array
     */
    protected  $links = array(
    /* 'identifier' => array(
     *      'rel'=>'stylesheet',
     *      'href'=>'style.css',
     *      'type'=>'text/css',
     *      'hreflang'=>'en',
     *      'media'=>'print',
     *      'sizes'=>'16x16'
     * ),
     */
    );
    
    /**
     * Add css link to current page.
     * @param string $identifier The name of the css
     * @param string $link The link address of the css.
     */
    public function addCSS( $identifier, $link ) {
        $this->addLink($identifier, 'stylesheet', 'text/css', $link);
    }
    
    /**
     * Set the favicon for current page by given path.
     * @param string $path The page where the icon stored.
     * @return void
     */
    public function setFavicon( $path='/favicon.ico' ) {
        $this->addLink('favicon', 'icon', 'image/x-icon', $path);
    }
    
    /**
     * Add link to this page.
     * @param string $identifier The name of the link, use to idenity the link.
     * @param string $rel The rel value of the link
     * @param string $type The type value of the link
     * @param string $href The href value of the link
     * @param string $media The media value of the link
     * @param string $hreflang The hreflang value of the link
     * @param string $sizes The sizes value of the link
     */
    public function addLink( $identifier, $rel=null, $type=null, $href=null, $media=null, $hreflang=null, $sizes=null ) {
        $attributes = array(
            'rel'       => $rel,
            'href'      => $href,
            'type'      => $type,
            'hreflang'  => $hreflang,
            'media'     => $media,
            'sizes'     => $sizes
        );
        
        if ( !isset($this->links[$identifier]) ) {
            $this->links[$identifier] = $attributes;
        } else {
            $this->links[$identifier] = array_merge($this->links[$identifier],$attributes);
        }
    }
    
    /**
     * @param unknown $identifier
     */
    public function remove( $name ) {
        if ( $this->has($name) ) {
            unset($this->links[$name]);
        }
    }
    
    /**
     * @param unknown $name
     */
    public function has( $name ) {
        return isset($this->links[$name]);
    }
    
    /**
     * Get the link information by name
     * @param string $name The name of the link
     * @return array
     */
    public function get( $name ) {
        if ( !$this->has($name) ) {
            throw new Exception('Link "'.$name.'" does not exists.');
        }
        return $this->links[$name];
    }
    
    /**
     * Get all the names of link of current page.
     * @return array
     */
    public function getList() {
        return array_keys($this->links);
    }
    
    /**
     * Get the content of links
     * @return string
     */
    public function toString() {
        $linkList = array();
        foreach ( $this->links as $name => $link ) {
            $linkString = array('<link');
            foreach ( $link as $attr => $value ) {
                if ( null === $value ) {
                    continue;
                }
                $linkString[] = $attr.'="'.$value.'"';
            }
            $linkString[] = '/>';
            $linkList[] = implode(' ', $linkString);
        }
        
        if ( 0 === count($linkList) ) {
            return null;
        }
        
        $linkList = implode("\n", $linkList);
        return $linkList;
    }
}