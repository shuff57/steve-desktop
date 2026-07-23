/**
 * Island contract — the boundary every integration under src/integrations/
 * implements. Islands never import from outside their own folder; the main
 * app talks to them only through this typed surface.
 */

export interface Island<M> {
  id: string;
  label: string;
  enabled: boolean;
  methods: M;
}

export interface IslandSpec<M> {
  id: string;
  label: string;
  enabled?: boolean;
  methods: M;
}

export function defineIsland<M>(spec: IslandSpec<M>): Island<M> {
  return {
    id: spec.id,
    label: spec.label,
    enabled: spec.enabled ?? true,
    methods: spec.methods,
  };
}
