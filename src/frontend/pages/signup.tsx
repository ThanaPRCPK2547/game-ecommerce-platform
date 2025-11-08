import { useState } from 'react'
import Layout from '../components/Layout'
import Link from 'next/link'

export default function SignUpPage() {
  const [fullName, setFullName] = useState('')
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')

  function onSubmit(e: React.FormEvent) {
    e.preventDefault()
    // TODO: เชื่อมต่อ PHP API /api/auth/signup (ภายหลัง)
    alert('เดโม: ยังไม่ได้เชื่อมต่อ API')
  }

  return (
    <Layout>
      <div className="max-w-md mx-auto rounded-2xl border border-white/10 bg-white/5 p-6">
        <h1 className="text-2xl font-semibold mb-4">Sign Up</h1>
        <form onSubmit={onSubmit} className="space-y-3">
          <input value={fullName} onChange={e=>setFullName(e.target.value)} placeholder="username" className="w-full px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100" required />
          <input value={email} onChange={e=>setEmail(e.target.value)} placeholder="email" type="email" className="w-full px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100" required />
          <input value={password} onChange={e=>setPassword(e.target.value)} placeholder="password" type="password" className="w-full px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100" required />
          <button type="submit" className="w-full px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium">สมัครสมาชิก</button>
        </form>
        <p className="text-sm text-slate-400 mt-3">มีบัญชีแล้ว? <Link href="/login" className="text-indigo-300 hover:text-indigo-200">เข้าสู่ระบบ</Link></p>
      </div>
    </Layout>
  )
}


