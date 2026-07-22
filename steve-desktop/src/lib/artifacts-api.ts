import { invoke } from '@tauri-apps/api/core';

// Agent captures (screenshots + screen recordings) live under the OS app-data dir, outside the git
// repo (live-account screenshots can hold student data). These wrap the Rust commands in lib.rs.

export interface Artifact {
  name: string;
  kind: 'image' | 'video' | 'other';
  size: number;
  /** ms since epoch */
  mtime: number;
  /** data: URL for image tiles; null for video/other (open externally). */
  thumb: string | null;
}

export async function listArtifacts(): Promise<Artifact[]> {
  return invoke<Artifact[]>('list_artifacts');
}

export async function deleteArtifact(name: string): Promise<void> {
  await invoke('delete_artifact', { name });
}

/** Full artifact as a data: URL, for showing it inline in the app (img / video element). */
export async function readArtifact(name: string): Promise<string> {
  return invoke<string>('read_artifact', { name });
}

/** Open the artifact in the OS default app (secondary — the in-app viewer is primary). */
export async function openArtifact(name: string): Promise<void> {
  await invoke('open_artifact', { name });
}

/** Absolute path of the artifacts dir — handed to the agent so it saves screenshots there. */
export async function getArtifactsDir(): Promise<string> {
  return invoke<string>('artifacts_dir');
}

/** Start/stop recording the driven tab (CDP screencast → ffmpeg). Agent-invokable via __steveControl.
 *  `targetUrl` pins which tab to record when several are open; omitted → first embedded tab. */
export async function startRecording(targetUrl?: string): Promise<string> {
  return invoke<string>('start_recording', { targetUrl: targetUrl ?? null });
}

export async function stopRecording(): Promise<string | null> {
  return invoke<string | null>('stop_recording');
}
