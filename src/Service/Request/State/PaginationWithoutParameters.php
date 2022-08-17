<?php

namespace App\Service\Request\State;

class PaginationWithoutParameters extends AbstractPagination
{

    public function getPagination($target): iterable
    {
        return $target;
    }

}