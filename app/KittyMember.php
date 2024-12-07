<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\KittyGroup;
use App\Contact;
use App\KittyInstallment;

class KittyMember extends Model
{
    use HasFactory;
    protected $fillable = [
        'kitty_group_id',
        'customer_id',
        'name',
        'haswon',
    ];

    public function group()
    {
        return $this->belongsTo(KittyGroup::class, 'kitty_group_id');
    }

    public function customer()
    {
        return $this->belongsTo(Contact::class, 'customer_id');
    }

    public function installments()
    {
        return $this->hasMany(KittyInstallment::class);
    }
}
