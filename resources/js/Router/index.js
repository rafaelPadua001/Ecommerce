import {createRouter, createWebHistory} from 'vue-router';

 /* 
    importar os componentes a partir daqui
*/ 
import Home from '../Pages/Frontend/Main.vue'
import Login from '../Pages/Frontend/Auth/Login.vue'
import Register from '../Pages/Frontend/Auth/Register.vue'
import Dashboard from '../Pages/Frontend/Auth/Dashboard.vue'
import Profile from '../Pages/Frontend/Profile/Profile.vue'
import SubcategoriesAll from '../Pages/Frontend/Subcategories/Subcategories.vue'
import Buy from '../Pages/Frontend/Checkout/Buy.vue'
import Cart from '../Pages/Frontend/Cart/Cart.vue'
import Checkout from '../Pages/Frontend/Checkout/Checkout.vue'
import Orders from '../Pages/Frontend/Orders/Orders.vue'
import AdminLogin from '../Pages/Backend/Auth/Login.vue'
import AdminDashboard from '../Pages/Backend/Auth/Dashboard.vue'
import Categories from '../Pages/Backend/Categories/Categories.vue'
import Subcategories from '../Pages/Backend/Subcategories/Subcategories.vue'
import Products from '../Pages/Backend/Products/Products.vue'
import ProductSeo from '../Pages/Backend/Products/Seo/Seo.vue'
import Stock from '../Pages/Backend/Products/Stock/Stock.vue'
import Images from '../Pages/Backend/Galerie/Images/Images.vue'
import Videos from '../Pages/Backend/Galerie/Videos/Video.vue'
import allOrders from '../Pages/Backend/Orders/Orders.vue'
import Coupons from '../Pages/Backend/Coupons/Coupons.vue'
import Delivery from '../Pages/Backend/Delivery/Delivery.vue'

const routes = [{
    path: '/',
    name: 'home',
    component: Home
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/profileCustomer/:id',
        name: 'profile',
        component: Profile
    },
    {
        path: '/admin',
        name: 'admin',
        component: AdminLogin
    },
    
    {
        path: '/adminDashboard',
        name: 'adminDashboard',
        component: AdminDashboard
    },
    {
        path: '/categories',
        name: 'categories',
        component: Categories
    },
    {
        path: '/subcategories',
        name: 'subcategories',
        component: Subcategories
    },
    {
        path: '/subcategories/all/:category_id',
        name: 'sucategories.all',
        component: SubcategoriesAll
    },
    {
        path: '/buy/product',
        name: 'buy',
        component: Buy

    },
    {
        path: '/carts',
        name: 'carts',
        component: Cart
    },
    {
        path: '/item/buy',
        name: 'item.buy',
        component: Buy,
        props: route => ({ shippment: JSON.parse(route.query.shippment || 'null'), zip_code: route.query.zip_code })
  
        
    },
    {
        path: '/carts/buy/:id',
        name: 'carts.buy',
        component: Checkout,
    },
    {
        path: '/products',
        name: 'products',
        component: Products,
    },
    {
        path: '/product_seo',
        name: 'product_seo',
        component: ProductSeo
    },
    {
        path: '/stock',
        name: 'stock',
        component: Stock
    },
    {
        path: '/orders',
        name: 'orders',
        component: Orders
    },
    {
        path: '/images',
        name: 'images',
        component: Images
    },
    {
        path: '/videos',
        name: 'videos',
        component: Videos
    },
    {
        path: '/allOrders',
        name: 'allOrders',
        component: allOrders
    },
    {
        path: '/coupons',
        name: 'coupons',
        component: Coupons
    },
    {
        path: '/delivery',
        name: 'delivery',
        component: Delivery
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

export default router;