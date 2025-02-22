<?php

namespace App\Livewire;

use Livewire\Component;

class Card extends Component
{
    public $title;
    public $content;
    public $image;

    public function mount($title, $content, $image)
    {
        $this->title = $title;
        $this->content = $content;
        $this->image = $image;
    }

    public function render()
    {
        return view('livewire.card');
    }
}
