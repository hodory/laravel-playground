<?php
/**
 * Created by PhpStorm.
 * User: khzero
 * Date: 2019-12-24
 * Time: 오후 11:15
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }
}
