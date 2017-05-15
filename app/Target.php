<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Target extends Model {

    /**
     * @author Sjors van Mierlo
     *
     * Defines the table the models refers to.
     */
    protected $table = 'targets';

    /**
     * @author Casper Schobers
     *
     * makes the game available in the Target object
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game() {
        return $this->belongsTo('App\Game');
    }
}
