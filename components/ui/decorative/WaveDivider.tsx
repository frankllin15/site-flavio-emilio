interface WaveDividerProps {
  variant: 'white-to-gray' | 'gray-to-white' | 'blue-to-white' | 'dark';
  position?: 'top' | 'bottom';
  flip?: boolean;
}

export default function WaveDivider({
  variant,
  position = 'bottom',
  flip = false
}: WaveDividerProps) {
  const positionClasses = position === 'bottom' ? 'bottom-0' : 'top-0';
  const flipTransform = flip ? 'scaleY(-1)' : 'none';

  const colorMap = {
    'white-to-gray': '#f9fafb', // gray-50
    'gray-to-white': '#ffffff',
    'blue-to-white': '#ffffff',
    'dark': '#111827' // gray-900
  };

  return (
    <div
      className={`absolute inset-x-0 ${positionClasses} h-16 md:h-24 pointer-events-none z-10`}
      aria-hidden="true"
    >
      <svg
        viewBox="0 0 1200 120"
        preserveAspectRatio="none"
        className="w-full h-full"
        style={{ transform: flipTransform }}
      >
        <path
          d="M0,0 C150,80 350,80 600,60 C850,40 1050,40 1200,80 L1200,120 L0,120 Z"
          fill={colorMap[variant]}
        />
      </svg>
    </div>
  );
}
