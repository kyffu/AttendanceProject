<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ViewExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $view;
    protected $data;

    public function __construct($view, $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function view(): View
    {
        return view($this->view, $this->data);
    }
}
