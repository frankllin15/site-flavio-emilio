import { cn } from '@/lib/utils/cn';

interface SectionHeadingProps {
  title: string;
  subtitle?: string;
  centered?: boolean;
  className?: string;
}

export default function SectionHeading({
  title,
  subtitle,
  centered = true,
  className,
}: SectionHeadingProps) {
  // Check if className has custom text color for headings or paragraphs
  const hasCustomHeadingColor = className?.includes('[&_h2]:text-') || className?.includes('text-');
  const hasCustomParagraphColor = className?.includes('[&_p]:text-');

  return (
    <div className={cn(
      'mb-12',
      centered && 'flex flex-col items-center',
      className
    )}>
      <h2 className={cn(
        'text-4xl md:text-5xl font-bold mb-4',
        centered && 'text-center',
        !hasCustomHeadingColor && 'text-gray-900'
      )}>
        {title}
      </h2>
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
