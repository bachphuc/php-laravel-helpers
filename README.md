# **helper for laravel**

## namespace: bachphuc\PhpLaravelHelpers

## Alias: **LaravelHelpers**

## Image Helpers

- function **photo_upload_path**()
- function **photo_storage**($file, $maskPath = '', $extension = '')
- function **photo_fix_orientation**($path = '', $position = 'bottom-right')
- function **photo_mask**($path, $maskPath = '')
- function **photo_copy_and_crop**($path, $width, $height = 0, $quality = 0)
- function **photo_crop**($path, $output, $width, $height = 0, $quality = 0)
- function **photo_resize**($path, $maxWidth = 0, $maxHeight = 0, $quality = 0)

## Trail Models

### WithModelBase: Function
- hasField
- getId
- getType
- getTitle
- getDesc
- getHref
- getImage
- remove
- increase
- display
- displayDetail
- onCreated
- getListItems
- processGetItemListQuery

### WithImage
### WithModelRule

## Middleware
- auth.admin