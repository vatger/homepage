<?php

namespace App\Livewire\Helpers;

trait NotyTrait
{
    public function showNoty(string $message, string $type = 'success', int $timeout = 5000): void
    {
        //$type = 'alert' | 'success' | 'warning' | 'error' | 'info' | 'information'
        $this->dispatch('livewire_showNoty', ['message' => $message, 'type' => $type, 'timeout' => $timeout]);
    }
}
