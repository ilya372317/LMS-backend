<?php

namespace App\Service\Request\State;

use App\Constants\Request\RequestParameter;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PaginationWithPageNum extends AbstractPagination
{
    public function getPagination($target): PaginationInterface
    {
        $pageNumber = $this->request->get(RequestParameter::PAGE_NUMBER);
        return $this->paginator->paginate($target, $pageNumber);
    }
}