<?php

class ForumService extends Service{
    
    protected $categoryTable;
    protected $postTable;
    protected $threadTable;
    protected $favouriteTable;
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
        $this->favouriteTable = parent::getTable('forum','favourite');
    }
    
    public function getCategory($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->categoryTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getThread($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->threadTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getPost($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->postTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getAllCategories($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->categoryTable->findAll($hydrationMode);
    }
    
    public function getFullThread($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->threadTable->createQuery('t');
        $q->leftJoin('t.User u');
        $q->leftJoin('t.Category c');
        $q->select('u.*,t.*,c.*');
        $q->addWhere('t.'.$field.' = ?',$id);
        $q->addWhere('t.active = 1');
	return $q->fetchOne(array(),$hydrationMode);
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
    
    public function getLastCategoryThread($category_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->threadTable->createQuery('t');
        $q->leftJoin('t.User u');
        $q->addSelect('t.*,u.*');
        $q->addWhere('category_id = ?',$category_id);
        $q->orderBy('t.created_at DESC');
        $q->addWhere('t.active = 1');
        $q->limit(1);
	return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getAllCategoryThreads($category_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $limits = $this->getPageLimits(20);
        $q = $this->threadTable->createQuery('t');
        $q->leftJoin('t.Posts p');
        $q->leftJoin('t.User u1');
        $q->leftJoin('p.User u2');
        $q->select('t.*,p.*,u1.username,u2.username');
        $q->orderBy('t.created_at DESC, p.created_at DESC');
        $q->addWhere('t.category_id = ?',$category_id);
        $q->addWhere('t.active = 1');
        $q->limit($limits['limit']);
        $q->offset($limits['offset']);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getAllThreadPosts($thread_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $limits = $this->getPageLimits(20);
        $q = $this->postTable->createQuery('p');
        $q->leftJoin('p.Thread t');
        $q->leftJoin('t.User u1');
        $q->leftJoin('p.User u2');
        $q->select('t.*,p.*,u1.username,u2.username');
        $q->orderBy('p.created_at ASC');
        $q->addWhere('p.thread_id = ?',$thread_id);
        $q->addWhere('p.active = 1');
        $q->limit($limits['limit']);
        $q->offset($limits['offset']);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function addPost($values,$thread,$user){
                
        $data = array();
        $data['content'] = $values['content'];
        $data['thread_id'] = $thread['id'];
        $data['category_id'] = $thread['category_id'];
        $data['user_id'] = $user['id'];
        $post = $this->postTable->getRecord();
        $post->fromArray($data);
        $post->save();
        
        return $post;
    }
    
    public function editPost($values){
                
        
        $post = $this->getPost($values['id']);
        $post->fromArray($values);
        $post->save();
        
        return $post;
    }
    
    public function addThread($values,$category,$user){
                
        $data = array();
        $data['title'] = $values['title'];
        $data['content'] = $values['content'];
        $data['category_id'] = $category['id'];
        $data['user_id'] = $user['id'];
        $post = $this->threadTable->getRecord();
        $post->fromArray($data);
        $post->save();
        
        return $post;
    }
    
    public function editThread($values){
                
        $thread = $this->getThread($values['id']);
        $thread->fromArray($values);
        $thread->save();
        
        return $thread;
    }
    
    public function addCategoryToFavourite($category,$user){
                
        $data = array();
        $data['category_id'] = $category['id'];
        $data['user_id'] = $user['id'];
        $post = $this->favouriteTable->getRecord();
        $post->fromArray($data);
        $post->save();
        
        return $post;
    }
    
    public function getFavouriteCategories($user_id,$full = false,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->favouriteTable->createQuery('f');
        if(!$full){
            $q->select('f.category_id');
        }
        else{
            $q->select('f.*,u.*,c.*,t.*');
            $q->leftJoin('f.User u');
            $q->leftJoin('f.Category c');
            $q->leftJoin('c.Threads t');
        }
        $q->addWhere('f.user_id = ?',$user_id);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getFavouriteCategory($category_id,$user_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->favouriteTable->createQuery('f');
        $q->select('f.id');
        $q->addWhere('f.category_id = ?',$category_id);
        $q->addWhere('f.user_id = ?',$user_id);
	return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function removeCategoryFromFavourite($category_id,$user_id){
        $favourite = $this->getFavouriteCategory($category_id,$user_id);
        $favourite->delete();
    }
    
}
    
?>
