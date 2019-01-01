<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter
{
    /**
     * @return string
     */
    public function fullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
