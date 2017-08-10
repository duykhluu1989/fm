<?php

namespace App\Libraries\Widgets;

class GridView
{
    const ROWS_PER_PAGE = 50;

    protected $dataProvider;

    protected $columns;

    protected $tools;

    protected $filters;

    protected $filterValues;

    protected $checkbox;

    protected $pagination;

    public function __construct($dataProvider, $columns)
    {
        $this->dataProvider = $dataProvider;
        $this->columns = $columns;

        $this->pagination = true;
    }

    public function render()
    {
        echo view('libraries.widgets.gridViews.grid', [
            'dataProvider' => $this->dataProvider,
            'columns' => $this->columns,
            'tools' => $this->tools,
            'filterValues' => $this->filterValues,
            'filters' => $this->filters,
            'checkbox' => $this->checkbox,
            'pagination' => $this->pagination,
        ]);
    }

    public function setTools($tools)
    {
        $this->tools = $tools;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function setFilterValues($filterValues)
    {
        $this->filterValues = $filterValues;
        $this->dataProvider->appends($this->filterValues);
    }

    public function setCheckbox()
    {
        $this->checkbox = true;
    }

    public function unsetPagination()
    {
        $this->pagination = false;
    }
}