// Icons from the lucide-svelte pack (the maintained successor to Feather).
// Re-exported under the app's existing names so call sites stay unchanged.
export {
  LayoutDashboard as DashboardIcon,
  Globe as BrowserIcon,
  Brain as SkillsIcon,
  Settings as SettingsIcon,
} from 'lucide-svelte';

// CollapseIcon flips direction based on its `collapsed` prop — needs a wrapper.
export { default as CollapseIcon } from './CollapseIcon.svelte';
