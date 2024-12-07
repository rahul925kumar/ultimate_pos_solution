<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\KittyMember;
use App\Contact;
class KittyInstallment extends Model
{
    use HasFactory;

    protected $table = "kitty_installments";

    protected $fillable = [
        'customer_id',
        'due_amount',
        'kitty_group_id',
        'paid_amount',
        'due_date',
        'status'
    ];

    public function kittyMember()
    {
        return $this->belongsTo(KittyMember::class, 'kitty_group_id');
    }

    // App\Models\KittyInstallment.php

    public function customer()
    {
        return $this->belongsTo(Contact::class, 'customer_id');
    }

}
