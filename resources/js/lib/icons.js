import { library } from '@fortawesome/fontawesome-svg-core';

import {
    faAngleLeft,
    faArrowAltCircleLeft,
    faArrowDown, faArrowLeft, faArrowUp,
    faBell,
    faCaretDown,
    faCheck,
    faCog,
    faCogs,
    faEnvelope,
    faEnvelopeOpen,
    faEye,
    faEyeSlash,
    faInbox,
    faGripHorizontal, faGripVertical,
    faListAlt,
    faPencilAlt,
    faPlus,
    faPlusCircle,
    faSlidersH,
    faSpinner,
    faSyncAlt,
    faTimes,
    faTrashAlt,
    faUpload
} from '@fortawesome/free-solid-svg-icons';

import {
    faCopy,
    faCreditCard
} from '@fortawesome/free-regular-svg-icons';

import {
    faCcAmex,
    faCcDinersClub,
    faCcDiscover,
    faCcJcb,
    faCcMastercard,
    faCcVisa
} from '@fortawesome/free-brands-svg-icons';

export default {
    register() {
        library.add(
            faAngleLeft,
            faArrowAltCircleLeft,
            faArrowDown, faArrowLeft, faArrowUp,
            faBell,
            faCaretDown,
            faCcAmex,
            faCcDinersClub,
            faCcDiscover,
            faCcJcb,
            faCcMastercard,
            faCcVisa,
            faCheck,
            faCog,
            faCogs,
            faCopy,
            faCreditCard,
            faEnvelope,
            faEnvelopeOpen,
            faEye,
            faEyeSlash,
            faInbox,
            faGripHorizontal, faGripVertical,
            faListAlt,
            faPencilAlt,
            faPlus,
            faPlusCircle,
            faSlidersH,
            faSpinner,
            faSyncAlt,
            faTimes,
            faTrashAlt,
            faUpload
        );
    }
}
