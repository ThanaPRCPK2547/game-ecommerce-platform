export default function BrandLogo({ size = 22 }: { size?: number }) {
  return (
    <span
      className="font-semibold tracking-wide"
      style={{ fontSize: size }}
    >
      <span className="bg-gradient-to-r from-indigo-400 via-fuchsia-400 to-rose-400 bg-clip-text text-transparent">
        GAME
      </span>
      <span className="text-slate-100">STORE</span>
      <span className="ml-1 px-1.5 py-0.5 rounded-md text-[10px] align-middle bg-white/10 text-fuchsia-300 border border-white/10">
        TH
      </span>
    </span>
  )
}


