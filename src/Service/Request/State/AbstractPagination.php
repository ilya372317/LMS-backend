<?php

namespace App\Service\Request\State;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractPagination implements PaginatorState
{
    protected PaginatorInterface $paginator;

    protected Request $request;

    public function __construct(PaginatorInterface $paginator, Request $request)
    {
        $this->paginator = $paginator;
        $this->request = $request;
    }

    abstract public function getPagination($target): iterable;
}