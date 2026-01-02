'use client';

import { useState, useEffect } from 'react';
import { cn } from '@/lib/utils/cn';
import MobileMenu from '@/components/client/MobileMenu';
import Button from '@/components/ui/Button';

const menuItems = [
  { label: 'Sobre', href: '#sobre' },
  // { label: 'Soluções', href: '#solucoes' },
  { label: 'Eventos', href: '#eventos' },
  { label: 'Blog', href: '#blog' },
  { label: 'Livros', href: '#livros' },
];

export default function Header() {
  const [isScrolled, setIsScrolled] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 20);
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <header
      className={cn(
        'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
        isScrolled
          ? 'bg-white shadow-md py-3'
          : 'bg-transparent py-4'
      )}
    >
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between">
          {/* Logo */}
          <a href="#hero" className="flex items-center space-x-2">
            <span className={cn(
              'text-2xl font-bold transition-colors',
              isScrolled ? 'text-gray-900' : 'text-white'
            )}>
              Flávio Emílio
            </span>
          </a>

          {/* Desktop Navigation */}
          <nav className="hidden md:flex items-center space-x-8">
            {menuItems.map((item) => (
              <a
                key={item.href}
                href={item.href}
                className={cn(
                  'font-semibold transition-colors hover:text-primary',
                  isScrolled ? 'text-gray-700' : 'text-white'
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
              asLink
              href="#contato"
              className="hidden sm:inline-block"
            >
              <span className='hidden md:block'>
              Entrar em Contato
              </span>
              <span className='md:hidden'>
                Contato
              </span>
            </Button>

            <MobileMenu />
          </div>
        </div>
      </div>
    </header>
  );
}
