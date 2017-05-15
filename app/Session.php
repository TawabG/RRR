<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model {

    /**
     * @author Sjors van Mierlo
     *
     * Defines the table the models refers to.
     */
    protected $table = 'sessions';

    /**
     * @author Casper Schobers
     *
     * makes the game available in the Session object
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game() {
        return $this->belongsTo('App\Game');
    }

    /**
     * @author Casper Schobers
     *
     * makes the players available in the Session object
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players() {
        return $this->belongsToMany('App\Player', 'sessions_players');
    }

    /**
     * @author Casper Schobers
     *
     * makes the score available in the Session object
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scores() {
        return $this->hasMany('App\Score');
    }
    
}
