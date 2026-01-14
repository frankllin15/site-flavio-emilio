import { cn } from "@/lib/utils/cn";

interface ContainerProps {
  children: React.ReactNode;
  className?: string;
  as?: "div" | "section" | "article" | "main";
  style?: React.CSSProperties;
}

export default function Container({
  children,
  className,
  as: Component = "div",
  style,
}: ContainerProps) {
  return (
    <Component
      className={cn(
        "container mx-auto",
        // Só aplica o padding padrão se não houver um override explícito
        // ou se você preferir, use uma lógica de negação:
        !className?.includes("px-") && "px-4 sm:px-6 lg:px-8",
        className
      )}
      style={style}
    >
      {children}
    </Component>
  );
}
