import { showNoty } from './noty';
import jquery from 'jquery';
import { Modal } from 'bootstrap';

//for Livewire3
import { Livewire, Alpine, Component } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard';

export function findLivewireComponent(name: string): Component {
    return Livewire.all().find((value: any) => value['name'] == name);
}

export function loadLivewireExtensions() {
    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        // Runs immediately before a commit's payload is sent to the server...

        respond(() => {
            // Runs after a response is received but before it's processed...
        });

        succeed(({ snapshot, effect }) => {
            // Runs after a successful response is received and processed
            // with a new snapshot and list of effects...
            console.log(123);
            window.dispatchEvent(new Event('featherReplace'));
            console.log(456);
        });

        fail(() => {
            // Runs if some part of the request failed...
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
