import { ReactNode } from 'react'

export default function Section({ title, action, children }: { title: string, action?: ReactNode, children: ReactNode }) {
  return (
    <section className="mt-8">
      <div className="flex items-center justify-between mb-3">
        <h2 className="text-lg font-semibold">{title}</h2>
        {action}
      </div>
      {children}
    </section>
  )
}


