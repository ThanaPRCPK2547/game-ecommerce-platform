import { useEffect, useState } from 'react'
import Layout from '../components/Layout'
import { CartItem, getCart, removeItem, total, updateQuantity } from '../utils/cart'
import Link from 'next/link'

export default function CartPage() {
  const [items, setItems] = useState<CartItem[]>([])

  useEffect(() => { setItems(getCart()) }, [])

  function changeQty(i: CartItem, q: number) {
    updateQuantity(i.id, q, i.player_game_id || null)
    setItems(getCart())
  }

  function remove(i: CartItem) {
    removeItem(i.id, i.player_game_id || null)
    setItems(getCart())
  }

  const sum = total(items)

  return (
    <Layout>
      <h1 className="text-2xl font-semibold mb-4">ตะกร้าสินค้า</h1>
      <div className="grid md:grid-cols-3 gap-6">
        <div className="md:col-span-2 space-y-3">
          {items.map((i, idx) => (
            <div key={idx} className="rounded-2xl border border-white/10 bg-white/5 p-4">
              <div className="flex items-center justify-between gap-3">
                <div>
                  <p className="text-slate-100 font-medium">{i.name}</p>
                  <p className="text-xs text-slate-400">หมวดหมู่: {i.category_name || '-'}{i.player_game_id ? ` • Player ID: ${i.player_game_id}` : ''}</p>
                </div>
                <button onClick={() => remove(i)} className="text-sm text-pink-300 hover:text-pink-200">ลบ</button>
              </div>
              <div className="mt-3 flex items-center justify-between">
                <div className="text-indigo-300 font-semibold">฿{(i.price * i.quantity).toFixed(2)}</div>
                <input
                  type="number"
                  min={1}
                  value={i.quantity}
                  onChange={(e) => changeQty(i, Math.max(1, Number(e.target.value)))}
                  className="w-24 px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100"
                />
              </div>
            </div>
          ))}
          {items.length === 0 && <p className="text-slate-400">ตะกร้าของคุณว่างเปล่า</p>}
        </div>
        <div className="rounded-2xl border border-white/10 bg-white/5 p-4 h-max">
          <p className="text-slate-300">ยอดรวม</p>
          <p className="text-2xl text-indigo-300 font-semibold">฿{sum.toFixed(2)}</p>
          <Link href="/checkout" className={`block text-center mt-3 px-5 py-2.5 rounded-xl text-white text-sm font-medium ${items.length ? 'bg-indigo-600 hover:bg-indigo-500' : 'bg-white/10 pointer-events-none'}`}>ไปชำระเงิน</Link>
        </div>
      </div>
    </Layout>
  )
}


