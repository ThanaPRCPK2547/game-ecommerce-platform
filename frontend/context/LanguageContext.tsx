import { createContext, useContext, useMemo, useState, type ReactNode } from 'react'

export type Language = 'th' | 'en'

export type TranslationKey =
  | 'nav.store'
  | 'nav.discover'
  | 'nav.browse'
  | 'nav.news'
  | 'nav.login'
  | 'nav.signup'
  | 'nav.cart'
  | 'nav.download'
  | 'hero.pill'
  | 'hero.title'
  | 'hero.description'
  | 'hero.cta.primary'
  | 'hero.cta.secondary'
  | 'search.placeholder'
  | 'search.trending'
  | 'section.featured'
  | 'section.latest'
  | 'section.hotDeals'
  | 'section.categories'
  | 'section.dealBadge'
  | 'products.empty'
  | 'products.title'
  | 'products.searchPlaceholder'
  | 'products.allCategories'
  | 'posts.empty'
  | 'posts.readMore'
  | 'games.title'
  | 'games.viewProducts'
  | 'games.empty'
  | 'games.detail.empty'
  | 'product.category'
  | 'product.stock'
  | 'product.addToCart'
  | 'product.ready'
  | 'product.highlight'
  | 'product.detail.loading'
  | 'product.detail.categoryLabel'
  | 'product.detail.addToCart'
  | 'product.detail.typeLabel'
  | 'product.detail.requiresId'
  | 'footer.about'
  | 'footer.contact'
  | 'footer.careers'
  | 'footer.press'
  | 'footer.support'
  | 'footer.legal'
  | 'footer.copyright'
  | 'footer.backToTop'

type TranslationDictionary = Record<TranslationKey, string>

