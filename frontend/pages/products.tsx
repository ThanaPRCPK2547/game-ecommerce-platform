import { useEffect, useMemo, useState } from 'react'
import Layout from '../components/Layout'
import ProductCard from '../components/ProductCard'
import { useLanguage } from '../context/LanguageContext'

type Category = { id: number, name: string }
type Product = {
  id: number
  name: string
  description: string
  price: number
  stock_quantity: number
  category_id?: number
  category_name?: string
}

export default function ProductsPage() {
  const apiBase = process.env.NEXT_PUBLIC_PHP_API_BASE || process.env.PHP_API_BASE || 'http://localhost:8000'
  const [categories, setCategories] = useState<Category[]>([])
  const [products, setProducts] = useState<Product[]>([])
  const [categoryId, setCategoryId] = useState<number | 'all'>('all')
  const [query, setQuery] = useState('')
  const { t } = useLanguage()

  useEffect(() => {
    fetch(`${apiBase}/api/categories.php`).then(r => r.json()).then(j => {
      if (j.ok) setCategories(j.data)
    }).catch(() => {})
    fetch(`${apiBase}/api/products.php?limit=100`).then(r => r.json()).then(j => {
      if (j.ok) setProducts(j.data)
    }).catch(() => {})
  }, [apiBase])

  const filtered = useMemo(() => {
    return products.filter(p => {
      const byCat = categoryId === 'all' || String(p.category_id) === String(categoryId)
      const byQ = !query || [p.name, p.description, p.category_name].join(' ').toLowerCase().includes(query.toLowerCase())
      return byCat && byQ
    })
  }, [products, categoryId, query])

  return (
    <Layout>
      <section className="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <h1 className="text-2xl font-semibold">{t('products.title')}</h1>
        <div className="flex gap-2">
          <input
            placeholder={t('products.searchPlaceholder')}
            className="px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
          />
          <select
            className="px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100 focus:outline-none"
            value={categoryId}
            onChange={(e) => setCategoryId(e.target.value === 'all' ? 'all' : Number(e.target.value))}
          >
            <option value="all">{t('products.allCategories')}</option>
            {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
          </select>
        </div>
      </section>

      <section className="mt-6 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {filtered.map((p) => <ProductCard key={p.id} p={p} />)}
        {filtered.length === 0 && <p className="text-slate-400">{t('products.empty')}</p>}
      </section>
    </Layout>
  )
}

