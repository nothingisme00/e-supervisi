import { defineConfig } from 'vitest/config';
import path from 'path';

export default defineConfig({
    test: {
        environment: 'jsdom',
        include: ['resources/js/**/*.test.js'],
        reporters: ['default', ['tdd-guard-vitest', { projectRoot: path.resolve(__dirname) }]],
    },
});
