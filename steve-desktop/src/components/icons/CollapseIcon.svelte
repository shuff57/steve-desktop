<script lang="ts">
  let { collapsed = false }: { collapsed?: boolean } = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left" style:transform={collapsed ? 'rotate(180deg)' : 'none'}><polyline points="15 18 9 12 15 6"></polyline></svg>

<style>
  svg {
    transition: transform 0.2s ease;
  }
</style>
