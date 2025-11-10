import { useEffect, useState } from 'react'
import Layout from '../components/Layout'
import { CartItem, clearCart, getCart, total } from '../utils/cart'
import Link from 'next/link'

export default function CheckoutPage() {
  const [items, setItems] = useState<CartItem[]>([])
  const [name, setName] = useState('')
  const [email, setEmail] = useState('')
  const [address, setAddress] = useState('')
  const [payment, setPayment] = useState('COD')
  const [success, setSuccess] = useState(false)

  useEffect(() => { setItems(getCart()) }, [])

  function placeOrder(e: React.FormEvent) {
    e.preventDefault()
    // เดโม: สรุปคำสั่งซื้อโดยยังไม่เชื่อม Payment จริง
    setSuccess(true)
    clearCart()
  }

  if (success) {
    return (
      <Layout>
        <div className="rounded-2xl border border-white/10 bg-white/5 p-6 text-center">
          <h1 className="text-2xl font-semibold mb-2">สั่งซื้อสำเร็จ</h1>
          <p className="text-slate-300">เราได้บันทึกคำสั่งซื้อของคุณแล้ว</p>
          <Link href="/" className="inline-block mt-4 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium">กลับหน้าแรก</Link>
        </div>
      </Layout>
    )
  }

  const sum = total(items)

  return (
    <Layout>
      <h1 className="text-2xl font-semibold mb-4">ชำระเงิน</h1>
      <div className="grid md:grid-cols-3 gap-6">
        <form onSubmit={placeOrder} className="md:col-span-2 space-y-4">
          <div className="rounded-2xl border border-white/10 bg-white/5 p-4">
            <h2 className="font-medium mb-3">ข้อมูลผู้รับ</h2>
            <div className="grid md:grid-cols-2 gap-3">
              <input value={name} onChange={e => setName(e.target.value)} placeholder="ชื่อ-นามสกุล" className="px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100" required />
              <input value={email} onChange={e => setEmail(e.target.value)} placeholder="อีเมล" type="email" className="px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100" required />
            </div>
            <textarea value={address} onChange={e => setAddress(e.target.value)} placeholder="ที่อยู่จัดส่ง (จำเป็นเฉพาะสินค้ากายภาพ)" className="mt-3 w-full px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100" rows={3} />
          </div>
          <div className="rounded-2xl border border-white/10 bg-white/5 p-4">
            <h2 className="font-medium mb-3">วิธีชำระเงิน</h2>
            <select value={payment} onChange={e => setPayment(e.target.value)} className="px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100">
              <option value="COD">ชำระปลายทาง</option>
              <option value="BANK">โอนผ่านธนาคาร</option>
              <option value="CARD">บัตรเครดิต/เดบิต</option>
            </select>
          </div>
          <button type="submit" className={`px-5 py-2.5 rounded-xl text-white text-sm font-medium ${items.length ? 'bg-indigo-600 hover:bg-indigo-500' : 'bg-white/10 pointer-events-none'}`}>ยืนยันสั่งซื้อ</button>
        </form>
        <div className="rounded-2xl border border-white/10 bg-white/5 p-4 h-max">
          <h2 className="font-medium mb-3">สรุปรายการ</h2>
          <div className="space-y-2">
            {items.map((i, idx) => (
              <div key={idx} className="flex items-center justify-between text-sm">
                <div>
                  <p className="text-slate-200">{i.name} × {i.quantity}</p>
                  {i.player_game_id && <p className="text-xs text-slate-400">Player ID: {i.player_game_id}</p>}
                </div>
                <span className="text-slate-300">฿{(i.price * i.quantity).toFixed(2)}</span>
              </div>
            ))}
            {items.length === 0 && <p className="text-slate-400">ตะกร้าของคุณว่าง</p>}
          </div>
          <div className="mt-3 border-t border-white/10 pt-3 flex items-center justify-between">
            <span className="text-slate-300">ยอดรวม</span>
            <span className="text-indigo-300 font-semibold">฿{sum.toFixed(2)}</span>
          </div>
        </div>
      </div>
    </Layout>
  )
}


