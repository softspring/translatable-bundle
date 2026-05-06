import {access, cp, mkdir, rm} from 'node:fs/promises';
import {constants} from 'node:fs';

const assetsRoot = new URL('.', import.meta.url);
const dist = new URL('./dist/', assetsRoot);
const entries = ['scripts', 'styles'];

await rm(dist, {recursive: true, force: true});
await mkdir(dist, {recursive: true});

for (const entry of entries) {
  const source = new URL(`./${entry}/`, assetsRoot);
  try {
    await access(source, constants.F_OK);
    await cp(source, new URL(`./${entry}/`, dist), {recursive: true});
  } catch {
    // Optional asset folders are skipped.
  }
}
