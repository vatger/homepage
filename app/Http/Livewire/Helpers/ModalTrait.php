<?php

namespace App\Http\Livewire\Helpers;

trait ModalTrait
{
    private array $modal_ids = [];

    protected function setModalIds(array $dom_ids): void
    {
        // must be unique dom id of modal activated by jQuery
        $this->modal_ids = array_unique($dom_ids);
    }

    public function openModal($dom_id): void
    {
        if (!in_array($dom_id, $this->modal_ids, true)) {
            abort(400, "[ModalTrait] No modal by id: $dom_id");
        }
        $this->dispatchBrowserEvent('livewire_showModal', ['dom_id' => $dom_id]);
    }

    public function closeModal($dom_id): void
    {
        if (!in_array($dom_id, $this->modal_ids, true)) {
            abort(400, "[ModalTrait] No modal by id: $dom_id");
        }
        $this->dispatchBrowserEvent('livewire_hideModal', ['dom_id' => $dom_id]);
    }
}
