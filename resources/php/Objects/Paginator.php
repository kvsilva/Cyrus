<?php

namespace Objects;

class Paginator
{
    private int $page;
    private int $items;
    private int $totalItems;
    private int $totalPages;

    /**
     * @param int $page
     * @param int $items
     * @param int $totalItems
     * @param int $totalPages
     */
    public function __construct(int $page, int $items, int $totalItems, int $totalPages)
    {
        $this->page = $page;
        $this->items = $items;
        $this->totalItems = $totalItems;
        $this->totalPages = $totalPages;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return Paginator
     */
    public function setPage(int $page): Paginator
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getItems(): int
    {
        return $this->items;
    }

    /**
     * @param int $items
     * @return Paginator
     */
    public function setItems(int $items): Paginator
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * @param int $totalItems
     * @return Paginator
     */
    public function setTotalItems(int $totalItems): Paginator
    {
        $this->totalItems = $totalItems;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * @param int $totalPages
     * @return Paginator
     */
    public function setTotalPages(int $totalPages): Paginator
    {
        $this->totalPages = $totalPages;
        return $this;
    }




}