<?php

namespace App\Models\User;

use App\Models\Administration\Library;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMeta extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'user_meta';
    protected $guarded = [];

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }

    protected function casts()
    {
        return [
            'registration_date' => 'datetime:d-m-Y',
            'expiration_date' => 'datetime:d-m-Y',
            'birthdate' => 'datetime:d-m-Y'
        ];
    }
}
