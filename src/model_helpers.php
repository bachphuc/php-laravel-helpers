<?php
function model_class($str)
{
    if (empty($str)) {
        return null;
    }

    if ($str instanceof \Illuminate\Database\Eloquent\Model) {
        return $str;
    }

    if (class_exists($str)) {
        return $str;
    }

    $strClass = "\\App\\Models\\" . ucfirst($str);
    if (class_exists($strClass)) {
        return $strClass;
    }

    $strClass = "\\App\\" . ucfirst($str);
    if (class_exists($strClass)) {
        return $strClass;
    }

    if(strpos($str, '_') !== false){
        $upStr = '';
        if(function_exists('camel_case')){
            $upStr = ucfirst(camel_case($str));
        }
        else if(class_exists('\Illuminate\Support\Str') && method_exists('\Illuminate\Support\Str', 'camel')){
            $upStr = ucfirst(\Illuminate\Support\Str::camel($str));
        }

        if(!empty($upStr)){
            if($upStr instanceof \Illuminate\Database\Eloquent\Model) {
                return $upStr;
            }
            
            if(class_exists($upStr)) {
                return $upStr;
            }
    
            $strClass = "\\App\\Models\\" . ucfirst($upStr);
            if(class_exists($strClass)) {
                return $strClass;
            }
    
            $strClass = "\\App\\" . ucfirst($upStr);
            if(class_exists($strClass)) {
                return $strClass;
            }
        }
    }

    // try to resolve class
    $obj = null;
    try{
        $obj = resolve($str);
        if($obj){
            return get_class($obj);
        }
    }
    catch(\Exception $e){
        
    }

    return null;
}

if (!function_exists('model_item')) {
    function model_item($modelClass, $id)
    {
        $class = model_class($modelClass);
        if (!$class) {
            return null;
        }

        $item = $class::find($id);
        return $item;
    }
}


function __title($item, $limit = 0){
    if(!$item) return '';
    if($limit){
        return str_limit_words($item->getTitle(), $limit);
    }
    $str = $item->getTitle();
    $str = str_replace('"', '', $str);
    return $str;
}

function __desc($item, $limit = 0){
    if($limit){
        return str_limit_words($item->getDescription(), $limit);
    }
    return $item->getDescription();
}

function __img($item, $size = null){
    $img = '';
    if(empty($size)) {
        $img = $item->getImage();
    }
    else{
        $img = $item->getThumbnailImage($size);
    }
    $img = str_replace(url(''), '', $img);
    return $img;
}

function __href($item){
    return str_replace(url(''), '', $item->getHref());
}

function __edit_href($item){
    if(method_exists($item, 'getEditHref')) {
        return str_replace(url(''), '', $item->getEditHref());
    }
    return '/';
}

function __id($item){
    return $item->getId();
}

function __duration($item){
    return $item->formatDuration();
}

function __g($item = null, $field = ''){
    if(empty($item) || empty($field)) return '';
    $field = trim($field, "'");
    if(strpos($field, '.') === false){
        if(method_exists($item, $field)){
            return $item->{$field}();
        }
        $camelField = camel_case($field);
        if(method_exists($item, $camelField)){
            return $item->{$camelField}();
        }
        return $item->{$field};
    }
    $tmp = $item;
    
    $parts = explode('.', $field);
    if(empty($parts)) return '';
    foreach($parts as $part){
        if(is_object($tmp)){
            $tmp = $tmp->{$part};
        }
        else if(is_array($tmp)){
            $tmp = $tmp[$part];
        }
    }

    return $tmp;
}

function __owner($item){
    return $item->getOwner();
}

function __owner_id($item){
    $user = $item->getOwner();
    if(!$user) return 0;
    return $user->getId();
}

function __owner_name($item){
    $user = $item->getOwner();
    if(!$user) return '';
    return $user->getTitle();
}

function __owner_href($item){
    $user = $item->getOwner();
    if(!$user) return '';
    return str_replace(url(''), '', $user->getHref());
}

function __channel_href($item){
    $user = $item->getOwner();
    if(!$user) return '';
    return str_replace(url(''), '', $user->getVideoChanelHref());
}

function __created_at($item){
    return $item->formatCreatedTime();
}