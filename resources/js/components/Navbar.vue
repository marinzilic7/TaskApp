<script></script>

<template>
    <div>
        <div v-if="loading" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <nav class="navbar navbar-expand-lg bg-body-tertiary" v-if="!loading">
            <div class="container-fluid">
                <RouterLink class="navbar-brand text-light" to="/"
                    >TaskApp</RouterLink
                >
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div
                    class="collapse navbar-collapse"
                    id="navbarSupportedContent"
                >
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <RouterLink
                                aria-current="page"
                                class="nav-link text-light"
                                to="/"
                                >Task</RouterLink
                            >
                        </li>
                    </ul>

                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li v-if="!isLoggedUser" class="nav-item">
                            <RouterLink
                                aria-current="page"
                                class="nav-link text-light"
                                to="/register"
                                >Register</RouterLink
                            >
                        </li>
                        <li v-if="!isLoggedUser" class="nav-item">
                            <RouterLink
                                aria-current="page"
                                class="nav-link text-light login"
                                to="/login"
                                >Login</RouterLink
                            >
                        </li>
                        <div v-if="loggedInUser.image === null">
                            <img
                                src="https://www.pngall.com/wp-content/uploads/5/Profile-PNG-File.png"
                                class="rounded-circle"
                                style="width: 40px"
                            />
                        </div>
                        <div v-else>
                            <img
                                :src="getImageUrl(loggedInUser.image)"
                                class="rounded-circle img-fluid"
                                style="width: 40px"
                            />
                        </div>

                        <li class="nav-item dropdown me-5" v-if="isLoggedUser">
                            <a
                                class="name-user nav-link dropdown-toggle text-light"
                                href="#"
                                id="navbarDropdown"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                {{ loggedInUser.firstName }}
                            </a>

                            <ul
                                class="dropdown-menu"
                                aria-labelledby="navbarDropdown"
                            >
                                <li v-if="loggedInUser.role === 'admin'">
                                    <RouterLink
                                        class="dropdown-item"
                                        to="/admin"
                                        >Admin</RouterLink
                                    >
                                </li>
                                <li>
                                    <RouterLink
                                        class="dropdown-item"
                                        to="/profile"
                                        >Račun</RouterLink
                                    >
                                </li>
                                <li>
                                    <a
                                        @click="logout()"
                                        class="dropdown-item"
                                        href="#"
                                        >Odjava</a
                                    >
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item d-flex">
                            <a class="nav-link text-light me-3 me-lg-0">{{
                                date
                            }}</a>
                            <a class="nav-link text-light">{{ time }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
export default {
    data() {
        return {
            date: null,
            time: null,
            isLoggedUser: false,
            loggedInUser: null,
            loading: true,
        };
    },
    mounted() {
        this.currentTime();
    },
    created() {
        this.isLogged();
    },
    methods: {
        currentTime() {
            setInterval(() => {
                const currentDate = new Date();
                const dayOfWeek = currentDate.toLocaleString("hr-HR", {
                    weekday: "short",
                });
                const dayAndMonth = currentDate.toLocaleString("hr-HR", {
                    day: "numeric",
                    month: "short",
                });
                const year = currentDate.getFullYear();
                const time = currentDate.toLocaleTimeString("hr-HR", {
                    hour: "2-digit",
                    minute: "2-digit",
                });
                this.time = `${time}`;
                this.date = `${dayOfWeek.toLowerCase()}. ${dayAndMonth} ${year}.`;
                this.loading = false;
            }, 1000);
        },
        isLogged() {
            axios
                .get("/isLogged")
                .then((response) => {
                    if (response.data && response.data.firstName) {
                        this.loggedInUser = response.data;
                        this.isLoggedUser = true;
                        console.log(this.loggedInUser.image);
                    } else {
                        this.isLoggedUser = false;
                        this.loggedInUser = null;
                        this.$router.push("/login");
                    }
                })
                .catch((error) => {
                    console.log(error);
                    this.isLoggedUser = false;
                    this.loggedInUser = null;

                });
        },
        logout() {
            axios
                .post("/logout")
                .then(() => {
                    this.isLoggedUser = false;
                    this.loggedInUser = null;
                    this.$router.push("/login");
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getImageUrl(imageName) {
            return `/profile_images/${imageName}`;
        },
    },
};
</script>

<style>
.navbar {
    background: linear-gradient(to right, #0c2e53, #175392);
}

.login::after {
    content: " | ";
    margin-left: 10px;
}

.spinner-border {
    position: fixed;
    top: 50%;
    left: 50%;
    color: #0c2e53;
    z-index: 1000;
}
</style>
