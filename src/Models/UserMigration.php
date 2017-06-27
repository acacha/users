<?php

namespace Acacha\Users\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserMigration.
 *
 * @package Acacha\Users\Models
 */
class UserMigration extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['source_user_id','source_user','user_id'];

    /**
     * Get the user record associated with the invitation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the progress batch that this user migration belongs.
     */
    public function batch()
    {
        return $this->belongsTo(UserMigrationBatch::class);
    }

    /**
     * Relation to eager loading by default.
     *
     * @var array
     */
    public $with = ['user'];
}

