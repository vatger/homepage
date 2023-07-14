import { showNoty } from './noty';
import * as $ from 'jquery';

export function loadLivewireExtensions() {
    window.addEventListener('livewire_showNoty', (event) => {
        showNoty(event['detail'].message, event['detail'].type, event['detail'].timeout);
    });

    window.addEventListener('livewire_showModal', (event) => {
        $('#' + event['detail'].dom_id).modal('show');
    });

    window.addEventListener('livewire_hideModal', (event) => {
        $('#' + event['detail'].dom_id).modal('hide');
    });
}
