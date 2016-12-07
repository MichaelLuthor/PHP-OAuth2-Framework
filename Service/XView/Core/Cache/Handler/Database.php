<?php
namespace X\Service\XView\Core\Cache\Handler;
use X\Service\XView\Core\Util\InterfaceViewCacheHandler;
use X\Core\X;
use X\Service\XDatabase\Core\Util\Exception as XDatabaseException;
use X\Service\XDatabase\Service as XDatabaseService;
class Database implements InterfaceViewCacheHandler {
    private $service = null;
    private $database = null;
    private $lifetime = null;
    private $tablename = null;
    /**
     * @param unknown $config
     */
    public function __construct( $config ) {
        $this->service = X::system()->getServiceManager()->get(XDatabaseService::getServiceName());
        $this->database = $this->service->get($config['dbname']);
        $this->lifetime = $config['default_lifetime'];
        $this->tablename = $config['tablename'];
    }
    
    /**
     * {@inheritDoc}
     * @see \X\Service\XView\Core\Util\InterfaceViewCacheHandler::cacheContent()
     */
    public function cacheContent($name, $content, $mark = null, $lifetime = null) {
        if ( null === $lifetime ) {
            $lifetime = $this->lifetime;
        }
        
        $query = 'INSERT INTO %s VALUES (%s,%s,%s,"%s",%s)';
        $query = sprintf($query, 
            $this->database->quoteTableName($this->tablename),
            $this->database->quote($this->generateID($name, $mark)),
            $this->database->quote($name),
            $this->database->quote($mark),
            date('Y-m-d H:i:s', time()+$lifetime),
            $this->database->quote($content)
        );
        try { 
            $this->database->exec($query);
        } catch ( XDatabaseException $e ) {
            $this->createCacheTable();
            $this->database->exec($query);
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \X\Service\XView\Core\Util\InterfaceViewCacheHandler::isCacheAvailable()
     */
    public function isCacheAvailable($name, $mark = null) {
        $query = 'SELECT COUNT(*) AS cache_count FROM %s WHERE id=%s';
        $query = sprintf($query, 
            $this->database->quoteTableName($this->tablename),
            $this->database->quote($this->generateID($name, $mark))
        );
        try {
            $result = $this->database->query($query);
            return 1 === (int)$result['cache_count'];
        } catch ( XDatabaseException $e ) {
            return false;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \X\Service\XView\Core\Util\InterfaceViewCacheHandler::clean()
     */
    public function clean($name, $mark = null) {
        $query = 'DELETE FROM %s WHERE name=%s';
        if ( null !== $mark ) {
            $query .= ' AND mark=%s';
        }
        $query = sprintf($query, 
            $this->database->quoteTableName($this->tablename),
            $this->database->quote($name),
            $this->database->quote($mark)
        );
        try {
            $this->database->exec($query);
        } catch ( XDatabaseException $e ) {}
    }
    
    /**
     * @return void
     */
    public function cleanAll() {
        $query = 'DELETE FROM %s';
        $query = sprintf($query,$this->database->quoteTableName($this->tablename));
        try {
            $this->database->exec($query);
        } catch ( XDatabaseException $e ) {}
    }

    /**
     * {@inheritDoc}
     * @see \X\Service\XView\Core\Util\InterfaceViewCacheHandler::getContent()
     */
    public function getContent($name, $mark = null) {
        $id = $this->generateID($name, $mark);
        $query = 'SELECT * FROM %s WHERE id=%s';
        $query = sprintf($query,
            $this->database->quoteTableName($this->tablename),
            $this->database->quote($id)
         );
        try {
            $result = $this->database->query($query);
        }  catch ( XDatabaseException $e ) {
            return null;
        }
        
        if ( empty($result) ) {
            return null;
        }
        
        if ( time() > strtotime($result[0]['expired_at']) ) {
            $this->deleteCache($id);
            return null;
        }
        return $result[0]['content'];
    }
    
    /**
     * @param unknown $id
     */
    private function deleteCache( $id ) {
        $query = 'DELETE FROM %s WHERE id=%s';
        $query = sprintf($query,
            $this->database->quoteTableName($this->tablename),
            $this->database->quote($id)
        );
        $this->database->exec($query);
    }
    
    /**
     * @param unknown $name
     * @param unknown $mark
     */
    private function generateID( $name, $mark=null ) {
        return $name.$mark;
    }
    
    /**
     * @return void
     */
    private function createCacheTable() {
        $query = 'CREATE TABLE `'.$this->tablename.'` (
          `id` varchar(64) NOT NULL,
          `name` varchar(32) NOT NULL,
          `mark` varchar(32) NOT NULL,
          `expired_at` datetime NOT NULL,
          `content` text NOT NULL,
          PRIMARY KEY (`id`)
        )';
        $this->database->exec($query);
    }
}