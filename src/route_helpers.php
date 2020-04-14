<?php
    
    function get_route($route, $params = []){
        // TODO: move domain maps to Config.
        $domainMaps = [

        ];
        
        $routeParams = [];
        $globalRouteParams = [];
        $version = '';
        if(hasMobiClass()){
            $version = \App\Mobi::getVersion();
            $globalRouteParams = \App\Mobi::getGlobalRouteParams();
        }
        
        if(!empty($globalRouteParams)){
            $routeParams = array_merge($globalRouteParams, $params);
        }
        else{
            $routeParams = $params;
        }
        $host = request()->getHttpHost();
        if(isset($domainMaps[$host])){
            if(!empty($version)){
                $domainRoute = $domainMaps[$host] . '.' . $version . '.' . $route;
                if(\Route::has($domainRoute)){
                    return amp_html_url(route($domainRoute, $routeParams));
                }
                else{
                    $domainRoute = $domainMaps[$host]. '.' . $route;
                    if(\Route::has($domainRoute)){
                        return amp_html_url(route($domainRoute, $routeParams));
                    }
                }
            }
            else{
                $domainRoute = $domainMaps[$host]. '.' . $route;
            }
            if(\Route::has($domainRoute)){
                return amp_html_url(route($domainRoute, $routeParams));
            }
        }
        if(!empty($version)){
            if(\Route::has($version . '.' . $route)){
                return amp_html_url(route($version . '.' . $route, $routeParams));
            }
        }
        if(\Route::has($route)){
            return amp_html_url(route($route, $routeParams));
        }
        if(is_prod()){
            return get_route('root');
        }
    
        return amp_html_url(route($route, $routeParams));
    }
    
    function __route($route, $params = []){
        return get_route($route, $params);
    }
    
    function get_host(){
        $host = str_replace('www.', '', request()->getHost());
        return $host;
    }

    function is_amp(){
        if(hasMobiClass()){
            return \App\Mobi::isAMPSite();
        }
        return false;
    }
    
    function has_amp_version(){
        if(hasMobiClass()){
            return \App\Mobi::hasAMPVersion();
        }
        return false;
    }
    
    function has_html_version(){
        if(hasMobiClass()){
            return \App\Mobi::hasHtmlVersion();
        }
        return '';
    }
    
    function amp_html_url($url = null){
        if(hasMobiClass()){
            return \App\Mobi::getAMPHtmlVersionUrl($url);
        }
        return $url;
    }
    
    function amp_version_url(){
        if(hasMobiClass()){
            return \App\Mobi::getAMPVersionUrl();
        }
        return current_url();
    }
    
    function apm_html_route($route){
        return amp_html_url(get_route($route));
    }
    
    function short_route($route){
        return str_replace(url(''), '', route($route));
    }

    function current_url(){
        $url = request()->url();
        $schema = \URL::formatScheme(null);
        if(strpos($schema, 'https') !== false && (strpos($url, 'https') === false || strpos($url, 'https') !== 0)){
            $url = str_replace('http:', 'https:', $url);
        }
        return $url;
    }