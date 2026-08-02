import { defineConfig } from 'vite'
import { svelte } from '@sveltejs/vite-plugin-svelte'

export default defineConfig({
  plugins: [svelte()],
  server: {
    port: 5174,
    strictPort: true,
    watch: {
      usePolling: true,
      interval: 300,
      // mom-content is CONTENT, not source, and the agent writes into it while a run is in flight.
      // Watching it means a question file landing triggers a full reload, which remounts the very
      // component that spawned the run — the run finishes in Rust and its result lands in a
      // destroyed instance, so the log just empties mid-write. Observed doing exactly that.
      ignored: ['**/mom-content/**'],
    },
  },
})
