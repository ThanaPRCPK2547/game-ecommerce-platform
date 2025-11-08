import Link from 'next/link'
import { ReactNode } from 'react'
import BrandLogo from './BrandLogo'
import { useLanguage, type TranslationKey } from '../context/LanguageContext'

const navLinks: { href: string; key: TranslationKey }[] = [
  { href: '/', key: 'nav.store' },
  { href: '/games', key: 'nav.discover' },
  { href: '/products', key: 'nav.browse' },
  { href: '/news', key: 'nav.news' },
]

export default function Layout({ children }: { children: ReactNode }) {
  const { language, setLanguage, t } = useLanguage()

  return (
    <div className="min-h-screen flex flex-col bg-gradient-to-b from-[#080b14] via-[#0e1424] to-[#121829] text-slate-100">
      <header className="border-b border-white/10 bg-black/30 backdrop-blur">
        <div className="max-w-7xl mx-auto px-4 lg:px-6">
          <div className="flex items-center justify-between py-4 gap-6">
            <Link href="/" className="flex items-center gap-3">
              <span className="inline-flex items-center justify-center rounded-lg bg-white/5 border border-white/10 px-3 py-2">
                <BrandLogo size={20} />
              </span>
              <div className="hidden lg:block text-left">
                <p className="text-xs uppercase tracking-[0.3em] text-slate-400">Game</p>
                <p className="text-base font-semibold">Store</p>
              </div>
            </Link>

            <nav className="hidden md:flex items-center gap-6 text-sm text-slate-300">
              {navLinks.map(({ href, key }) => (
                <Link key={key} href={href} className="hover:text-white transition">
                  {t(key)}
                </Link>
              ))}
            </nav>

            <div className="flex items-center gap-3">
              <div className="hidden lg:flex items-center gap-2 rounded-full border border-white/10 bg-white/5 p-1 text-xs">
                <button
                  type="button"
                  className={`px-3 py-1 rounded-full transition ${language === 'th' ? 'bg-indigo-500 text-white' : 'text-slate-300 hover:text-white'}`}
                  onClick={() => setLanguage('th')}
                >
                  TH
                </button>
                <button
                  type="button"
                  className={`px-3 py-1 rounded-full transition ${language === 'en' ? 'bg-indigo-500 text-white' : 'text-slate-300 hover:text-white'}`}
                  onClick={() => setLanguage('en')}
                >
                  EN
                </button>
              </div>

              <Link href="/cart" className="hidden md:inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-sm text-slate-200 hover:border-indigo-400 hover:text-white transition">
                <span>🛒</span>
                {t('nav.cart')}
              </Link>

              <div className="flex items-center gap-2">
                <Link href="/login" className="hidden sm:inline-flex px-3 py-1.5 text-sm text-slate-200 hover:text-white transition">
                  {t('nav.login')}
                </Link>
                <Link href="/signup" className="inline-flex rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                  {t('nav.signup')}
                </Link>
              </div>
            </div>
          </div>
        </div>
      </header>

      <main className="flex-1">
        <div className="max-w-7xl mx-auto px-4 lg:px-6 py-6">{children}</div>
      </main>

      <footer className="border-t border-white/10 bg-black/40 backdrop-blur-sm">
        <div className="max-w-7xl mx-auto px-4 lg:px-6 py-10">
          <div className="grid gap-6 md:grid-cols-5 text-sm text-slate-300">
            <div className="space-y-3">
              <p className="text-xs uppercase tracking-[0.3em] text-slate-500">Store</p>
              <ul className="space-y-2 text-slate-400">
                <li><Link href="/about" className="hover:text-white transition">{t('footer.about')}</Link></li>
                <li><Link href="/support" className="hover:text-white transition">{t('footer.support')}</Link></li>
                <li><Link href="/legal" className="hover:text-white transition">{t('footer.legal')}</Link></li>
              </ul>
            </div>
            <div className="space-y-3">
              <p className="text-xs uppercase tracking-[0.3em] text-slate-500">Company</p>
              <ul className="space-y-2 text-slate-400">
                <li><Link href="/careers" className="hover:text-white transition">{t('footer.careers')}</Link></li>
                <li><Link href="/press" className="hover:text-white transition">{t('footer.press')}</Link></li>
                <li><Link href="/contact" className="hover:text-white transition">{t('footer.contact')}</Link></li>
              </ul>
            </div>
            <div className="space-y-3">
              <p className="text-xs uppercase tracking-[0.3em] text-slate-500">Apps</p>
              <ul className="space-y-2 text-slate-400">
                <li><Link href="/download" className="hover:text-white transition">{t('nav.download')}</Link></li>
                <li><Link href="/cart" className="hover:text-white transition">{t('nav.cart')}</Link></li>
                <li><Link href="/login" className="hover:text-white transition">{t('nav.login')}</Link></li>
              </ul>
            </div>
            <div className="space-y-3">
              <p className="text-xs uppercase tracking-[0.3em] text-slate-500">Follow</p>
              <ul className="space-y-2 text-slate-400">
                <li><a href="https://facebook.com" className="hover:text-white transition" target="_blank" rel="noreferrer">Facebook</a></li>
                <li><a href="https://x.com" className="hover:text-white transition" target="_blank" rel="noreferrer">X</a></li>
              </ul>
            </div>
            <div className="flex flex-col justify-between">
              <p className="text-xs text-slate-500">{t('footer.copyright')}</p>
              <button
                type="button"
                className="self-start mt-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs text-slate-300 hover:text-white transition"
                onClick={() => {
                  if (typeof window !== 'undefined') {
                    window.scrollTo({ top: 0, behavior: 'smooth' })
                  }
                }}
              >
                ⬆ {t('footer.backToTop')}
              </button>
            </div>
          </div>
        </div>
      </footer>
    </div>
  )
}
