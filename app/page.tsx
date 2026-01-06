import Header from '@/components/layout/Header';
import Footer from '@/components/layout/Footer';
import Hero from '@/components/sections/Hero';
import About from '@/components/sections/About';
import Proficient from '@/components/sections/Proficient';
import Services from '@/components/sections/Services';
import Events from '@/components/sections/Events';
import BlogPreview from '@/components/sections/BlogPreview';
import Books from '@/components/sections/Books';
import Contact from '@/components/sections/Contact';
import SocialProof from '@/components/sections/SocialProof';

export default function Home() {
  return (
    <>
      <Header />
      <main>
        <Hero />
        <About />
        <Proficient />
        {/* <Services /> */}
        <Events />
        <SocialProof />
        <BlogPreview />
        <Books />
        <Contact />
      </main>
      <Footer />
    </>
  );
}
