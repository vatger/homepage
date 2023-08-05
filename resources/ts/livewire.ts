import { showNoty } from './noty';

//for Livewire3
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard';

export function loadLivewireExtensions() {
    Alpine.plugin(Clipboard);
    Livewire.start();
    let $ = window['jquery'];

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
