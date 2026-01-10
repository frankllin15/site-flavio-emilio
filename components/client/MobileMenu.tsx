'use client';

import { useState } from 'react';
import Link from 'next/link';
import { cn } from '@/lib/utils/cn';

const menuItems = [
  { label: 'Início', href: '/' },
  { label: 'Sobre', href: '/#sobre' },
  { label: 'Proficient', href: '/#proficient' },
  { label: 'Serviços', href: '/#servicos' },
  { label: 'Palestras', href: '/#palestras' },
  { label: 'Eventos', href: '/#eventos' },
  { label: 'Blog', href: '/#blog' },
  { label: 'Livros', href: '/#livros' },
  { label: 'Contato', href: '/#contato' },
];

interface MobileMenuProps {
  isScrolled: boolean;
}

export default function MobileMenu({ isScrolled }: MobileMenuProps) {
  const [isOpen, setIsOpen] = useState(false)

  const toggleMenu = () => setIsOpen(!isOpen);

  const handleLinkClick = () => {
    setIsOpen(false);
  };

  return (
    <>
      {/* Hamburger Button */}
      <button
        onClick={toggleMenu}
        className={cn("md:hidden p-2 text-gray-700 hover:text-primary transition-colors",
          isScrolled ? 'text-gray-900' : 'text-white'
        )}
        aria-label="Menu de navegação"
        aria-expanded={isOpen}
      >
        <svg
          className="w-6 h-6"
          fill="none"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          {isOpen ? (
            <path d="M6 18L18 6M6 6l12 12" />
          ) : (
            <path d="M4 6h16M4 12h16M4 18h16" />
          )}
        </svg>
      </button>

      {/* Mobile Menu Overlay */}
      {isOpen && (
        <div
          className="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
          onClick={toggleMenu}
        />
      )}

      {/* Mobile Menu Panel */}
      <div
        className={cn(
          'fixed top-0 right-0 h-full w-64 bg-white shadow-2xl z-50 transform transition-transform duration-300 ease-in-out md:hidden',
          isOpen ? 'translate-x-0' : 'translate-x-full'
        )}
      >
        <div className="flex flex-col h-full">
          {/* Close Button */}
          <div className="flex justify-end p-4">
            <button
              onClick={toggleMenu}
              className="p-2 text-gray-700 hover:text-primary transition-colors"
              aria-label="Fechar menu"
            >
              <svg
                className="w-6 h-6"
                fill="none"
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="2"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          {/* Menu Items */}
          <nav className="flex flex-col px-4 py-8 space-y-4">
            {menuItems.map((item) => (
              <Link
                key={item.href}
                href={item.href}
                onClick={handleLinkClick}
                className="text-lg font-semibold text-gray-700 hover:text-primary transition-colors py-2"
              >
                {item.label}
              </Link>
            ))}
          </nav>
        </div>
      </div>
    </>
  );
}
