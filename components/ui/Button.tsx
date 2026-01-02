import { cn } from '@/lib/utils/cn';

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'primary' | 'secondary' | 'accent' | 'tertiary';
  children: React.ReactNode;
  href?: string;
  asLink?: boolean;
}

export default function Button({
  variant = 'primary',
  children,
  className,
  href,
  asLink = false,
  ...props
}: ButtonProps) {
  const baseClasses = {
    primary: 'btn-primary',
    secondary: 'btn-secondary',
    accent: 'btn-accent',
    tertiary: 'btn-tertiary',
    
  };

  const classes = cn(baseClasses[variant], className);

  if (asLink && href) {
    return (
      <a href={href} className={classes}>
        {children}
      </a>
    );
  }

  return (
    <button className={classes} {...props}>
      {children}
    </button>
  );
}
