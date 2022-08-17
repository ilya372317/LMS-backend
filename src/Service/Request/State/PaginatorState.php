<?php

namespace App\Service\Request\State;

interface PaginatorState
{
    public function getPagination($target): iterable;
}