import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Register from "../views/Register.vue";
import Login from "../views/Login.vue";
import Admin from "../views/admin/Admin.vue";
import Users from "../views/admin/Users.vue";
import Categories from "../views/admin/Categories.vue";
import Profile from "../views/Profile.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "home",
            component: Home,
        },
        {
            path: "/register",
            name: "register",
            component: Register,
        },
        {
            path: "/login",
            name: "login",
            component: Login,
        },
        {
            path: "/admin",
            name: "admin",
            component: Admin,
        },
        {
            path: "/profile",
            name: "profile",
            component: Profile,
        },
        {
            path: '/users',
            name: 'Users',
            component: Users,
          },
          {
            path: '/categories',
            name: 'Categories',
            component: Categories,
          },
    ],
});

// // Navigacijski stražar za provjeru autentifikacije
// router.beforeEach((to, from, next) => {
//     const isAuthenticated = !!localStorage.getItem('token'); // Provjerite da li je korisnik autentificiran

//     if (to.name === 'admin' && !isAuthenticated) {
//         // Ako korisnik pokušava pristupiti admin ruti i nije autentificiran, preusmjerite ga na login rutu
//         next({ name: 'login' });
//     } else {
//         // U suprotnom, dopustite navigaciju
//         next();
//     }
// });

export default router;
