import { getReader } from '@/lib/keystatic';
import type { Event } from '@/types';

async function getEvents(): Promise<Event[]> {
  const reader = getReader();
  const slugs = await reader.collections.events.list();
  const entries = await Promise.all(
    slugs.map(async (slug) => {
      const data = await reader.collections.events.read(slug);
      if (!data) return null;
      return {
        id: slug,
        title: data.title || '',
        description: data.description || '',
        location: data.location || '',
        image: data.image || '',
        date: data.date || undefined,
        sortDate: new Date(data.sortDate || ''),
      } satisfies Event;
    })
  );
  return entries.filter((e) => e !== null);
}

export async function getSortedEvents(limit?: number): Promise<Event[]> {
  const events = await getEvents();
  const sorted = events.sort(
    (a, b) => b.sortDate.getTime() - a.sortDate.getTime()
  );
  return limit ? sorted.slice(0, limit) : sorted;
}

export async function getEventsCount(): Promise<number> {
  const reader = getReader();
  const slugs = await reader.collections.events.list();
  return slugs.length;
}
