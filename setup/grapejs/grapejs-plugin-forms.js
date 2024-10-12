(function (global, factory) {
    // Check the environment and assign the factory output accordingly
    if (typeof exports === 'object' && typeof module === 'object') {
        module.exports = factory();
    } else if (typeof define === 'function' && define.amd) {
        define([], factory);
    } else if (typeof exports === 'object') {
        exports['grapejs-plugin-forms'] = factory();
    } else {
        global['grapejs-plugin-forms'] = factory();
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
    utils.defineProperty(module, { default: () => formPlugin });

    // Define commonly used variables
    var tagNames = {
        form: 'form',
        input: 'input',
        textarea: 'textarea',
        select: 'select',
        checkbox: 'checkbox',
        radio: 'radio',
        button: 'button',
        label: 'label',
        option: 'option'
    };

    // Utility functions for object assignment
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
    function formPlugin(editor, options) {
        options = assign({
            blocks: ['form', 'input', 'textarea', 'select', 'button', 'label', 'checkbox', 'radio'],
            category: { id: 'forms', label: 'Forms' },
            block: function () { return {}; }
        }, options);

        (function (editor) {
            var components = editor.Components;

            var traits = {
                nameTrait: { name: 'name' },
                placeholderTrait: { name: 'placeholder' },
                valueTrait: { name: 'value' },
                requiredTrait: { type: 'checkbox', name: 'required' }
            };

            // Block addition function
            function addBlock(type, content) {
                var block = options.blocks.includes(type) ? options.block(type) : {};
                editor.BlockManager.add(type, assign({
                    category: options.category,
                    select: true
                }, content, block));
            }

            // Add form-related components
            components.addType(tagNames.form, {
                isComponent: el => el.tagName === 'FORM',
                model: {
                    defaults: {
                        tagName: 'form',
                        droppable: ':not(form)',
                        draggable: ':not(form)',
                        attributes: { method: 'get' },
                        traits: [
                            { type: 'select', name: 'method', options: [{ value: 'get', name: 'GET' }, { value: 'post', name: 'POST' }] },
                            { name: 'action' }
                        ]
                    }
                },
                view: {
                    events: { submit: e => e.preventDefault() }
                }
            });

            // Add input field
            components.addType(tagNames.input, {
                isComponent: el => el.tagName === 'INPUT',
                model: {
                    defaults: {
                        tagName: 'input',
                        droppable: false,
                        highlightable: false,
                        attributes: { type: 'text' },
                        traits: [traits.nameTrait, traits.placeholderTrait, traits.valueTrait, traits.requiredTrait]
                    }
                }
            });

            // More component definitions (textarea, select, checkbox, etc.)...
            // Similar structure for other components
        })(editor);

        // Trait manager setup
        (function (editor) {
            editor.TraitManager.addType('select-options', {
                events: { keyup: 'onChange' },
                onValueChange: function () {
                    var trait = this.model;
                    var target = this.target;
                    var options = trait.get('value').trim().split('\n').map(line => {
                        var parts = line.split('::');
                        return { type: tagNames.option, components: parts[1] || parts[0], attributes: { value: parts[0] } };
                    });
                    target.components().reset(options);
                    target.view.render();
                },
                getInputEl: function () {
                    if (!this.$input) {
                        this.$input = document.createElement('textarea');
                        var components = this.target.components();
                        var values = components.map(component => {
                            var value = component.get('attributes').value || '';
                            var content = component.components().models[0]?.get('content') || '';
                            return `${value}::${content}`;
                        });
                        this.$input.value = values.join("\n");
                    }
                    return this.$input;
                }
            });
        })(editor);
    }

    return module.default;
});
