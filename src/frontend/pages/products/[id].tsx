import { useRouter } from 'next/router'
import { useEffect, useState } from 'react'
import Layout from '../../components/Layout'
import { addToCart } from '../../utils/cart'
import { useLanguage } from '../../context/LanguageContext'

type Product = {
  id: number
  name: string
  description: string
  price: number
  category_name?: string
  product_type?: 'PHYSICAL' | 'DIGITAL_KEY' | 'TOP_UP'
}

export default function ProductDetailPage() {
  const apiBase = process.env.NEXT_PUBLIC_PHP_API_BASE || process.env.PHP_API_BASE || 'http://localhost:8000'
  const router = useRouter()
  const { id } = router.query
  const [product, setProduct] = useState<Product | null>(null)
  const [qty, setQty] = useState(1)
  const [playerGameId, setPlayerGameId] = useState('')
  const { t } = useLanguage()

  useEffect(() => {
    if (!id) return
    fetch(`${apiBase}/api/product.php?id=${id}`).then(r => r.json()).then(j => {
      if (j.ok) setProduct(j.data)
    }).catch(() => {})
  }, [apiBase, id])

  function handleAdd() {
    if (!product) return
    const needsPlayerId = product.product_type === 'TOP_UP'
    if (needsPlayerId && !playerGameId.trim()) {
      alert(t('product.detail.requiresId'))
      return
    }
    addToCart({
      id: product.id,
      name: product.name,
      price: Number(product.price),
      quantity: qty,
      category_name: product.category_name,
      player_game_id: needsPlayerId ? playerGameId : null,
    })
    router.push('/cart')
  }

  if (!product) return (
    <Layout>
      <p className="text-slate-400">{t('product.detail.loading')}</p>
    </Layout>
  )

  const type = product.product_type || 'PHYSICAL'

  return (
    <Layout>
      <div className="grid md:grid-cols-2 gap-6">
        <div className="rounded-2xl border border-white/10 bg-white/5 aspect-[16/10]" />
        <div className="space-y-4">
          <h1 className="text-2xl font-semibold text-slate-100">{product.name}</h1>
          <p className="text-sm text-slate-400">{t('product.detail.categoryLabel')}: {product.category_name || '-'}</p>
          <p className="text-slate-300 leading-relaxed">{product.description}</p>
          <div className="text-xl text-indigo-300 font-semibold">฿{Number(product.price).toFixed(2)}</div>
          <div className="flex gap-2 items-center">
            <input
              type="number"
              min={1}
              value={qty}
              onChange={(e) => setQty(Math.max(1, Number(e.target.value)))}
              className="w-24 px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100"
            />
            {type === 'TOP_UP' && (
              <input
                placeholder="Player Game ID"
                value={playerGameId}
                onChange={(e) => setPlayerGameId(e.target.value)}
                className="flex-1 px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100"
              />
            )}
            <button onClick={handleAdd} className="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium">
              {t('product.detail.addToCart')}
            </button>
          </div>
          <p className="text-xs text-slate-500">{t('product.detail.typeLabel')}: {type}</p>
        </div>
      </div>
    </Layout>
  )
}

