# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a professional portfolio and consulting website for Flávio Emílio Cavalcanti, an organizational consultant specializing in HR, leadership, and career management. Built with Next.js 16 (App Router), React 19, TypeScript, and Tailwind CSS 4.

**Primary Language**: Portuguese (pt-BR)

## Development Commands

```bash
# Development server (runs on http://localhost:3000)
npm run dev

# Production build
npm run build

# Start production server
npm start

# Run linter
npm run lint
```

## Architecture

### Tech Stack
- **Framework**: Next.js 16 (App Router)
- **React**: v19.2.3
- **TypeScript**: Strict mode enabled
- **Styling**: Tailwind CSS 4 with custom design tokens
- **UI Components**: Custom components + Radix UI primitives
- **Carousel**: Embla Carousel for Events and Services sections
- **Email**: Resend API for contact form
- **Validation**: Zod for form/API validation
- **Icons**: Lucide React

### Project Structure

```
app/
  ├── api/contact/route.ts       # Contact form API endpoint (POST)
  ├── servicos/[id]/             # Dynamic service detail pages
  ├── palestras/[id]/            # Dynamic lecture detail pages
  ├── layout.tsx                 # Root layout with fonts & metadata
  ├── page.tsx                   # Homepage (section composition)
  └── globals.css                # Tailwind config with brand tokens

components/
  ├── layout/                    # Header, Footer
  ├── sections/                  # Page sections (Hero, About, Services, etc.)
  ├── client/                    # Client components (ContactForm, MobileMenu)
  └── ui/                        # Reusable UI components (Button, Card, Carousel)

lib/
  ├── data/                      # Static content (services, lectures, events, books, etc.)
  ├── utils/                     # Utility functions (cn for className merging)
  ├── resend.ts                  # Resend client initialization
  └── blog.ts                    # Blog-related utilities

types/
  ├── index.ts                   # Main type exports (Service, Lecture, Event, Book, etc.)
  ├── blog.ts                    # Blog-specific types
  └── contact.ts                 # Contact form types
```

### Key Patterns

#### Component Organization
- **Layout components**: Header and Footer in `components/layout/`
- **Section components**: Full-page sections (Hero, Services, etc.) in `components/sections/`
- **Client components**: Interactive components marked with `"use client"` in `components/client/`
- **UI components**: Reusable primitives in `components/ui/`

#### Data Management
- All content is stored as typed TypeScript objects in `lib/data/`
- Services, lectures, events, and books are exported as arrays from their respective files
- Types are centralized in `types/index.ts` with domain-specific exports from subdirectories

#### Styling
- Custom design tokens defined in `app/globals.css` using Tailwind's `@theme` directive
- Brand colors: Deep blue (trust/authority), graphite gray (sophistication), gold/terracotta (warm accents)
- Custom fonts: Playfair Display (serif), Montserrat (display), Geist (sans/mono)
- The `cn()` utility (`lib/utils.ts`) merges Tailwind classes using `clsx` and `tailwind-merge`

#### Routing
- Uses Next.js App Router with TypeScript
- Dynamic routes for services (`servicos/[id]`) and lectures (`palestras/[id]`)
- Homepage (`app/page.tsx`) composes all sections in sequence

#### Forms & API
- Contact form uses Zod validation schema
- POST endpoint at `/api/contact` sends emails via Resend
- Requires environment variables: `RESEND_API_KEY`, `CONTACT_EMAIL_TO`, `CONTACT_EMAIL_FROM`

### Path Aliases
- `@/*` maps to the project root (configured in `tsconfig.json`)
- Example: `@/components/ui/Button` instead of `../../../components/ui/Button`

### Environment Variables
Required variables (see `.env.local` for reference):
- `RESEND_API_KEY`: API key from resend.com
- `CONTACT_EMAIL_TO`: Recipient email for contact form submissions
- `CONTACT_EMAIL_FROM`: Sender email (must be verified in Resend)

## Development Notes

### Carousel Implementation
- The `Carousel` component (`components/ui/Carousel.tsx`) uses Embla Carousel
- Supports keyboard navigation (arrow keys), touch/drag, and click-to-navigate dots
- Mobile shows swipe hint, desktop shows navigation arrows
- Responsive breakpoints: 100% on mobile, 50% on tablet, 33.33% on desktop

### Type Safety
- All data structures use TypeScript interfaces from `types/`
- Service and Lecture types support flexible content structures (topics, learningPoints, etc.)
- Zod schemas validate API inputs at runtime

### Image Configuration
- Next.js image optimization configured with qualities [100, 70] in `next.config.ts`
- Social proof logos, book covers, and event images stored in `public/images/`

### Fonts
- Custom Google Fonts loaded in `app/layout.tsx`
- Font variables injected via CSS custom properties and referenced in `globals.css`

### SEO & Metadata
- Comprehensive metadata defined in `app/layout.tsx`
- OpenGraph and Twitter card support
- Portuguese locale (`pt_BR`)

## Common Tasks

### Adding a new service
1. Add service object to `lib/data/services.ts` with required fields (id, title, description)
2. Optionally add logo image to `public/images/services/`
3. Service automatically appears in Services section carousel

### Adding a new event/lecture
1. Add to `lib/data/events.ts` or `lib/data/lectures.ts`
2. Add image to `public/images/events/` or `public/images/lectures/`
3. Automatically appears in respective section carousel

### Modifying brand colors
- Update design tokens in `app/globals.css` under `@theme inline`
- Follow existing naming convention: `--color-{category}-{variant}-{shade}`

### Testing the contact form locally
1. Set up Resend API key in `.env.local`
2. Use `onboarding@resend.dev` as sender for testing
3. POST to `/api/contact` with valid schema payload
