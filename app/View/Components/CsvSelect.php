<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CsvSelect extends Component
{
    public $label;
    public $options;
    public $selected;
    public $attributes;
    public $name;

    public function __construct($label, $options, $selected = null, $name = '')
    {
        $this->label = $label;
        $this->options = $options;
        $this->selected = $selected;
        $this->name = $name;
    }

    public function render()
    {
        return view('components.csv-select');
    }
}