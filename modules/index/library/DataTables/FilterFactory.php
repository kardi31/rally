<?php

/**
 * Factory
 *
 * @author Michał Folga <michalfolga@gmail.com>
 */
class Index_DataTables_FilterFactory {
    
    public function filterQuery($q,$fields){
        
        $q = $this->sortQuery($q, $fields);
        
        $q = $this->filterQ($q,$fields);
        
        return $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    function sortQuery($q,$fields){
        
            if(isset($_REQUEST['iSortCol_0'])){
                $col = $_REQUEST['iSortCol_0']-1;
                $direction = $_REQUEST['sSortDir_0'];
                $q->addOrderBy($fields[$col]." ".$direction);
            }
            
            return $q;
    }
    
    function filterQ($q,$fields){
        
        // swap keys with values
        $request = array_flip(array_filter($_REQUEST,'strlen'));
        
        // search for elements that starts with custom_filter
        $customFilter = array_filter($request,function($key) {
            return substr($key, 0, 13) === 'custom_filter';
        });
        foreach($customFilter as $filterValue => $filterElement):
            
            $filterParts = explode('_',$filterElement);
            // type of filter (text,dateFrom,dateTo,select)
            if ( $filterParts[2] == 'text'):
                
                $fieldDelimiter = $filterParts[3];
                $searchField = $fields[$fieldDelimiter-1];
                $q->addWhere($searchField ." like ?",'%'.$filterValue.'%');
                
                // filter select - remove "-1" value to prevent filtering empty results
                
            elseif ( $filterParts[2] == 'select' && $filterValue !="-1"):
                $fieldDelimiter = $filterParts[3];
                $searchField = $fields[$fieldDelimiter-1];
                if(is_numeric($filterValue)){
                    $q->addWhere($searchField ." = ?",$filterValue);
                }
                else
                    $q->addWhere($searchField ." like ?",'%'.$filterValue.'%');
                    
                // date range From
                
            elseif ( $filterParts[2] == 'dateFrom' ):
                $dateFrom = TK_Text::timeFormat($filterValue,'Y-m-d','d/m/Y');
                $fieldDelimiter = $filterParts[3];
                $searchField = $fields[$fieldDelimiter-1];
                $q->addWhere($searchField ." >= ?",$dateFrom);
                    
                // date range To
                
            elseif ( $filterParts[2] == 'dateTo' ):
                $dateFrom = TK_Text::timeFormat($filterValue,'Y-m-d','d/m/Y');
                $fieldDelimiter = $filterParts[3];
                $searchField = $fields[$fieldDelimiter-1];
                $q->addWhere($searchField ." <= ?",$dateFrom);
                    
                
            endif;
        
        endforeach;
        
        return $q;
        
    }
}

    