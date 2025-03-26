<?php

namespace App\Actions;

interface Action
{
    /**
     * Execute the action.
     *
     * @return mixed
     */
    public function execute();
}
