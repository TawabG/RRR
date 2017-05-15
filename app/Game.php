<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model {

    /**
     * @author Tawab Ghorbandi
     *
     * Defines the table the models refers to.
     */
    protected $table = 'games';

    /**
     * @author Tawab Ghorbandi
     *
     * makes the sessions available in the Game object
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessions() {
        return $this->hasMany('App\Session');
    }

    /**
     * @author Tawab Ghorbandi
     *
     * makes the targets available in the Game object
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function targets() {
        return $this->hasMany('App\Target');
    }
}
