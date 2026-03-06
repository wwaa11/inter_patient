<?php

namespace App\Traits;

trait HasRandomColor
{
    /**
     * Generate a random hex color.
     */
    public function randomHexColor(): string
    {
        return '#'.substr(str_shuffle('0123456789abcdef'), 0, 6);
    }
}
