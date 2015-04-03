<?php

class MediaService extends Service{
    
    private static $instance = NULL;

    private $path = './media/';
    
    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new MediaService();
       return self::$instance;
    }
    
    public function uploadPhoto($photoName,$dir){
        $target_dir = $this->path.$dir;
        
        $photoSlug = $this->generateFileSlug($_FILES[$photoName]["name"],$target_dir);
        if (move_uploaded_file($_FILES[$photoName]["tmp_name"], $target_dir."/".$photoSlug)) {
            return $photoSlug;
        } else {
            return null;
        }
        
        
    }
    
    public function generateFileSlug($photoName,$dir){
        
        $explode = explode('.',$photoName);
        $filename = $explode[0];
        $extension = $explode[1];
        
        $slug = TK_Text::createSlug($filename);
        
        if(file_exists($dir.$filename.".".$extension)){
            $key = 1;
            $newFilename = $filename;
            while(file_exists($dir.$newFilename.".".$extension)){
                
                $newFilename = $filename."-".$key;
                $key++;
            }
        }
        
        return $slug.".".$filename;
        
    }
}
?>