const translations: Record<Language, TranslationDictionary> = {
  th: {
    'nav.store': 'สโตร์',
    'nav.discover': 'ค้นหา',
    'nav.browse': 'เรียกดู',
    'nav.news': 'ข่าว',
    'nav.login': 'เข้าสู่ระบบ',
    'nav.signup': 'สมัครสมาชิก',
    'nav.cart': 'ตะกร้า',
    'nav.download': 'ดาวน์โหลด',
    'hero.pill': 'อัปเดตล่าสุด',
    'hero.title': 'ค้นพบเกมใหม่และดีลพิเศษทุกสัปดาห์',
    'hero.description':
      'สำรวจเกมยอดนิยม เกมใหม่ล่าสุด และส่วนลดสุดคุ้มที่คัดสรรมาเพื่อคุณ พร้อมระบบจัดการไลบรารีแบบครบวงจร',
    'hero.cta.primary': 'สั่งซื้อล่วงหน้า',
    'hero.cta.secondary': 'ดูข้อมูลเพิ่ม',
    'search.placeholder': 'ค้นหาเกมหรือสินค้า',
    'search.trending': 'กำลังมาแรง',
    'section.featured': 'เกมฟรีประจำสัปดาห์',
    'section.latest': 'เกมออกใหม่ยอดนิยม',
    'section.hotDeals': 'ดีลแรงห้ามพลาด',
    'section.categories': 'หมวดหมู่แนะนำ',
    'section.dealBadge': 'ดีล',
    'products.empty': 'ยังไม่มีสินค้าให้แสดง',
    'products.title': 'สินค้าเกม',
    'products.searchPlaceholder': 'ค้นหาโค้ดเกม/บัตรเติมเงิน...',
    'products.allCategories': 'ทุกหมวดหมู่',
    'posts.empty': 'ยังไม่มีข่าวหรือบทความ',
    'posts.readMore': 'อ่านเพิ่มเติม',
    'games.title': 'เกมทั้งหมด',
    'games.viewProducts': 'ดูสินค้า →',
    'games.empty': 'ยังไม่มีเกม/หมวดหมู่',
    'games.detail.empty': 'ยังไม่มีสินค้าในเกมนี้',
    'product.category': 'หมวดหมู่',
    'product.stock': 'คงเหลือ',
    'product.addToCart': 'เพิ่มลงตะกร้า',
    'product.ready': 'พร้อมเล่น',
    'product.highlight': 'แนะนำ',
    'product.detail.loading': 'กำลังโหลด...',
    'product.detail.categoryLabel': 'หมวดหมู่',
    'product.detail.addToCart': 'เพิ่มลงตะกร้า',
    'product.detail.typeLabel': 'ประเภทสินค้า',
    'product.detail.requiresId': 'กรุณากรอก Player Game ID',
    'footer.about': 'เกี่ยวกับเรา',
    'footer.contact': 'ติดต่อ',
    'footer.careers': 'ร่วมงานกับเรา',
    'footer.press': 'ข่าวประชาสัมพันธ์',
    'footer.support': 'ศูนย์ช่วยเหลือ',
    'footer.legal': 'ข้อกำหนดและความเป็นส่วนตัว',
    'footer.copyright': '© 2024 GameStore. สงวนลิขสิทธิ์',
    'footer.backToTop': 'กลับไปด้านบน',
  },
  en: {
    'nav.store': 'Store',
    'nav.discover': 'Discover',
    'nav.browse': 'Browse',
    'nav.news': 'News',
    'nav.login': 'Login',
    'nav.signup': 'Sign Up',
    'nav.cart': 'Cart',
    'nav.download': 'Download',
    'hero.pill': 'Fresh Update',
    'hero.title': 'Discover new games and weekly exclusive deals',
    'hero.description':
      'Explore best sellers, upcoming releases, and curated discounts with a seamless library experience across devices.',
    'hero.cta.primary': 'Pre-order now',
    'hero.cta.secondary': 'Learn more',
    'search.placeholder': 'Search games or products',
    'search.trending': 'Trending topics',
    'section.featured': 'Weekly Free Games',
    'section.latest': 'Popular New Releases',
    'section.hotDeals': 'Hot Deals You Can’t Miss',
    'section.categories': 'Featured Categories',
    'section.dealBadge': 'Deal',
    'products.empty': 'No products to show yet',
    'products.title': 'Game Products',
    'products.searchPlaceholder': 'Search game codes or top-up cards...',
    'products.allCategories': 'All categories',
    'posts.empty': 'No news or posts available',
    'posts.readMore': 'View details',
    'games.title': 'All Games',
    'games.viewProducts': 'View products →',
    'games.empty': 'No games or categories yet',
    'games.detail.empty': 'No products for this game yet',
    'product.category': 'Category',
    'product.stock': 'In stock',
    'product.addToCart': 'Add to Cart',
    'product.ready': 'Ready to play',
    'product.highlight': 'Featured',
    'product.detail.loading': 'Loading...',
    'product.detail.categoryLabel': 'Category',
    'product.detail.addToCart': 'Add to Cart',
    'product.detail.typeLabel': 'Product type',
    'product.detail.requiresId': 'Please enter Player Game ID',
    'footer.about': 'About',
    'footer.contact': 'Contact',
    'footer.careers': 'Careers',
    'footer.press': 'Press',
    'footer.support': 'Support',
    'footer.legal': 'Legal & Privacy',
    'footer.copyright': '© 2024 GameStore. All rights reserved.',
    'footer.backToTop': 'Back to top',
  },
}

type LanguageContextValue = {
  language: Language
  setLanguage: (language: Language) => void
  t: (key: TranslationKey) => string
}

const LanguageContext = createContext<LanguageContextValue | undefined>(undefined)

export function LanguageProvider({ children }: { children: ReactNode }) {
  const [language, setLanguage] = useState<Language>('th')

  const value = useMemo(
    () => ({
      language,
      setLanguage,
      t: (key: TranslationKey) => translations[language][key] ?? key,
    }),
    [language],
  )

  return <LanguageContext.Provider value={value}>{children}</LanguageContext.Provider>
}

export function useLanguage() {
  const ctx = useContext(LanguageContext)
  if (!ctx) {
    throw new Error('useLanguage must be used within LanguageProvider')
  }
  return ctx
}
