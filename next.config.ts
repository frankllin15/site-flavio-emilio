import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  images: {
    qualities: [100, 70],
  },
  serverExternalPackages: ['@keystatic/core', '@keystatic/next'],
  async redirects() {
    return [
      {
        source: '/admin',
        destination: '/keystatic',
        permanent: false,
      },
    ];
  },
};

export default nextConfig;
