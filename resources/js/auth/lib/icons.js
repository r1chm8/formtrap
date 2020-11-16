import { library } from '@fortawesome/fontawesome-svg-core';

import {
    faEye,
    faEyeSlash
} from '@fortawesome/free-solid-svg-icons';

export default {
    register() {
        library.add(
            faEye,
            faEyeSlash
        );
    }
}
