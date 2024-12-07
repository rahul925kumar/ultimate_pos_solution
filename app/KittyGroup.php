<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\KittyMember;

class KittyGroup extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'total_amount', 'start_month'];

    public function members()
    {
        return $this->hasMany(KittyMember::class, 'kitty_group_id');
    }

}
 


