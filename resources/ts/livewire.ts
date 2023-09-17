import { showNoty } from './noty';
import jquery from 'jquery';
import { Modal } from 'bootstrap';

//for Livewire3
import { Livewire, Alpine, Component } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard';
import feather, { replace as featherReplace } from 'feather-icons';

export function findLivewireComponent(name: string): Component {
    return Livewire.all().find((value: any) => value['name'] == name);
}

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

    Livewire.on('livewire_showNoty', ({ message, type, timeout }) => {
        showNoty(message, type, timeout);
    });

    Livewire.on('livewire_showModal', ({ event }) => {
        let el = document.getElementById(event['detail'].dom_id);
        if (el) {
            let modal = Modal.getOrCreateInstance(el);
            modal.show();
        }
    });

    Livewire.on('livewire_hideModal', ({ event }) => {
        let el = document.getElementById(event['detail'].dom_id);
        if (el) {
            let modal = Modal.getOrCreateInstance(el);
            modal.hide();
        }
    });
}
