/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  images: {
    remotePatterns: [
      { protocol: 'http', hostname: 'localhost', port: '8000' },
      { protocol: 'https', hostname: '**' }
    ]
  },
  env: {
    PHP_API_BASE: process.env.PHP_API_BASE || 'http://localhost:8000'
  }
}

module.exports = nextConfig


