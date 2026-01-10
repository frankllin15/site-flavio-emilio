"use client";

import { useState, useEffect } from "react";
import { cn } from "@/lib/utils/cn";
import MobileMenu from "@/components/client/MobileMenu";
import { Button } from "@/components/ui/Button";
import Link from "next/link";

const menuItems = [
  { label: "Sobre", href: "#sobre" },
  { label: "Proficient", href: "#proficient" },
  { label: "Serviços", href: "#servicos" },
  { label: "Palestras", href: "#palestras" },
  { label: "Eventos", href: "#eventos" },
  { label: "Blog", href: "#blog" },
  { label: "Livros", href: "#livros" },
];

export default function Header() {
  const [isScrolled, setIsScrolled] = useState(false);

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
        isScrolled ? "bg-white shadow-md py-3" : "bg-transparent py-4"
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
                isScrolled ? "text-gray-900" : "text-white"
              )}
            >
              <span className="font-medium tracking-tight opacity-90 group-hover:opacity-100 transition-opacity">
                Flávio
              </span>
              <span className="font-extrabold tracking-tighter">Emílio</span>
              <span
                className={cn(
                  "h-2 w-2 rounded-full ml-0.5 animate-pulse-slow",
                  isScrolled ? "bg-blue-600" : "bg-blue-400"
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
                  isScrolled ? "text-gray-700" : "text-white"
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
                href="https://wa.me/5511999999999"
                target="_blank"
                rel="noopener noreferrer"
              >
                Entrar em Contato
              </Link>
            </Button>

            <MobileMenu isScrolled={isScrolled} />
          </div>
        </div>
      </div>
    </header>
  );
}
