<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

use DateTimeInterface;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function child_menus() {
        return $this->hasMany(Menu::class, 'parent_menu_id');
    }

    public function parent_menu() {
        return $this->belongsTo(Menu::class, 'parent_menu_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menu')->withPivot('permission');
    }

    public function parent() {
        return $this->belongsTo(Menu::class, 'parent_menu_id');
    }
    
    public function children() {
        return $this->hasMany(Menu::class, 'parent_menu_id');
    }
}
