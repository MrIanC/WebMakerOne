(function (global, factory) {
    // Check the environment and assign the factory output accordingly
    if (typeof exports === 'object' && typeof module === 'object') {
        module.exports = factory();
    } else if (typeof define === 'function' && define.amd) {
        define([], factory);
    } else if (typeof exports === 'object') {
        exports['grapejs-plugin-iframe'] = factory();
    } else {
        global['grapejs-plugin-iframe'] = factory();
    }
})(typeof globalThis !== 'undefined' ? globalThis : typeof window !== 'undefined' ? window : this, function () {
    "use strict";

    var utils = {
        defineProperty: (target, properties) => {
            for (var prop in properties) {
                if (Object.prototype.hasOwnProperty.call(properties, prop) && !Object.prototype.hasOwnProperty.call(target, prop)) {
                    Object.defineProperty(target, prop, { enumerable: true, get: properties[prop] });
                }
            }
        },
        hasOwnProperty: (obj, prop) => Object.prototype.hasOwnProperty.call(obj, prop),
        markAsModule: (module) => {
            if (typeof Symbol !== 'undefined' && Symbol.toStringTag) {
                Object.defineProperty(module, Symbol.toStringTag, { value: 'Module' });
            }
            Object.defineProperty(module, '__esModule', { value: true });
        }
    };

    // Initialize a module
    var module = {};
    utils.markAsModule(module);
    utils.defineProperty(module, { default: () => iframePlugin });

    // Utility function to assign properties
    var assign = Object.assign || function (target) {
        for (var i = 1; i < arguments.length; i++) {
            var source = arguments[i];
            for (var key in source) {
                if (Object.prototype.hasOwnProperty.call(source, key)) {
                    target[key] = source[key];
                }
            }
        }
        return target;
    };

    // Main plugin function
    function iframePlugin(editor, options) {
        options = assign({
            blocks: ['iframe'],
            category: { id: 'media', label: 'Media' },
            block: function () { return {}; }
        }, options);

        (function (editor) {
            var components = editor.Components;

            var traits = {
                frameborderTrait: { name: 'frameborder', type: 'text' },
                allowTrait: { name: 'allow', type: 'text' },
                referrerPolicyTrait: { name: 'referrerpolicy', type: 'select', options: [
                    { value: 'no-referrer', name: 'No Referrer' },
                    { value: 'no-referrer-when-downgrade', name: 'No Referrer When Downgrade' },
                    { value: 'origin', name: 'Origin' },
                    { value: 'origin-when-cross-origin', name: 'Origin When Cross-Origin' },
                    { value: 'same-origin', name: 'Same Origin' },
                    { value: 'strict-origin', name: 'Strict Origin' },
                    { value: 'strict-origin-when-cross-origin', name: 'Strict Origin When Cross-Origin' },
                    { value: 'unsafe-url', name: 'Unsafe URL' }
                ] }
            };

            // Add iframe component
            components.addType('iframe', {
                isComponent: el => el.tagName === 'IFRAME',
                model: {
                    defaults: {
                        tagName: 'iframe',
                        droppable: false,
                        draggable: true,
                        attributes: { src: '', frameborder: '0', allow: '', referrerpolicy: 'no-referrer' },
                        traits: [
                            { name: 'src', type: 'text', label: 'Source' },
                            traits.frameborderTrait,
                            traits.allowTrait,
                            traits.referrerPolicyTrait
                        ]
                    }
                },
                view: {}
            });
        })(editor);
    }

    return module.default;
});
