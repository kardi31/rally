<?php

class ForumService extends Service{
    
    protected $categoryTable;
    protected $postTable;
    protected $threadTable;
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new ForumService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->categoryTable = parent::getTable('forum','category');
        $this->postTable = parent::getTable('forum','post');
        $this->threadTable = parent::getTable('forum','thread');
    }
    
    public function getCategory($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->categoryTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getAllCategories($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->categoryTable->findAll($hydrationMode);
    }
    
    public function countCategoryThreads($category_id){
        $q = $this->threadTable->createQuery('t');
        $q->select('count(t.id) as thread_count');
        $q->addWhere('t.category_id = ?',$category_id);
        $q->addWhere('t.active = 1');
	return $q->fetchOne(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    public function countCategoryPosts($category_id){
        $q = $this->postTable->createQuery('p');
        $q->select('count(p.id) as post_count');
        $q->addWhere('p.category_id = ?',$category_id);
        $q->addWhere('p.active = 1');
	return $q->fetchOne(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    public function getLastCategoryPost($category_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->postTable->createQuery('p');
        $q->leftJoin('p.User u');
        $q->addSelect('p.*,u.*');
        $q->addWhere('category_id = ?',$category_id);
        $q->orderBy('p.created_at DESC');
        $q->addWhere('p.active = 1');
        $q->limit(1);
	return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getAllCategoryThreads($category_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $limits = $this->getPageLimits(20);
        $q = $this->threadTable->createQuery('t');
        $q->select('*');
        $q->leftJoin('t.Posts p');
        $q->orderBy('t.created_at DESC, p.created_at DESC');
        $q->addWhere('t.category_id = ?',$category_id);
        $q->addWhere('t.active = 1');
        $q->limit($limits['limit']);
        $q->offset($limits['offset']);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function addForumMoney($forum_id,$amount,$moneyType,$description = false){
	if($amount==0||empty($amount)){
	    return ;
	}
	
        $forum = $this->getForum($forum_id);
	
	$newCash = (int)$forum['cash'] + $amount;
	
	$forum->set('cash',$newCash);
	$forum->save();
        
        $this->saveForumFinance($forum_id,$amount,$moneyType,true,$description);
        
        return $forum;
    }
    
    public function removeForumMoney($forum_id,$amount,$moneyType,$description = false){
	if($amount==0||empty($amount)){
	    return ;
	}
	
        $forum = $this->getForum($forum_id);
	
	$newCash = (int)$forum['cash'] - $amount;
	
	$forum->set('cash',$newCash);
	$forum->save();
        
        $this->saveForumFinance($forum_id,$amount,$moneyType,false,$description);
    }
    
    
    public function saveForumFinance($forum_id,$amount,$detailed_type,$income = false,$description = null){
        $financeArray = array();
        $financeArray['forum_id'] = $forum_id;
        $financeArray['amount'] = $amount;
        $financeArray['detailed_type'] = $detailed_type;
        if(!empty($description)){
            $financeArray['description'] = $description;
        }
        $financeArray['income'] = $income;
        
        $financeArray['save_date'] = date('Y-m-d H:i:s'); 
        
        $record = $this->financeTable->getRecord();
        $record->fromArray($financeArray);
        $record->save();
        
        return $record;
    }
    
    public function getSimpleReport($forum_id,$prevWeek = false){
        if(!$prevWeek){
           $dateFrom = date('Y-m-d H:i:s', strtotime('this week monday'));
           $dateTo = date('Y-m-d H:i:s',strtotime('next week monday'));
        }
        else{
           $dateFrom = date('Y-m-d H:i:s', strtotime('this week monday - '.$prevWeek.' week'));
           $dateTo = date('Y-m-d H:i:s',strtotime('next week monday - '.$prevWeek.' week'));
        }
        
        
        $q = $this->financeTable->createQuery('f');
        $q->addWhere('save_date < ?',$dateTo);
        $q->addWhere('save_date > ?',$dateFrom);
        $q->addWhere('forum_id = ?',$forum_id);
        $q->groupBy('detailed_type');
        $q->orderBy('income,detailed_type');
        $q->select('sum(amount) as amount,detailed_type,income');
        $result = $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
        $sortedResult = $this->sortSimpleReport($result);
        return $sortedResult;
    }
    
    public function sortSimpleReport($results){
        $sortedResult = array('income' => array(),'expense' => array());
        foreach($results as $result):
            if($result['income']){
                $sortedResult['income'][] = $result;
            }
            else{
                $sortedResult['expense'][] = $result;
            }
        endforeach;
        
        return $sortedResult;
    }
    
    
    
    public function getFinanceTypes(){
        return $this->financeTypes;
    }
    
    public function getAdvancedReport($forum_id,$prevWeek = false){
        if(!$prevWeek){
           $dateFrom = date('Y-m-d H:i:s', strtotime('this week monday'));
           $dateTo = date('Y-m-d H:i:s',strtotime('next week monday'));
        }
        else{
           $dateFrom = date('Y-m-d H:i:s', strtotime('this week monday - '.$prevWeek.' week'));
           $dateTo = date('Y-m-d H:i:s',strtotime('next week monday - '.$prevWeek.' week'));
        }
        
        
        $q = $this->financeTable->createQuery('f');
        $q->addWhere('save_date < ?',$dateTo);
        $q->addWhere('save_date > ?',$dateFrom);
        $q->addWhere('forum_id = ?',$forum_id);
        $q->orderBy('save_date DESC');
        $q->select('amount,DATE(save_date) as save_date,detailed_type,income,description');
        return $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    public function selectLeagueFullForums($league_name,$current_season = true,$hydrationMode = Doctrine_Core::HYDRATE_RECORD,$limit = false){
        // get league from current season by default
        // otherwise use season that is passed as a param
	if($current_season)
            $season = LeagueService::getInstance()->getCurrentSeason();
        else
            $season = (int)$current_season;
        $q = $this->categoryTable->createQuery('t');
        $q->leftJoin('t.Seasons s');
        $q->addSelect('t.*');
	$q->where('s.league_name = ?',$league_name);
        $q->addWhere('s.season = ?',$season);
        if($limit)
            $q->limit($limit);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function canAfford($forum,$price){
        // forum must be an instance of Forum_Model_Doctrine_Forum
        if(is_integer($forum)){
            $forum = $this->getForum($forum,'id');
        }
        if($forum->cash>=$price)
            return true;
        else
            return false;
    }
}
?>
