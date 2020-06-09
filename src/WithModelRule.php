<?php
namespace bachphuc\PhpLaravelHelpers;

trait WithModelRule {
    public function canDeleteBy(\App\User $user = null)
    {
        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if (!isset($this->user_id)) {
            return false;
        }

        if($this->hasField('user_id')){
            return $user->id == $this->user_id ? true : false;
        }
        else if($this->hasField('owner_id')){
            return $user->id == $this->owner_id ? true : false;
        }
        return false;
    }  

    public function canDelete()
    {
        $user = auth()->user();
        return $this->canDeleteBy($user);
    }

    public function canEditBy(\App\User $user = null)
    {
        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if (!isset($this->user_id)) {
            return false;
        }

        // if this is User
        if($this instanceof \App\User){
            return $this->id == $user->id ? true : false;
        }

        return $user->id == $this->user_id ? true : false;
    }

    public function canEdit()
    {
        $user = auth()->user();
        return $this->canEditBy($user);
    }
}