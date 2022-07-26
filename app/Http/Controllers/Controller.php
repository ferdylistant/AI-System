<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $__request;

    protected $__title;

    protected $__breadcrumb;

    protected function __breadcrumb($path, $type = 1, $link = []) {
        // $path = preg_replace('/^\/|\/$|/', '', $path);
        // $path = explode('/', $path);
        // $path = array_map(function($arr) {
        //     return ucwords(preg_replace('/[^a-z0-9]/i', ' ', $arr));
        // },$path);
        // $result = '';

        // if(count($path) !== $type) {
        //     throw new \Exception('Parameter $path and $type no match.');
        // }
        // $result = '<div class="section-header-breadcrumb">';
        // for($i=1; $i<=count($path); $i++) {
        //     $result .= 
        // }
        // if(is_null($type)) {
        //     $result .= '<div class="section-header-breadcrumb">
        //     <div class="breadcrumb-item "><a href="#">'.$path[0].'</a></div>
        //     <div class="breadcrumb-item">'.$path[1].'</div>
        //     </div>';
        // } elseif ($type == 'View') {
        //     $result .= '<div class="section-header-breadcrumb">
        //         <div class="breadcrumb-item "><a href="#">'.$path[0].'</a></div>
        //         <div class="breadcrumb-item"><a href="'.url($path[0].'/'.$path[1]).'">'.$path[1].'</a></div>
        //         <div class="breadcrumb-item">'.$path[2].'</div>
        //         </div>';
        // } elseif ($type == 'Create') {
            
        // } elseif ($type == 'Update') {
            
        // }
        // return $result;
        // var_dump($path);
    }
}
