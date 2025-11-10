import Layout from '../components/Layout'
import ProductCard from '../components/ProductCard'
import HeroBanner from '../components/HeroBanner'
import Section from '../components/Section'
import { useLanguage } from '../context/LanguageContext'

type Product = {
  id: number
  name: string
  description: string
  price: number
  stock_quantity: number
  category_name?: string
}

type Post = {
  id: number
  title: string
  content: string
  author_name?: string
}

type HomeProps = {
  products: Product[]
  posts: Post[]
  apiBase: string
}

const featuredCategories = [
  { th: 'แอ็กชัน', en: 'Action' },
  { th: 'ผจญภัย', en: 'Adventure' },
  { th: 'อินดี้', en: 'Indie' },
  { th: 'สวมบทบาท', en: 'RPG' },
  { th: 'ครอบครัว', en: 'Family' },
  { th: 'มัลติเพลเยอร์', en: 'Multiplayer' },
]

export default function Home({ products, posts }: HomeProps) {
  const { t, language } = useLanguage()

  return (
    <Layout>
      <div className="space-y-12">
        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
          <div className="flex-1">
            <label htmlFor="store-search" className="sr-only">
              {t('search.placeholder')}
            </label>
            <div className="relative">
              <input
                id="store-search"
                type="search"
                placeholder={t('search.placeholder')}
                className="w-full rounded-full border border-white/10 bg-black/40 px-6 py-3 text-sm text-white placeholder:text-slate-500 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40"
              />
              <span className="pointer-events-none absolute right-5 top-1/2 -translate-y-1/2 text-slate-500">⌕</span>
            </div>
          </div>
          <div className="flex justify-end gap-2 text-xs text-slate-400">
            <span className="uppercase tracking-[0.3em] text-slate-500">{t('search.trending')}</span>
            <span className="rounded-full border border-white/10 bg-white/5 px-3 py-1">RPG</span>
            <span className="rounded-full border border-white/10 bg-white/5 px-3 py-1">Shooter</span>
            <span className="rounded-full border border-white/10 bg-white/5 px-3 py-1">Metaverse</span>
          </div>
        </div>

        <HeroBanner />

        <Section
          title={t('section.featured')}
          action={
            <a href="/products" className="text-sm font-medium text-indigo-300 hover:text-indigo-200">
              {language === 'th' ? 'ดูเพิ่มเติม' : 'See more'}
            </a>
          }
        >
          <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            {products.slice(0, 4).map((p) => (
              <ProductCard key={p.id} p={p} />
            ))}
            {products.length === 0 && <p className="text-slate-400">{t('products.empty')}</p>}
          </div>
        </Section>

        <Section title={t('section.latest')}>
          <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {products.slice(4, 10).map((p) => (
              <ProductCard key={p.id} p={p} />
            ))}
            {products.length <= 4 && <p className="text-slate-400">{t('products.empty')}</p>}
          </div>
        </Section>

        <Section title={t('section.hotDeals')}>
          <div className="grid gap-4 md:grid-cols-3">
            {posts.map((post) => (
              <article
                key={post.id}
                className="flex flex-col rounded-3xl border border-indigo-500/20 bg-gradient-to-br from-indigo-900/30 via-indigo-900/10 to-transparent p-6 hover:border-indigo-400/60 transition"
              >
                <span className="text-xs uppercase tracking-[0.3em] text-indigo-300">{t('section.dealBadge')}</span>
                <h3 className="mt-3 text-lg font-semibold text-white line-clamp-2">{post.title}</h3>
                <p className="mt-2 text-sm text-slate-300 line-clamp-3">{post.content}</p>
                <button
                  type="button"
                  className="mt-auto self-start rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs text-slate-200 hover:border-indigo-400 transition"
                >
                  {t('posts.readMore')}
                </button>
              </article>
            ))}
            {posts.length === 0 && <p className="text-slate-400">{t('posts.empty')}</p>}
          </div>
        </Section>

        <Section title={t('section.categories')}>
          <div className="flex flex-wrap gap-3">
            {featuredCategories.map((category) => (
              <button
                key={category.th}
                type="button"
                className="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-200 hover:border-indigo-400 hover:text-white transition"
              >
                {category[language]}
              </button>
            ))}
          </div>
        </Section>
      </div>
    </Layout>
  )
}

export async function getServerSideProps() {
  const apiBase = process.env.PHP_API_BASE || 'http://localhost:8000'
  const [productsRes, postsRes] = await Promise.all([
    fetch(`${apiBase}/api/products.php`).then(r => r.json()).catch(() => ({ ok: false, data: [] })),
    fetch(`${apiBase}/api/posts.php`).then(r => r.json()).catch(() => ({ ok: false, data: [] })),
  ])

  return {
    props: {
      products: productsRes.ok ? productsRes.data : [],
      posts: postsRes.ok ? postsRes.data : [],
      apiBase,
    },
  }
}
