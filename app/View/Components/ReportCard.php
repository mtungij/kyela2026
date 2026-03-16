<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ReportCard extends Component
{
    public $title;
    public $value;
    public $icon;
    public $iconBg;
    public $textColor;
    public $borderColor;
    public $extra;

    public function __construct($title, $value, $icon = null, $iconBg = 'bg-gray-100', $textColor = 'text-gray-900', $borderColor = 'border-gray-300', $extra = null)
    {
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
        $this->iconBg = $iconBg;
        $this->textColor = $textColor;
        $this->borderColor = $borderColor;
        $this->extra = $extra;
    }

    public function render()
    {
        return view('components.report-card');
    }
}