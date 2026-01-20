import { cn } from '@/lib/utils/cn';
import { LucideIcon } from 'lucide-react';

interface SectionHeadingProps {
  title: string;
  subtitle?: string;
  centered?: boolean;
  className?: string;
  badge?: string;
  badgeVariant?: 'primary' | 'accent' | 'secondary';
  icon?: LucideIcon;
  showAccentLine?: boolean;
}

export default function SectionHeading({
  title,
  subtitle,
  centered = true,
  className,
  badge,
  badgeVariant = 'primary',
  icon: Icon,
  showAccentLine = true,
}: SectionHeadingProps) {
  // Check if className has custom text color for headings or paragraphs
  const hasCustomHeadingColor = className?.includes('[&_h2]:text-') || className?.includes('text-');
  const hasCustomParagraphColor = className?.includes('[&_p]:text-');

  const badgeVariants = {
    primary: 'bg-brand-blue-100 text-brand-blue-700 border-brand-blue-200',
    accent: 'bg-accent-gold-100 text-accent-gold-700 border-accent-gold-200',
    secondary: 'bg-gray-100 text-gray-700 border-gray-200'
  };

  return (
    <div className={cn(
      'mb-12 animate-fade-in',
      centered && 'flex flex-col items-center',
      className
    )}>
      {/* Badge */}
      {badge && (
        <div className="mb-4">
          <span className={cn(
            'inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold border uppercase tracking-wide',
            badgeVariants[badgeVariant]
          )}>
            {badge}
            {Icon && <Icon className="w-4 h-4 text-current md:hidden" strokeWidth={2} />}
          </span>
        </div>
      )}

      {/* Title with optional icon */}
      <h2 className={cn(
        'text-4xl md:text-5xl font-bold mb-4 relative inline-flex items-center gap-3',
        centered && 'text-center',
        !hasCustomHeadingColor && 'text-gray-900'
      )}>
        {Icon && (
          <Icon className="w-10 h-10 md:w-12 md:h-12 text-accent-gold-500 shrink-0 hidden md:inline-block" strokeWidth={2} />
        )}
        {title}
      </h2>

      {/* Accent Line */}
      {showAccentLine && (
        <div className="flex items-center gap-2 mb-4">
          <div className="h-1 w-12 bg-gradient-to-r from-brand-blue-500 to-accent-gold-400 rounded-full" />
          <div className="h-1 w-12 bg-accent-gold-400 rounded-full" />
          <div className="h-1 w-12 bg-gradient-to-r from-accent-gold-400 to-brand-blue-500 rounded-full" />
        </div>
      )}

      {/* Subtitle */}
      {subtitle && (
        <p className={cn(
          'text-xl',
          centered && 'text-center max-w-3xl',
          !centered && 'max-w-full',
          !hasCustomParagraphColor && 'text-gray-600'
        )}>
          {subtitle}
        </p>
      )}
    </div>
  );
}
