# Frontend (Next.js + Tailwind)

## Setup

Inside `frontend`:

```bash
npm install
# or: pnpm install / yarn
```

## Development

- Start PHP backend (port 8000):
```bash
php -S localhost:8000 -t /Users/thanakorn/Desktop/mini_project
```

- Start Next.js (port 3000):
```bash
npm run dev
```

- Open: http://localhost:3000

If your PHP runs on a different URL, set env:
```bash
export PHP_API_BASE=http://localhost:8000
npm run dev
```

## Pages
- `/` หน้าโฮมธีมเกมมิ่ง พร้อมสินค้าแนะนำและบทความ
- `/products` หน้าแสดงสินค้าทั้งหมด + ค้นหา/กรองหมวดหมู่

## Tailwind
- Config in `tailwind.config.ts`
- Styles in `styles/globals.css`


