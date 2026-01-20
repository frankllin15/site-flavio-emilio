"use client";

import { useState, useEffect } from "react";
import { cn } from "@/lib/utils/cn";
import MobileMenu from "@/components/client/MobileMenu";
import { Button } from "@/components/ui/Button";
import Link from "next/link";
import { is } from "zod/locales";

const menuItems = [
  { label: "Sobre", href: "/#sobre" },
  { label: "Proficient", href: "/#proficient" },
  { label: "Serviços", href: "/#servicos" },
  { label: "Palestras", href: "/#palestras" },
  { label: "Eventos", href: "/#eventos" },
  { label: "Blog", href: "/#blog" },
  { label: "Livros", href: "/#livros" },
  // { label: "Contato", href: "/#contato" },
];

type HeaderProps = {
  fixed?: boolean;
};

export default function Header({ fixed = false }: HeaderProps) {
  const [isScrolled, setIsScrolled] = useState(false);
  const applyFixed = fixed || isScrolled;

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 20);
    };

    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <header
      className={cn(
        "fixed top-0 left-0 right-0 z-50 transition-all duration-300",
        applyFixed ? "bg-white shadow-md py-3" : "bg-transparent py-4"
      )}
    >
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between">
          {/* Logo */}
          {/* <Link href="/" className="flex items-center space-x-2">
            <span
              className={cn(
                "text-2xl font-bold transition-colors",
                isScrolled ? "text-gray-900" : "text-white"
              )}
            >
              Flávio Emílio
            </span>
          </Link> */}

          <Link href="/" className="flex items-center group">
            <span
              className={cn(
                "text-2xl transition-colors duration-300 flex items-baseline gap-1",
                applyFixed ? "text-gray-900" : "text-white"
              )}
            >
              <span className="font-extrabold tracking-tight">Flávio</span>
              <span className="font-medium tracking-tighter  opacity-90 group-hover:opacity-100 transition-opacity">
                Emílio
              </span>
              <span
                className={cn(
                  "h-2 w-2 rounded-full ml-0.5 animate-pulse-slow bg-blue-600"
                )}
              ></span>
            </span>
          </Link>

          {/* Desktop Navigation */}
          <nav className="hidden md:flex items-center space-x-8">
            {menuItems.map((item) => (
              <a
                key={item.href}
                href={item.href}
                className={cn(
                  "font-semibold transition-colors hover:text-primary",
                  applyFixed ? "text-gray-700" : "text-white"
                )}
              >
                {item.label}
              </a>
            ))}
          </nav>

          {/* CTA Button + Mobile Menu */}
          <div className="flex items-center space-x-4">
            <Button
              variant="primary"
              asChild
              className="hidden md:inline-block"
            >
              <Link
                href="https://wa.me/5584991056687"
                target="_blank"
                rel="noopener noreferrer"
              >
                Entrar em Contato
              </Link>
            </Button>

            <MobileMenu isScrolled={applyFixed} />
          </div>
        </div>
      </div>
    </header>
  );
}
