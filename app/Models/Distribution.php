<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;

class Distribution extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function menu() {
        return $this->belongsTo(Menu::class);
    }
}
