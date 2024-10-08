<script setup>
import Navbar from "../../components/Navbar.vue";
import { ref } from "vue";
import Users from "../admin/Users.vue";
import Categories from "../admin/Categories.vue";

const activeComponent = ref("Users"); // Početni aktivni komponenta

// Funkcija koja postavlja aktivnu komponentu na 'Users' ili 'Categories'
const setActiveComponent = (component) => {
    activeComponent.value = component;
};
</script>

<template>
     <div v-if="loading">
        <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="spinner-border" role="status">

            </div>
        </div>
    </div>
    <div v-else-if="user.role != 'admin' ">
        <div class="d-flex flex-column align-items-center">
            <img src="../../../images/404.png" height="700px" />
            <h3>Stranica je dostupna samo administratorima.</h3>
        </div>
    </div>
    <div v-else>
        <div>
            <Navbar />
        </div>

        <div class="d-flex">
            <!-- Sidebar -->
            <div class="sidebar p-3 mt-5">
                <h4>Admin Panel</h4>
                <ul class="nav flex-column">
                    <!-- Korisnici -->
                    <li class="nav-item">
                        <!-- Provjera da li je trenutna komponenta 'Users', i dodavanje klase 'active' -->
                        <a
                            class="nav-link"
                            :class="{ active: activeComponent === 'Users' }"
                            @click="setActiveComponent('Users')"
                            href="#"
                        >
                            <i class="bi bi-person-circle"></i> Korisnici
                        </a>
                    </li>

                    <!-- Kategorije -->
                    <li class="nav-item">
                        <!-- Provjera da li je trenutna komponenta 'Categories', i dodavanje klase 'active' -->
                        <a
                            class="nav-link"
                            :class="{
                                active: activeComponent === 'Categories',
                            }"
                            @click="setActiveComponent('Categories')"
                            href="#"
                        >
                            <i class="bi bi-list-ul"></i> Kategorije
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Content area -->
            <div class="container">
                <div class="content-area ml-250px p-4">
                    <!-- Prikazuje korisnike ili kategorije ovisno o selekciji -->
                    <Users v-if="activeComponent === 'Users'" />
                    <Categories v-if="activeComponent === 'Categories'" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {
        Navbar,
        Users,
        Categories,
    },
    data() {
        return {
            user: [],
            loading:true,
        };
    },
    created() {
        this.isLogged();
    },
    methods: {
        isLogged() {
            axios
                .get("/isLogged")
                .then((response) => {
                    if (response.data && response.data.firstName) {
                        this.user = response.data;
                    } else {
                        this.loggedInUser = null;
                    }
                })
                .catch((error) => {
                    console.log(error);
                    this.isLoggedUser = false;
                    this.loggedInUser = null;
                }).finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>

<style>
.sidebar {
    position: fixed;
    top: 1%;
    bottom: 0;
    left: 0;
    width: 250px;
    background-color: #f8f9fa;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.sidebar .nav-link {
    color: #000;
}

.sidebar .nav-link:hover {
    color: #0d6efd;
}

.content-area {
    margin-left: 250px; /* Osigurava da content nije pokriven sidebarom */
}

/* Stil za aktivni link */
.sidebar .nav-link.active {
    color: #0c2e53;
    font-weight: bold;
}
</style>
