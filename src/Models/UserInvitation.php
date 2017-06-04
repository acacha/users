<?php

namespace Acacha\Users\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

use Acacha\Stateful\Traits\StatefulTrait;
use Acacha\Stateful\Contracts\Stateful;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class UserInvitation.
 *
 * @package Acacha\Users\Models
 */
class UserInvitation extends Model implements Stateful
{
    use StatefulTrait, RevisionableTrait;

    public static function boot()
    {
        parent::boot();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email','state','token'];

    /**
     * Enable revisions on create.
     *
     * @var array
     */
    protected $revisionCreationsEnabled = true;

    /**
     * Transaction States
     *
     * @var array
     */
    protected $states = [
        'pending'  => ['initial' => true],
        'accepted' => ['final' => true]
    ];

    /**
     * Transaction State Transitions
     *
     * @var array
     */
    protected $transitions = [
        'accept' => [
            'from' => ['pending'],
            'to' => 'accepted'
        ]
    ];

    /**
     * Set the initial state.
     *
     * @return void
     */
    public function setInitialState()
    {
        $this->setAttribute($this->getStateColumn(), $this->getInitialState());
        $this->token = hash_hmac('sha256', str_random(40), env('APP_KEY'));
    }

    /**
     * Get the user record associated with the invitation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return bool
     */
    protected function validateAccept()
    {
        if ($this->user_id != null && $this->user()) return true;
        return false;
    }
}

