/**
 * ogre-island — adopts the O.G.R.E grading-server (providers, batch loop,
 * SSE streaming) and SQLite schema (site-profiles, rubrics, history). Stub
 * in phase 0; methods added in phases 4–6.
 */
import { defineIsland } from '../_shared/island';

export interface OgreMethods {
  // Intentionally empty for phase 0. Phases 4, 5, and 6 will add:
  // - openOgreDb()                  -> Database
  // - loadStudents(profile)         -> { name, responseText }[]
  // - gradeBatch(input)             -> AsyncIterable<GradingEvent>
  // - listRubrics() / getRubric(id) -> Rubric
  // - listSiteProfiles() / getSiteProfile(id)
}

export const ogreIsland = defineIsland<OgreMethods>({
  id: 'ogre',
  label: 'OGRE',
  methods: {} as OgreMethods,
});
