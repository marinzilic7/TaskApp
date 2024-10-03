<template>
    <div>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <RouterLink class="navbar-brand text-light" to="/">TaskApp</RouterLink>
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
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <RouterLink aria-current="page" class="nav-link text-light" to="/">Home</RouterLink>
                        </li>
                    </ul>

                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <!-- Prikazivanje "Register" i "Login" ako korisnik nije prijavljen -->
                        <li v-if="!isLoggedUser" class="nav-item">
                            <RouterLink aria-current="page" class="nav-link text-light" to="/register">Register</RouterLink>
                        </li>
                        <li v-if="!isLoggedUser" class="nav-item">
                            <RouterLink aria-current="page" class="nav-link text-light login" to="/login">Login</RouterLink>
                        </li>

                        <!-- Prikazivanje korisničkog imena i logout opcije ako je korisnik prijavljen -->
                        <li class="nav-item dropdown me-5" v-if="isLoggedUser">
                            <a class="name-user nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ loggedInUser.firstName }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a @click="logout()" class="dropdown-item" href="#">Odjava</a>
                                </li>
                            </ul>
                        </li>

                        <!-- Prikazivanje datuma i vremena -->
                        <li class="nav-item d-flex">
                            <a class="nav-link text-light me-3 me-lg-0">{{ date }}</a>
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
            loggedInUser: null,  // Dodajemo loggedInUser
        };
    },
    mounted() {
        this.currentTime();
    },
    created() {
        this.isLogged();  // Poziv metode koja proverava da li je korisnik prijavljen
    },
    methods: {
        currentTime() {
            setInterval(() => {
                const currentDate = new Date();
                const dayOfWeek = currentDate.toLocaleString("hr-HR", { weekday: "short" });
                const dayAndMonth = currentDate.toLocaleString("hr-HR", { day: "numeric", month: "short" });
                const year = currentDate.getFullYear();
                const time = currentDate.toLocaleTimeString("hr-HR", { hour: "2-digit", minute: "2-digit" });
                this.time = `${time}`;
                this.date = `${dayOfWeek.toLowerCase()}. ${dayAndMonth} ${year}.`;
            }, 1000);
        },
        isLogged() {
            axios
                .get("/isLogged")
                .then((response) => {
                    if (response.data && response.data.firstName) {
                        // Ako je korisnik prijavljen
                        this.loggedInUser = response.data;
                        this.isLoggedUser = true;
                    } else {
                        // Ako korisnik nije prijavljen
                        this.isLoggedUser = false;
                        this.loggedInUser = null;
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
</style>
