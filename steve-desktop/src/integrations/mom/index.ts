/**
 * mom-island — read-only question browser + authoring/upload flow for the
 * MyOpenMath question bank. Stub in phase 0; methods added in phase 2/3.
 */
import { defineIsland } from '../_shared/island';

export interface MomMethods {
  // Intentionally empty for phase 0. Phases 2 and 3 will add:
  // - browse()                  -> { families: MOMFamily[] }
  // - getQuestion(family, slug) -> { path, contents }
  // - getFamily(family)         -> { name, count, manifest }
  // - createDraft(family)       -> { draftPath, sourceTemplate }
  // - upload(draftPath)         -> { ok, error? }   // CDP-driven, paste-only
}

export const momIsland = defineIsland<MomMethods>({
  id: 'mom',
  label: 'MOM',
  methods: {} as MomMethods,
});
