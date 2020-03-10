<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityResult extends Model
{
    protected $table = 'community_result';
    public $timestamps=false;
    protected $primaryKey = 'id';
    /**
     * @var string
     */
}
