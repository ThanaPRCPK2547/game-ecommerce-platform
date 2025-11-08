import { useLanguage } from '../context/LanguageContext'

type Product = {
  id: number
  name: string
  description: string
  price: number
  stock_quantity: number
  category_name?: string
  image_url?: string
}

export default function ProductCard({ p }: { p: Product }) {
  const { t, language } = useLanguage()
  const priceLabel = language === 'th' ? `฿${p.price.toFixed(2)}` : `THB ${p.price.toFixed(2)}`

  return (
    <div className="group overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-b from-white/10 via-white/5 to-white/0 backdrop-blur transition hover:border-indigo-400/60">
      <div className="relative aspect-[4/5] overflow-hidden">
        <div
          className="h-full w-full bg-gradient-to-tr from-indigo-900/70 to-purple-900/50"
          style={{
            backgroundImage: p.image_url ? `url(${p.image_url})` : undefined,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
          }}
        />
        <span className="absolute left-3 top-3 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs uppercase tracking-[0.3em] text-indigo-100">
          {t('product.highlight')}
        </span>
        <span className="absolute right-3 top-3 rounded-full bg-indigo-500/90 px-2 py-1 text-xs font-semibold text-white shadow-lg">
          {priceLabel}
        </span>
        <svg
          className="absolute inset-x-0 bottom-0 -mb-6 mx-auto h-24 w-24 text-white/40 opacity-0 transition group-hover:opacity-100"
          viewBox="0 0 24 24"
          fill="currentColor"
        >
          <path d="M7 6h10l4 7-4 7H7L3 13l4-7zm2 2-3 5 3 5h6l3-5-3-5H9z" />
        </svg>
      </div>

      <div className="space-y-3 p-5">
        <div className="flex items-start justify-between gap-2">
          <div>
            <h3 className="text-base font-semibold text-white line-clamp-1">{p.name}</h3>
            <p className="text-xs uppercase tracking-[0.3em] text-indigo-200">
              {p.category_name || '-'}
            </p>
          </div>
          <span className="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-300">
            {t('product.ready')}
          </span>
        </div>
        <p className="text-sm text-slate-300 line-clamp-2">{p.description}</p>
        <div className="flex items-center justify-between text-xs text-slate-400 pt-1">
          <span>
            {t('product.category')}: {p.category_name || '-'}
          </span>
          <span>
            {t('product.stock')}: {p.stock_quantity}
          </span>
        </div>
        <button className="w-full rounded-full bg-indigo-600 py-2 text-sm font-semibold text-white transition hover:bg-indigo-500">
          {t('product.addToCart')}
        </button>
      </div>
    </div>
  )
}
