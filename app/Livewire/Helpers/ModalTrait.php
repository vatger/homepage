<?php

namespace App\Livewire\Helpers;

trait ModalTrait
{
    private array $modal_ids = [];

    protected function setModalIds(array $dom_ids): void
    {
        // must be unique dom id of modal activated by jQuery
        $this->modal_ids = array_unique($dom_ids);
    }

    public function openModal(string $dom_id): void
    {
        if (!in_array($dom_id, $this->modal_ids, true)) {
            abort(400, "[ModalTrait] No modal by id: $dom_id");
        }
        $this->openModalNoCheck($dom_id);
    }

    protected function openModalNoCheck(string $dom_id): void
    {
        $this->dispatch('livewire_hideModal', ['dom_id' => $dom_id]);
    }

    public function closeModal(string $dom_id): void
    {
        if (!in_array($dom_id, $this->modal_ids, true)) {
            abort(400, "[ModalTrait] No modal by id: $dom_id");
        }
        $this->closeModalNoCheck($dom_id);
    }

    protected function closeModalNoCheck(string $dom_id): void
    {
        $this->dispatch('livewire_hideModal', ['dom_id' => $dom_id]);
    }
}
