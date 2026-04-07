import { createReader } from '@keystatic/core/reader';
import { createGitHubReader } from '@keystatic/core/reader/github';
import keystaticConfig from '@/keystatic.config';

export function getReader() {
  if (
    process.env.KEYSTATIC_GITHUB_CLIENT_ID &&
    process.env.GITHUB_PAT
  ) {
    return createGitHubReader(keystaticConfig, {
      repo: 'frankllin15/site-flavio-emilio',
      token: process.env.GITHUB_PAT,
    });
  }
  return createReader(process.cwd(), keystaticConfig);
}
