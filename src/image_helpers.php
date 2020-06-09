<?php
    function photo_upload_path(){
        return \App\Models\ModelBase::STORAGE_PATH;
    }

    function photo_storage($file, $maskPath = '', $extension = ''){
        if(empty($extension)){
            // get original extension
            $extension = $file->getClientOriginalExtension();
            if(empty($extension)){
                $extension = 'jpg';
            }
        }

        $path = \Storage::putFileAs(photo_upload_path(), $file , str_random(8) . '.' .  $extension);

        photo_fix_orientation($path);

        if(!empty($maskPath)){
            photo_mask($path, $maskPath);
        }

        return $path;
    }

    function photo_fix_orientation($path = '', $position = 'bottom-right'){
        if(empty($path)) return;
        // fix wrong orientation
        $image = \Image::make(public_path($path));
        // perform orientation using intervention
        $image->orientate();
        $image->save(public_path($path), 80);
    }

    function photo_mask($path, $maskPath = ''){
        if(empty($path) || empty($maskPath)){
            return false;
        }
        if(!file_exists(public_path($path))){
            return false;
        }
        if(!file_exists(public_path($maskPath))){
            return false;   
        }

        $img = \Image::make(public_path($path));
        $img->insert(public_path($maskPath), $position , 16, 16);
        $img->save(public_path($path), 95);
    }

    function photo_copy_and_crop($path, $width, $height = 0, $quality = 0){
        $output = photo_upload_path() . '/' . str_random(8) . '.jpg';
        photo_crop($path, $output, $width, $height, $quality);
        return $output;
    }

    function photo_crop($path, $output, $width, $height = 0, $quality = 0) {
        if(!$height){
            $height = $width;
        }

        if (!$quality) {
            $quality = 80;
        }

        // resize the image to a width of 300 and constrain aspect ratio (auto height)
        $img = \Image::make(public_path($path));
        $img->fit($width, $height);
        $img->save(public_path($output), $quality);
    }

    function photo_resize($path, $maxWidth = 0, $maxHeight = 0, $quality = 0){
        if (!$maxWidth) {
            $maxWidth = 720;
        }

        if (!$maxHeight) {
            $maxHeight = 720;
        }

        if (!$quality) {
            $quality = 80;
        }

        // resize the image to a width of 300 and constrain aspect ratio (auto height)
        $img = \Image::make(public_path($path));
        $img->resize($maxWidth, $maxHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->save(public_path($path), $quality);
    }