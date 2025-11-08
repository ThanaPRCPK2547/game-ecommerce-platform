import { useRouter } from 'next/router'
import { useEffect, useMemo, useState } from 'react'
import Layout from '../../components/Layout'
import ProductCard from '../../components/ProductCard'
import { useLanguage } from '../../context/LanguageContext'

type Product = {
  id: number
  name: string
  description: string
  price: number
  stock_quantity: number
  category_id?: number
  category_name?: string
}

export default function GameDetailPage() {
  const apiBase = process.env.NEXT_PUBLIC_PHP_API_BASE || process.env.PHP_API_BASE || 'http://localhost:8000'
  const router = useRouter()
  const { id } = router.query
  const [products, setProducts] = useState<Product[]>([])
  const [categoryName, setCategoryName] = useState('')
  const { t } = useLanguage()

  useEffect(() => {
    if (!id) return
    fetch(`${apiBase}/api/products.php?limit=100`).then(r => r.json()).then(j => {
      if (j.ok) {
        setProducts(j.data)
      }
    }).catch(() => {})
    fetch(`${apiBase}/api/categories.php`).then(r => r.json()).then(j => {
      if (j.ok) {
        const found = j.data.find((c: any) => String(c.id) === String(id))
        setCategoryName(found?.name || '')
      }
    }).catch(() => {})
  }, [apiBase, id])

  const filtered = useMemo(() => {
    return products.filter(p => String(p.category_id) === String(id))
  }, [products, id])

  return (
    <Layout>
      <h1 className="text-2xl font-semibold mb-4">{categoryName || t('games.title')}</h1>
      <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {filtered.map(p => <ProductCard key={p.id} p={p} />)}
        {filtered.length === 0 && <p className="text-slate-400">{t('games.detail.empty')}</p>}
      </div>
    </Layout>
  )
}

