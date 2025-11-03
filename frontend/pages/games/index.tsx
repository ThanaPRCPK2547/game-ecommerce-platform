import Link from 'next/link'
import Layout from '../../components/Layout'
import { useEffect, useState } from 'react'
import { useLanguage } from '../../context/LanguageContext'

type Category = { id: number, name: string, description?: string }

export default function GamesPage() {
  const apiBase = process.env.NEXT_PUBLIC_PHP_API_BASE || process.env.PHP_API_BASE || 'http://localhost:8000'
  const [categories, setCategories] = useState<Category[]>([])
  const { t } = useLanguage()

  useEffect(() => {
    fetch(`${apiBase}/api/categories.php`).then(r => r.json()).then(j => {
      if (j.ok) setCategories(j.data)
    }).catch(() => {})
  }, [apiBase])

  return (
    <Layout>
      <h1 className="text-2xl font-semibold mb-4">{t('games.title')}</h1>
      <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {categories.map(c => (
          <Link key={c.id} href={`/games/${c.id}`} className="group block rounded-2xl border border-white/10 bg-white/5 p-5 hover:border-indigo-400/50 transition">
            <div className="flex items-center justify-between">
              <p className="text-slate-100 font-medium">{c.name}</p>
              <span className="text-xs text-indigo-300 group-hover:text-indigo-200">{t('games.viewProducts')}</span>
            </div>
            {c.description && <p className="text-sm text-slate-400 mt-2 line-clamp-2">{c.description}</p>}
          </Link>
        ))}
        {categories.length === 0 && <p className="text-slate-400">{t('games.empty')}</p>}
      </div>
    </Layout>
  )
}

