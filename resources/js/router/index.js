import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Register from "../views/Register.vue";
import Login from "../views/Login.vue";
import Admin from "../views/admin/Admin.vue";
import Users from "../views/admin/Users.vue";
import Categories from "../views/admin/Categories.vue";
import Profile from "../views/Profile.vue";
import Important from "../views/Important.vue";
import Planned from "../views/Planned.vue";
import GroupPage from "../views/GroupPages/GroupPage.vue";
import Team from "../views/Team.vue";
import TeamPage from "../views/TeamPages/TeamPage.vue";
import Mytask from "../views/Mytask.vue";


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
            path: "/users",
            name: "Users",
            component: Users,
        },
        {
            path: "/categories",
            name: "Categories",
            component: Categories,
        },
        {
            path: "/important",
            name: "Important",
            component: Important,
        },
        {
            path: "/planned",
            name: "Planned",
            component: Planned,
        },
        {
            path: '/group/:id',
            name: 'group',
            component: GroupPage,
            props: true,
        },

        {
            path: "/team",
            name: "Team",
            component: Team,
        },
        {
            path: '/team/:id',
            name: 'team',
            component: TeamPage,
            props: true,
        },
        {
            path: "/mytasks",
            name: "Mytask",
            component: Mytask,
        }

    ],
});

router.afterEach(() => {
    const backdropElement = document.querySelector(".offcanvas-backdrop");
    if (backdropElement) {
        backdropElement.remove();
    }
});
// // Navigacijski stražar za provjeru autentifikacije


export default router;
