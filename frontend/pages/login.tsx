import { useState } from 'react'
import Layout from '../components/Layout'
import Link from 'next/link'
import GoogleIcon from '../components/GoogleIcon'

export default function LoginPage() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const googleUrl = process.env.NEXT_PUBLIC_GOOGLE_OAUTH_URL || process.env.GOOGLE_OAUTH_URL || '#'
  const facebookUrl = process.env.NEXT_PUBLIC_FACEBOOK_OAUTH_URL || process.env.FACEBOOK_OAUTH_URL || '#'

  function onSubmit(e: React.FormEvent) {
    e.preventDefault()
    // TODO: เชื่อมต่อ PHP API /api/auth/login (ภายหลัง)
    alert('เดโม: ยังไม่ได้เชื่อมต่อ API')
  }

  return (
    <Layout>
      <div className="max-w-md mx-auto rounded-2xl border border-white/10 bg-white/5 p-6">
        <h1 className="text-2xl font-semibold mb-4">Login</h1>
        <form onSubmit={onSubmit} className="space-y-3">
          <input value={email} onChange={e=>setEmail(e.target.value)} placeholder="email" type="email" className="w-full px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100" required />
          <input value={password} onChange={e=>setPassword(e.target.value)} placeholder="password" type="password" className="w-full px-3 py-2 rounded-xl bg-white/10 border border-white/10 text-slate-100" required />
          <button type="submit" className="w-full px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium">เข้าสู่ระบบ</button>
        </form>
        <div className="my-4 flex items-center gap-3 text-slate-400">
          <div className="flex-1 h-px bg-white/10" />
          <span className="text-xs">หรือ</span>
          <div className="flex-1 h-px bg-white/10" />
        </div>
        <div className="space-y-2">
          <a href={googleUrl} className="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white text-slate-800 text-sm font-medium hover:opacity-90">
            <img src="https://cdn.iconscout.com/icon/free/png-256/free-google-icon-svg-download-png-189824.png" alt="Google" width={18} height={18} />
            Login with Google
          </a>
          <a href={facebookUrl} className="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#1877F2] text-white text-sm font-medium hover:opacity-90">
            <svg viewBox="0 0 24 24" width="18" height="18" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M22.675 0h-21.35C.595 0 0 .595 0 1.326v21.348C0 23.405.595 24 1.326 24h11.495v-9.294H9.691V11.01h3.13V8.41c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.796.716-1.796 1.766v2.317h3.587l-.467 3.696h-3.12V24h6.116C23.405 24 24 23.405 24 22.674V1.326C24 .595 23.405 0 22.675 0z"/></svg>
            Login with Facebook
          </a>
        </div>
        <p className="text-sm text-slate-400 mt-3">ยังไม่มีบัญชี? <Link href="/signup" className="text-indigo-300 hover:text-indigo-200">สมัครสมาชิก</Link></p>
      </div>
    </Layout>
  )
}


