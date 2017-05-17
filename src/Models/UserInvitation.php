<?php

namespace Acacha\Users\Models;

use Illuminate\Database\Eloquent\Model;

use Acacha\Stateful\Traits\StatefulTrait;
use Acacha\Stateful\Contracts\Stateful;

/**
 * Class UserInvitation.
 *
 * @package Acacha\Users\Models
 */
class UserInvitation extends Model implements Stateful
{
    use StatefulTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email','state','token'];

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


//    /**
//     * @return bool
//     */
//    protected function validateAccept()
//    {
//        if ($this->user_id != null && $this->user()) return true;
//        return false;
//    }
}

