<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model {

    /**
     * @author Sjors van Mierlo
     *
     * Defines the table the models refers to.
     */
    protected $table = 'players';

    /**
     * @author Sjors van Mierlo
     *
     * No timestamp columns needed
     */
    public $timestamps = false;

    /**
     * @author Casper Schobers
     *
     * makes the sessions available in the Player object
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sessions() {
        return $this->belongsToMany('App\Session', 'sessions_players');
    }

    /**
     * @author Casper Schobers
     *
     * makes the scores available in the Player object
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scores() {
        return $this->hasMany('App\Score');
    }


    /**
     * @author Maikel Hoeks
     *
     * makes the target available in the Player object
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function target() {
        return $this->belongsTo('App\Target');
    }
}
