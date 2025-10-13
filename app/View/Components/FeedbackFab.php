<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Setting;

class FeedbackFab extends Component
{
    public $url;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Ambil value dari DB (menggunakan helper Setting::getValue)
        $this->url = Setting::getValue(Setting::WEB_FEEDBACK_FORM_URL);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.feedback-fab');
    }
}
