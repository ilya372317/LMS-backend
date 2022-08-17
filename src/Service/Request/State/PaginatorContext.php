<?php

namespace App\Service\Request\State;

use App\Constants\Request\RequestParameter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginatorContext
{
    private Request $request;

    private PaginatorInterface $paginator;

    private PaginatorState $paginatorState;

    public function __construct(Request $request, PaginatorInterface $paginator)
    {
        $this->request = $request;
        $this->paginator = $paginator;
        $this->paginatorState = $this->getState();
    }

    private function getState(): PaginatorState
    {
        $itemPerPage = $this->request->get(RequestParameter::ITEM_PER_PAGE);
        $pageNumber = $this->request->get(RequestParameter::PAGE_NUMBER);

        $requestHasItemPerPageParameter = isset($itemPerPage);

        $requestHasPageNumberParameter = isset($pageNumber);

        if ($requestHasItemPerPageParameter) {
            return new PaginationWithItemsPerPage($this->paginator, $this->request);
        } else {
            if ($requestHasPageNumberParameter) {
                return new PaginationWithPageNum($this->paginator, $this->request);
            } else {
                return new PaginationWithoutParameters($this->paginator, $this->request);
            }
        }
    }

    public function getPagination($target): iterable
    {
        return $this->paginatorState->getPagination($target);
    }
}