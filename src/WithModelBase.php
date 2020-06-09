<?php
namespace bachphuc\PhpLaravelHelpers;

trait WithModelBase {
    public function hasField($field){
        if(!$field) return false;
        $coreFields = ['id', 'created_at', 'updated_at'];
        if(in_array($field, $coreFields)) return true;
        return in_array($field, $this->fillable);
    }

    public function getId(){
        return $this->id;
    }

    public function getType(){
        return $this->item_type;
    }

    public function getTitle(){
        if($this->hasField('title')){
            return $this->title;
        }
        if($this->hasField('name')){
            return $this->name;
        }
        return '';
    }

    public function getDesc(){
        if($this->hasField('description')){
            return $this->description;
        }
        return '';
    }

    public function getHref()
    {
        return url(str_plural($this->itemType) . '/' . $this->id);
    }

    public function getImage(){
        if($this->hasField('image')){
            if(!empty($this->image)){
                return url($this->image);
            }
        }
        if($this->hasField('picture')){
            if(!empty($this->picture)){
                return url($this->picture);
            }
        }
        if($this->hasField('photo')){
            if(!empty($this->photo)){
                return url($this->photo);
            }
        }
        return null;
    }

    public function remove(){
        return $this->delete();
    }
}