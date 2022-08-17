<?php

namespace App\Service\Request\State;

use App\Constants\Request\RequestParameter;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PaginationWithItemsPerPage extends AbstractPagination
{
    public function getPagination($target): PaginationInterface
    {
        $pageNumber = $this->request->get(RequestParameter::PAGE_NUMBER);
        $pageNumberParameterNotIsset = $pageNumber === null;

        if ($pageNumberParameterNotIsset) {
            $pageNumber = 1;
        }

        $itemPerPage = $this->request->get(RequestParameter::ITEM_PER_PAGE);

        return $this->paginator->paginate($target, $pageNumber, $itemPerPage);
    }
}