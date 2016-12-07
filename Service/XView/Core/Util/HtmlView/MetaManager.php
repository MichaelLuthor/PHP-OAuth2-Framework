<?php
namespace X\Service\XView\Core\Util\HtmlView;
/**
 * 
 */
class MetaManager {
    /**
     * The metas contains all meta data of the page.
     * @var array
     */
    protected $metas = array(
    /* 'identifier'=> array(
     *      'name'=>'keywords',
     *      'content'=>'KeyWordList',
     *      'charset'=>'utf-8',
     *      'http-equiv'=>'refresh',
     * ), */
    );
    
    /**
     * Set the charset of current page.
     * @param string $charset The name charset.
     */
    public function setCharset( $charset='UTF-8' ) {
        $content = sprintf('text/html; charset=%s', $charset);
        $this->addMetaData('page.charset', null, $content, null, 'content-type');
    }
    
    /**
     * Add keyword to current page.
     * @param string $keyword The keyword to add.
     * @param string $_ ...
     */
    public function addKeyword( $keyword ) {
        $this->addKeywords(func_get_args());
    }
    
    /**
     * @param unknown $keywords
     */
    public function addKeywords( $newKeywords ) {
        if ( empty($newKeywords) || !is_array($newKeywords)) {
            return;
        }
        
        $keywords = $this->getAttribute('page.keyword', 'content');
        if ( null === $keywords ) {
            $keywords = array();
        } else {
            $keywords = explode(',', $keywords);
        }
        $keywords = array_merge($keywords, $newKeywords);
        $keywords = array_unique($keywords);
        $keywords = implode(',', $keywords);
        $this->addMetaData('page.keyword', 'keywords', $keywords );
    }
    
    /**
     * Jump to another page after given seconds.
     * @param string $url The target url
     * @param integer $seconds The time to stay on this page.
     */
    public function refreshTo( $url, $seconds=0 ) {
        $content = sprintf('%d; URL=%s', $seconds, $url);
        $this->addMetaData('page.refresh', null, $content, null, 'refresh');
    }
    
    /**
     * Add author information of current page.
     * @param string $author The information about author
     */
    public function addAuthor( $author ) {
        $this->addMetaData('page.author', 'author' , $author);
    }
    
    /**
     * Add description of current page.
     * @param string $description The description of current page.
     */
    public function addDescription ( $description ) {
        $this->addMetaData('page.description', 'description' , $description);
    }
    
    /**
     * Set expired tiem of current page.
     * @param int $time The time timestamp
     */
    public function setExpireTime( $time ) {
        $time = gmstrftime('%A %d %B %Y %H:%M GMT', $time);
        $this->addMetaData('page.expires', null, $time, null, 'expires');
    }
    
    /**
     * Set the frequency that search comes.
     * @param int $time The number of day to revisi.
     */
    public function setRevisitAfter( $time = 3 ) {
        $time = ( 1 == $time ) ? '1 Day' : $time.' Days';
        $this->addMetaData('page.revisit.after', 'revisit-after', $time);
    }
    
    /**
     * Set copyright for current page.
     * @param string $copyright
     */
    public function setCopyright( $copyright ) {
        $this->addMetaData('page.copyright', 'Copyright', $copyright);
    }
    
    /**
     * Tell the robots how to handle this page.
     * @param string|array $term The way how the rebots should handle.
     * @param string $robots The name of robot, lik 'googlebot'
     */
    public function setRobots( $term, $robots) {
        if ( is_array($term) ) {
            $term = implode(',', $term);
        }
        $this->addMetaData('page.robots', $robots, $term);
    }
    
    /**
     * Let client do not get the page form local cache.
     * @return void
     */
    public function disableTheClientCache() {
        $this->addMetaData('page.pragma.no.cach', null, 'No-cach', null, 'Pragma');
    }
    
    /**
     * Add meta data to current page.
     *
     * @param string $identifier The identifier of the meta data.
     * @param string $name The name of the meta data.
     * @param string $content The value of the meta data.
     * @param string $charset The charset of the meta data.
     * @param string $httpEquiv The http-equiv of the meta data.
     */
    public function addMetaData($identifier, $name=null, $content=null, $charset=null, $httpEquiv=null ) {
        $this->metas[$identifier] = array(
            'name'          => $name,
            'content'       => $content,
            'charset'       => $charset,
            'http-equiv'    => $httpEquiv,
        );
    }
    
    /**
     * @param unknown $identifier
     * @param unknown $attributes
     */
    public function addMetaByArray( $identifier, $attributes ) {
        $this->metas[$identifier] = $attributes;
    }
    
    /**
     * Get meta data by identifier
     *
     * @param string $identifier The identifier of meta data.
     * @return array|NULL
     */
    public function getMeta( $identifier ) {
        if ( isset($this->metas[$identifier]) ) {
            return $this->metas[$identifier];
        } else {
            return null;
        }
    }
    
    /**
     * Get the attribute value of meta data.
     *
     * @param string $identifier The identifier of the meta data.
     * @param string $attribute The name of attribute.
     *
     * @return NULL|array
     */
    public function getAttribute($identifier, $attribute) {
        $meta = $this->getMeta($identifier);
        if ( null===$meta || !isset($meta[$attribute])) {
            return null;
        }
        return $meta[$attribute];
    }
    
    /**
     * Add Open Graph Data
     * @param unknown $identifier
     * @param unknown $property
     * @param unknown $content
     */
    private function addOpenGraphData( $identifier, $property, $content ) {
        $this->metas[$identifier] = array(
            'property'  => $property,
            'content'   => $content
        );
    }
    
    /**
     * @param unknown $title
     */
    public function setOGTitle( $title ) {
        $this->addOpenGraphData('OpenGraph:Title', 'og:title', $title);
    }
    
    /**
     * @param string $tyle The value could be article|book|movie
     */
    public function setOGType( $tyle ) {
        $this->addOpenGraphData('OpenGraph:Type', 'og:type', $tyle);
    }
    
    /**
     * @param unknown $url
     */
    public function setOGURL( $url ) {
        $this->addOpenGraphData('OpenGraph:URL', 'og:url', $url);
    }
    
    /**
     * @param unknown $image
     */
    public function setOGImage( $image ) {
        $this->addOpenGraphData('OpenGraph:Image', 'og:image', $image);
    }
    
    /**
     * @param unknown $name
     */
    public function setOGSiteName( $name ) {
        $this->addOpenGraphData('OpenGraph:SiteName', 'og:site_name', $name);
    }
    
    /**
     * @param unknown $admins
     */
    public function setOGAdmins( $admins, $mark='og:admins' ) {
        $this->addOpenGraphData('OpenGraph:Admins', $mark, $admins);
    }
    
    /**
     *
     * @param unknown $description
     */
    public function setOGDescription( $description ) {
        $this->addOpenGraphData('OpenGraph:Description', 'og:description', $description);
    }
    
    /**
     * Get the content of meta data.
     * @return string
     */
    public function toString() {
        $metaList = array();
        foreach ( $this->metas as $meta ) {
            $meta = array_filter($meta);
            foreach ( $meta as $attribute => $value ) {
                $meta[$attribute] = $attribute.'="'.$value.'"';
            }
            $metaList[] = '<meta '.implode(' ', $meta).' />';
        }
    
        if ( 0 === count($metaList) ) {
            return null;
        }
    
        $metaList = implode("\n", $metaList);
        return $metaList;
    }
}