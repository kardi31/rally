<?php

/**
 * Factory
 *
 * @author Michał Folga <michalfolga@gmail.com>
 */
class Index_DataTables_Factory {
    
    public static function factory(array $options) {
        
        if(empty($options)) {
            throw new Exception('No options included');
        }
        
        
        
        if(!isset($options['class']) ){// || !is_subclass_of($options['class'], 'Default_DataTables_DataTablesAbstract')) {
            throw new Exception('Adapter class not included');
        }
        
        if(!strlen($options['class'])) {
            throw new Exception('Adapter class not instantiable');
        }
        
        
        if(!isset($options['table'])) {
            throw new Exception('Table not included');
        }
        
        
        $explode = explode('_',$options['class']);
        require_once(BASE_PATH.'/modules/'.strtolower($explode[0]).'/library/DataTables/'.$explode[2].".php");
        
        $class = $options['class'];
        
        $object = new $class();
        
        $table = Doctrine_Core::getTable($options['table']);
        
        $baseQuery = $object->getBaseQuery($table);
        
        require_once(BASE_PATH.'/modules/index/library/DataTables/FilterFactory.php');
        
        $filterFactory = new Index_DataTables_FilterFactory();
        $returnQuery = $filterFactory->filterQuery($baseQuery,$options['fields']);
        
        return $returnQuery;
        
    }
}

