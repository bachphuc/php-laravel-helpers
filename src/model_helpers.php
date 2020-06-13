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
