<template>
    <div>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
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
                                >Home</RouterLink
                            >
                        </li>
                    </ul>

                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <RouterLink
                                aria-current="page"
                                class="nav-link text-light"
                                to="/register"
                                >Register</RouterLink
                            >
                        </li>
                        <li class="nav-item">
                            <RouterLink
                                aria-current="page"
                                class="nav-link text-light login"
                                to="/login"
                                >Login</RouterLink
                            >
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
        };
    },
    mounted() {
        this.currentTime();
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
            }, 1000);
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
