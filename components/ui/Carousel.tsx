"use client";

import { useState, useCallback, useEffect, ReactNode } from "react";
import useEmblaCarousel from "embla-carousel-react";
import { EmblaOptionsType } from "embla-carousel";
import { ChevronLeft, ChevronRight, MoveLeft, MoveRight } from "lucide-react";
import { cn } from "@/lib/utils";

interface CarouselProps {
  children: ReactNode;
  options?: EmblaOptionsType;
  showMobileHint?: boolean;
  mobileHintText?: string;
  prevLabel?: string;
  nextLabel?: string;
  className?: string;
}

export default function Carousel({
  children,
  options = {
    loop: true,
    align: "start",
    dragFree: false,
    containScroll: "trimSnaps",
  },
  showMobileHint = true,
  mobileHintText = "Arraste para o lado",
  prevLabel = "Anterior",
  nextLabel = "Próximo",
  className,
}: CarouselProps) {
  const [emblaRef, emblaApi] = useEmblaCarousel(options);

  const [selectedIndex, setSelectedIndex] = useState(0);
  const [scrollSnaps, setScrollSnaps] = useState<number[]>([]);
  const [canScrollPrev, setCanScrollPrev] = useState(false);
  const [canScrollNext, setCanScrollNext] = useState(false);

  // Initialize scroll snaps and selection tracking
  useEffect(() => {
    if (!emblaApi) return;

    setScrollSnaps(emblaApi.scrollSnapList());

    const onSelect = () => {
      setSelectedIndex(emblaApi.selectedScrollSnap());
      setCanScrollPrev(emblaApi.canScrollPrev());
      setCanScrollNext(emblaApi.canScrollNext());
    };

    emblaApi.on("select", onSelect);
    emblaApi.on("reInit", onSelect);
    onSelect();

    return () => {
      emblaApi.off("select", onSelect);
      emblaApi.off("reInit", onSelect);
    };
  }, [emblaApi]);

  // Keyboard navigation
  useEffect(() => {
    if (!emblaApi) return;

    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "ArrowLeft") emblaApi.scrollPrev();
      if (e.key === "ArrowRight") emblaApi.scrollNext();
    };

    window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [emblaApi]);

  const scrollPrev = useCallback(() => {
    if (emblaApi) emblaApi.scrollPrev();
  }, [emblaApi]);

  const scrollNext = useCallback(() => {
    if (emblaApi) emblaApi.scrollNext();
  }, [emblaApi]);

  const scrollTo = useCallback(
    (index: number) => {
      if (emblaApi) emblaApi.scrollTo(index);
    },
    [emblaApi]
  );

  return (
    <div className={cn("relative", className)}>
      {/* Navigation Buttons - Desktop/Tablet Only */}
      <button
        onClick={scrollPrev}
        disabled={!canScrollPrev}
        className={cn(
          "absolute left-0 -translate-x-4 top-1/2 -translate-y-1/2 z-10",
          "w-12 h-12 rounded-full",
          "bg-linear-to-br from-white to-gray-50",
          "shadow-xl border-2 border-accent-gold-500/20",
          "hidden md:flex items-center justify-center",
          "transition-all duration-300",
          "hover:scale-110 hover:shadow-2xl hover:border-accent-gold-500",
          "active:scale-95",
          "disabled:opacity-0 disabled:pointer-events-none"
        )}
        aria-label={prevLabel}
      >
        <ChevronLeft className="w-6 h-6 text-gray-700" />
      </button>

      <button
        onClick={scrollNext}
        disabled={!canScrollNext}
        className={cn(
          "absolute right-0 translate-x-4 top-1/2 -translate-y-1/2 z-10",
          "w-12 h-12 rounded-full",
          "bg-linear-to-br from-white to-gray-50",
          "shadow-xl border-2 border-accent-gold-500/20",
          "hidden md:flex items-center justify-center",
          "transition-all duration-300",
          "hover:scale-110 hover:shadow-2xl hover:border-accent-gold-500",
          "active:scale-95",
          "disabled:opacity-0 disabled:pointer-events-none"
        )}
        aria-label={nextLabel}
      >
        <ChevronRight className="w-6 h-6 text-gray-700" />
      </button>

      {/* Carousel Track */}
      <div className="overflow-hidden" ref={emblaRef}>
        <div className="flex">{children}</div>
      </div>

      {/* Mobile Swipe Hint */}
      {showMobileHint && (
        <div
          className={cn(
            "md:hidden text-center mt-4 mb-2 text-sm text-gray-500",
            "flex items-center justify-center gap-2"
          )}
        >
          <MoveLeft />
          <span>{mobileHintText}</span>
          <MoveRight />
        </div>
      )}

      {/* Indicators/Dots */}
      <div className="flex justify-center gap-2 mt-8">
        {scrollSnaps.map((_, index) => (
          <button
            key={index}
            onClick={() => scrollTo(index)}
            className={cn(
              "h-2 rounded-full transition-all duration-300",
              index === selectedIndex
                ? "w-8 bg-accent-gold-500"
                : "w-2 bg-gray-300 hover:bg-gray-400"
            )}
            aria-label={`Ir para página ${index + 1}`}
          />
        ))}
      </div>
    </div>
  );
}

// CarouselItem component for convenient usage
export function CarouselItem({
  children,
  className,
}: {
  children: ReactNode;
  className?: string;
}) {
  return (
    <div
      className={cn(
        "flex-[0_0_100%] md:flex-[0_0_50%] lg:flex-[0_0_33.333%] px-2",
        className
      )}
    >
      {children}
    </div>
  );
}
