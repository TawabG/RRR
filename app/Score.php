<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Score extends Model {

    /**
     * @author Sjors van Mierlo
     *
     * Defines the table the models refers to.
     */
    protected $table = 'scores';

    /**
     * @author Casper Schobers
     *
     * makes the session available in the Score object
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session() {
        return $this->belongsTo('App\Session');
    }

    /**
     * @author Casper Schobers
     *
     * makes the player available in the Score object
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player(){
        return $this->belongsTo('App\Player');
    }
}
