<script setup>
import { RouterLink, RouterView } from "vue-router";
</script>

<template>
    <div class="main vh-100">
        <div class="conatiner">
            <div
                class="d-flex vh-100 justify-content-center align-items-center"
            >
                <form class="bg-light col-12 col-lg-4 col-md-5 col-sm-5 p-4">
                    <div
                        class="d-flex justify-content-center flex-column align-items-center gap-3"
                    >
                        <img
                            src="../../images/logo.png"
                            alt=""
                            srcset=""
                            height="70px"
                        />
                        <p class="reg-heading fw-bold">Prijavite se na sustav</p>
                    </div>
                    <hr class="hr-line" />


                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label"
                            >Vaša email adresa</label
                        >
                        <input
                            type="email"
                            class="form-control"
                            id="exampleInputEmail1"
                            aria-describedby="emailHelp"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"
                            >Vaša lozinka</label
                        >
                        <input
                            type="password"
                            class="form-control"
                            id="exampleInputPassword1"
                        />
                    </div>

                    <button type="submit" class="register-button btn w-100">
                        Prijava
                    </button>

                    <div class="d-flex justify-content-center mt-3">
                        <p class="text-muted">
                            Nemate račun?
                           <a href="/register">Registracija</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>

<script></script>

<style scoped>
.main {
    background: linear-gradient(to right, #0c2e53, #175392);
}

.register-button {
    background: #175392;
    color: white;
}

.reg-heading {
    color: #175392;
    letter-spacing: 1.5px;
}

.hr-line {
    border: 1px solid #175392;
}
</style>
