/**
 * Outlier detection over a graded batch.
 *
 * Ported from `grading-server/grading.js` (detectOutliers). The 2σ threshold is a tuned
 * decision, not an arbitrary one: O.G.R.E previously used 1σ, which flags ~32% of a
 * normal distribution and dragged accurate scores back toward the mean on the review
 * pass. 2σ flags ~5% — genuine mistakes rather than ordinary spread.
 */
import type { BatchResult } from './batch';

export interface OutlierEntry {
  studentIndex: number;
  score: number;
  /** Distance from the mean, in standard deviations. */
  deviation: number;
}

export interface OutlierReport {
  mean: number;
  stdDev: number;
  outliers: OutlierEntry[];
}

export function detectOutliers(results: BatchResult[]): OutlierReport {
  if (!results || results.length === 0) return { mean: 0, stdDev: 0, outliers: [] };

  const scores = results.map((r) => r.score);
  const mean = scores.reduce((a, b) => a + b, 0) / scores.length;
  const variance = scores.reduce((sum, s) => sum + (s - mean) ** 2, 0) / scores.length;
  const stdDev = Math.sqrt(variance);
  const threshold = stdDev * 2;

  const outliers = results
    .map((r) => ({
      studentIndex: r.studentIndex,
      score: r.score,
      // Guard the zero-spread case: an identically-scored class has stdDev 0, and
      // dividing by it would make every student NaN-deviant.
      deviation: Math.abs(r.score - mean) / (stdDev || 1),
      isOutlier: Math.abs(r.score - mean) > threshold,
    }))
    .filter((r) => r.isOutlier)
    .sort((a, b) => b.deviation - a.deviation)
    .map(({ studentIndex, score, deviation }) => ({
      studentIndex,
      score,
      deviation: parseFloat(deviation.toFixed(2)),
    }));

  return { mean: parseFloat(mean.toFixed(2)), stdDev: parseFloat(stdDev.toFixed(2)), outliers };
}
