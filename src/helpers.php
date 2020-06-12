<?php
function is_dev()
{
    return !\Config::get('app.live_mode');
}

function is_prod()
{
    return !is_dev();
}

function hasMobiClass()
{
    return class_exists("\\App\\Mobi");
}

function is_request_json()
{
    $contentType = request()->header('Content-Type');
    if (strpos($contentType, 'application/json') !== false) {
        return true;
    }

    $acceptHeader = request()->header('Accept');
    if (strpos($acceptHeader, 'application/json') !== false) {
        return true;
    }

    if (request()->query('format') == 'json') {
        return true;
    }

    return false;
}

function is_mobile_app()
{
    if (hasMobiClass()) {
        return \App\Mobi::isMobileApp();
    }
    return false;
}

function mobile_app_name()
{
    // identity connect from which app
    $appClientId = request()->header('app-client-id');
    if (!empty($appClientId)) {
        return $appClientId;
    }

    $params = request()->all();
    if (isset($params['app_client_id']) && !empty($params['app_client_id'])) {
        return $params['app_client_id'];
    }
    return '';
}

if (!function_exists('value_of')) {
    function value_of($obj, $path = '')
    {
        if (empty($path)) {
            return $obj;
        }

        $result = $obj;
        $fields = explode('.', $path);
        $bNotFound = false;
        foreach ($fields as $field) {
            if (is_array($result)) {
                if (isset($result, $field)) {
                    $result = $result[$field];
                } else {
                    $bNotFound = true;
                    break;
                }
            } else {
                if (isset($result, $field)) {
                    $result = $result->{$field};
                } else {
                    $bNotFound = true;
                    break;
                }
            }
        }
        if ($bNotFound) {
            return null;
        }

        return $result;
    }
}
