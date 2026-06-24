<script lang="ts">
  import { ChevronLeft, ChevronRight } from 'lucide-svelte';
  let { collapsed = false }: { collapsed?: boolean } = $props();
</script>

{#if collapsed}
  <ChevronRight />
{:else}
  <ChevronLeft />
{/if}
