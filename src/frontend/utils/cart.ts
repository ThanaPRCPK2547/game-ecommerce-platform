export type CartItem = {
  id: number
  name: string
  price: number
  quantity: number
  category_name?: string
  player_game_id?: string | null
}

const KEY = 'cart.v1'

function read(): CartItem[] {
  if (typeof window === 'undefined') return []
  try {
    const raw = localStorage.getItem(KEY)
    return raw ? JSON.parse(raw) : []
  } catch {
    return []
  }
}

function write(items: CartItem[]) {
  if (typeof window === 'undefined') return
  localStorage.setItem(KEY, JSON.stringify(items))
}

export function getCart() {
  return read()
}

export function addToCart(item: CartItem) {
  const items = read()
  const idx = items.findIndex(i => i.id === item.id && (i.player_game_id || '') === (item.player_game_id || ''))
  if (idx >= 0) {
    items[idx].quantity += item.quantity
  } else {
    items.push(item)
  }
  write(items)
}

export function updateQuantity(id: number, quantity: number, player_game_id?: string | null) {
  const items = read().map(i => {
    if (i.id === id && (i.player_game_id || '') === (player_game_id || '')) {
      return { ...i, quantity: Math.max(1, quantity) }
    }
    return i
  })
  write(items)
}

export function removeItem(id: number, player_game_id?: string | null) {
  const items = read().filter(i => !(i.id === id && (i.player_game_id || '') === (player_game_id || '')))
  write(items)
}

export function clearCart() { write([]) }

export function total(items?: CartItem[]) {
  const arr = items || read()
  return arr.reduce((sum, i) => sum + i.price * i.quantity, 0)
}


