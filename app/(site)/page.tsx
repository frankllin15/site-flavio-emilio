import Header from '@/components/layout/Header';
import Footer from '@/components/layout/Footer';
import Hero from '@/components/sections/Hero';
import Welcome from '@/components/sections/Welcome';
import About from '@/components/sections/About';
import Proficient from '@/components/sections/Proficient';
import Services from '@/components/sections/Services';
import Lectures from '@/components/sections/Lectures';
import Events from '@/components/sections/Events';
import BlogPreview from '@/components/sections/BlogPreview';
import Books from '@/components/sections/Books';
import Contact from '@/components/sections/Contact';
import Testimonials from '@/components/sections/Testimonials';
import Clients from '@/components/sections/Clients';
import ScrollToTopButton from '@/components/ui/ScrollToTopButton';

export default function Home() {
  return (
    <>
      <Header />
      <main>
        <Hero />
        {/* <Welcome /> */}
        <About />
        <Proficient />
        <Services />
        <Lectures />
        <Events limit={3} />
        <Clients />
        <Testimonials />
        <BlogPreview />
        <Books />
        <Contact />
      </main>
      <Footer />
      <ScrollToTopButton />
      {/* <FloatingWhatsAppButton /> */}
    </>
  );
}
