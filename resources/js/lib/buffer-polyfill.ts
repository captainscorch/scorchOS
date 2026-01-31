/**
 * gray-matter (and js-yaml) use Node's Buffer in the browser. This module sets the
 * polyfill once, and must be imported before any code that uses gray-matter.
 */
import { Buffer } from 'buffer';

if (typeof (globalThis as typeof globalThis & { Buffer?: typeof Buffer }).Buffer === 'undefined') {
    (globalThis as typeof globalThis & { Buffer: typeof Buffer }).Buffer = Buffer;
}
