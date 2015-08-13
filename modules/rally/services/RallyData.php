<?php



class RallyDataService extends Service{
    
    protected $rallyTable;
    protected $stageTable;
    protected $surfaceTable;
    protected $surfaces = array('tarmac','gravel','rain','snow');
    protected $minsArray = array('00','15','30','45');
    
    
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new RallyDataService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->rallyTable = parent::getTable('rally','dataRally');
        $this->surfaceTable = parent::getTable('rally','dataSurface');
        $this->stageTable = parent::getTable('rally','dataStage');
    }
    
    public function getAllRallies(){
        $q = $this->rallyTable->createQuery('rt');
        $q->orderBy('rt.id');
        return $q->execute();
//        return $this->rallyTable->findAll();
    }
    
    public function getRallyStages($id,$field='id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->stageTable->createQuery('s');
        $q->leftJoin('s.Rally r');
        $q->addWhere('r.'.$field.' = ?',$id);
        $q->select('s.*,r.id');
        return $q->execute(array(),$hydrationMode);
    }
    
    public function saveRallyDataFromRally($rally){
        $dataRally = $this->rallyTable->getRecord();
        $dataRally->fromArray($rally->toArray());
        $dataRally->save();
        
        foreach($rally->get('Stages') as $stage):
            $dataStage = $this->stageTable->getRecord();
            $dataStage->fromArray($stage->toArray());
            $dataStage->save();
        endforeach;
        
        foreach($rally->get('Surfaces') as $surface):
            $dataSurface = $this->surfaceTable->getRecord();
            $dataSurface->fromArray($surface->toArray());
            $dataSurface->save();
            $dataSurface->set('id',$surface['id']);
            $dataSurface->save();
        endforeach;
        
    }
    
}
?>
