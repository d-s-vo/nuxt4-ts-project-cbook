import eslint from '@eslint/js';
import tseslint from 'typescript-eslint';
import pluginVue from 'eslint-plugin-vue';
import vueParser from 'vue-eslint-parser';

export default tseslint.config(
    {
        ignores: [
            'vendor',
            'node_modules',
            'public/build',
            'resources/js/types/generated.d.ts',
        ],
    },
    eslint.configs.recommended,
    ...tseslint.configs.recommended,
    ...pluginVue.configs['flat/recommended'],
    {
        files: ['**/*.vue'],
        languageOptions: {
            parser: vueParser,
            parserOptions: {
                parser: tseslint.parser,
                extraFileExtensions: ['.vue'],
            },
        },
    },
    {
        languageOptions: {
            globals: {
                // Ziggy exposes route() as a global helper (see resources/js/types/global.d.ts).
                route: 'readonly',
            },
        },
        rules: {
            // Inertia pages and shared UI primitives are single-word by convention.
            'vue/multi-word-component-names': 'off',
            // Indentation and template line-wrapping follow .editorconfig (4 spaces),
            // so leave those purely cosmetic rules to the formatter, not ESLint.
            'vue/html-indent': 'off',
            'vue/max-attributes-per-line': 'off',
            'vue/html-closing-bracket-newline': 'off',
            'vue/html-self-closing': 'off',
            'vue/singleline-html-element-content-newline': 'off',
            'vue/multiline-html-element-content-newline': 'off',
        },
    },
);
