import { showNoty } from './noty';
import jquery from 'jquery';

//for Livewire3
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard';
import feather, { replace as featherReplace } from 'feather-icons';

export function loadLivewireExtensions() {
    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        // Equivelant of 'message.sent'

        succeed(({ snapshot, effect }) => {
            // Equivelant of 'message.received'

            queueMicrotask(() => {
                // Equivelant of 'message.processed'
                featherReplace();
            });
        });

        fail(() => {
            // Equivelant of 'message.failed'
        });
    });

    Alpine.plugin(Clipboard);
    Livewire.start();

    Livewire.on('livewire_showNoty', ({ event }) => {
        console.log(event); // todo test
    });

    window.addEventListener('livewire_showNoty', (event) => {
        showNoty(event['detail'].message, event['detail'].type, event['detail'].timeout);
    });

    window.addEventListener('livewire_showModal', (event) => {
        jquery('#' + event['detail'].dom_id).modal('show');
    });

    window.addEventListener('livewire_hideModal', (event) => {
        jquery('#' + event['detail'].dom_id).modal('hide');
    });
}
