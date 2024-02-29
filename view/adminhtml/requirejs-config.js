/**
 * Copyright © Kellton, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            'Magento_PageBuilder/template/form/element/html-code.html':
                'Kellton_ChatGptAI/template/html-code.html'
        }
    },
    config: {
        mixins: {
            'Magento_PageBuilder/js/form/element/html-code': {
                'Kellton_ChatGptAI/js/html-code-mixin': true
            }
        }
    }
};
