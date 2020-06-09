<?php
namespace bachphuc\PhpLaravelHelpers;

trait WithImage {
    public function uploadPhoto($params = [], $bCreateThumbnail = true){
        $filePath = isset($params['name']) ? $params['name'] : 'image';
        $field = isset($params['field']) ? $params['field'] : 'image';
        $bSkipProcessSize = isset($params['skip_size']) ? $params['skip_size'] : true;
        if(!$this->hasField($field)) return false;

        if (request()->hasFile($filePath)) {           
            $path = photo_storage(request()->{$filePath});
            photo_resize($path);
            
            if(!$bSkipProcessSize){
                list($width, $height) = getimagesize($path);
                if($this->hasField('image_width')){
                    $this->image_width = $width;
                }
                if($this->hasField('width')){
                    $this->width = $width;
                }
                if($this->hasField('image_height')){
                    $this->image_height = $height;
                }
                if($this->hasField('height')){
                    $this->height = $height;
                }
                if($this->hasField('image_ratio')){
                    $this->image_ratio = $width / $height;
                }
                
                if(!empty($this->maskImagePath)){
                    photo_mask($path, $this->maskImagePath);
                }
            }
            
            $this->{$field} = $path;
            $this->save();
            if($bCreateThumbnail){
                $sizes = isset($params['sizes']) ? $params['sizes'] : $this->thumbnailSizes;
                $this->createThumbnail($field, $sizes);
            }
        }
        else{
            return false;
        }
    }

    public function createThumbnail($name = 'image', $sizes = []){
        if(!$this->hasField($name)) return;
        if(empty($sizes)){
            $sizes = [120, 270, 300, 320, 500, 720];
        }
        
        $bChange = false;
        foreach($sizes as $size){
            $width = is_array($size) ? $size['width'] : $size;
            $field = 'thumbnail_' . $width;
            if($this->hasField($field)){
                $bChange = true;
                if(is_array($size)){
                    $this->{$field} = photo_copy_and_crop($this->{$name}, $size['width'], $size['height'], 90);
                }
                else{
                    $this->{$field} = photo_copy_and_crop($this->{$name}, $size, $size, 90);
                }
                
            }
        }
        if($bChange){
            $this->save();
        }
    }
}