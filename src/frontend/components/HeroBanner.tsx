import Link from 'next/link'
import { useLanguage } from '../context/LanguageContext'

const trendingRightRail = [
  {
    title: 'Resident Evil: Requiem',
    subtitle: 'Capcom',
    badge: { th: 'ใหม่', en: 'New' },
    image: 'https://img.asmedia.epimg.net/resizer/v2/ZKHOGS4GGNHPNJDWW4TCOXTKFY.jpg?auth=14b5f55620defb4995664da099fc27694e420e2378a0f2f7cf29318eb271a8fc&width=1288&height=725&smart=true',
  },
  {
    title: 'Battlefield REDSEC',
    subtitle: 'EA Originals',
    badge: { th: '-40%', en: '-40%' },
    image: 'https://bsmedia.business-standard.com/_media/bs/img/article/2025-10/28/full/1761638363-2733.png',
  },
  {
    title: 'EA SPORTS FC™ 26',
    subtitle: 'Electronic Arts',
    badge: { th: 'ทดลองเล่น', en: 'Trial' },
    image: 'https://retrogems.fr/wp-content/uploads/2025/09/ea-sports-fc-26-ultimate-edition.jpg',
  },
  {
    title: 'Duet Night Abyss',
    subtitle: 'Aniworks',
    badge: { th: 'อัปเดต', en: 'Update' },
    image: 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/3950020/ee582fcde01feabf17ed4e31a54a2b9f7e7284a3/header.jpg?t=1757067901',
  },
]

export default function HeroBanner() {
  const { t, language } = useLanguage()

  return (
    <section className="grid gap-6 lg:grid-cols-[1.7fr,1fr]">
      <div className="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-indigo-700/60 via-purple-800/30 to-rose-700/20 p-10">
        <div
          className="pointer-events-none absolute inset-0 bg-[url('https://images.unsplash.com/photo-1607252650355-f7fd0460ccdb?q=80&w=1600&auto=format&fit=crop')] bg-cover bg-center opacity-40"
        />
        <div
          className="pointer-events-none absolute inset-0 opacity-40"
          style={{
            background:
              'radial-gradient(1200px 400px at 20% 20%, rgba(99,102,241,.4), transparent 60%), radial-gradient(900px 500px at 80% 80%, rgba(244,63,94,.35), transparent 60%)',
          }}
        />

        <div className="relative z-10 flex flex-col h-full justify-between gap-8">
          <div className="max-w-2xl space-y-6">
            <span className="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1 text-xs uppercase tracking-[0.2em] text-indigo-100/80 backdrop-blur">
              🔥 {t('hero.pill')}
            </span>
            <h1 className="text-3xl md:text-5xl font-semibold leading-tight text-white drop-shadow">
              {t('hero.title')}
            </h1>
            <p className="text-base md:text-lg text-slate-200/90">
              {t('hero.description')}
            </p>
          </div>

          <div className="flex flex-col sm:flex-row gap-3">
            <Link
              href="/products"
              className="inline-flex items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900 shadow-lg shadow-indigo-900/40 transition hover:-translate-y-0.5 hover:bg-slate-100"
            >
              {t('hero.cta.primary')}
              <span>→</span>
            </Link>
            <Link
              href="/games"
              className="inline-flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/5 px-6 py-3 text-sm font-semibold text-white transition hover:border-white/40"
            >
              {t('hero.cta.secondary')}
            </Link>
          </div>
        </div>
      </div>

      <aside className="rounded-3xl border border-white/10 bg-white/5 p-6 space-y-4">
        <p className="text-sm font-semibold text-slate-200">{t('search.trending')}</p>
        <div className="space-y-4">
          {trendingRightRail.map((item) => (
            <div key={item.title} className="flex rounded-2xl border border-white/10 bg-white/5 p-3 gap-3 hover:border-indigo-400/50 transition">
              <div
                className="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl bg-cover bg-center"
                style={{ backgroundImage: `url(${item.image})` }}
              />
              <div className="flex-1">
                <p className="text-xs uppercase tracking-[0.3em] text-indigo-200">{item.badge[language]}</p>
                <p className="text-sm font-semibold text-white line-clamp-1">{item.title}</p>
                <p className="text-xs text-slate-400">{item.subtitle}</p>
              </div>
              <button type="button" className="self-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs text-slate-200 hover:border-indigo-300 transition">
                +
              </button>
            </div>
          ))}
        </div>
      </aside>
    </section>
  )
}
