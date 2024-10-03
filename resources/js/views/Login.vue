<template>
    <div class="main vh-100">
        <div class="conatiner">
            <div
                class="d-flex vh-100 justify-content-center align-items-center"
            >
                <form
                    class="bg-light col-12 col-lg-4 col-md-5 col-sm-5 p-4 rounded"
                    @submit.prevent="loginUser()"
                >
                    <div
                        class="d-flex justify-content-center flex-column align-items-center gap-3"
                    >
                        <img
                            src="../../images/logo.png"
                            alt=""
                            srcset=""
                            height="70px"
                        />
                        <p class="reg-heading fw-bold">
                            Prijavite se na sustav
                        </p>
                    </div>
                    <hr class="hr-line" />

                    <div class="form-floating mb-3">
                        <input
                            type="email"
                            class="form-control"
                            :class="{ 'is-invalid': errors.email }"
                            id="floatingInput"
                            placeholder="name@example.com"
                            v-model="form.email"
                            autocomplete="off"
                        />
                        <label for="floatingInput">Email adresa</label>
                        <p v-if="errors.email" class="text-danger">
                            {{ errors.email[0] }}
                        </p>
                    </div>

                    <div class="input-group mb-3">
                        <div class="form-floating flex-grow-1">
                            <input
                                :type="passwordEye ? 'text' : 'password'"
                                class="form-control"
                                :class="{ 'is-invalid': errors.password }"
                                id="floatingPassword"
                                placeholder="Lozinka"
                                v-model="form.password"
                            />
                            <label for="floatingPassword">Lozinka</label>
                        </div>
                        <span
                            @click="tooglePassword()"
                            class="input-group-text"
                        >
                            <i v-if="passwordEye" class="bi bi-eye"></i>
                            <i v-else class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                    <p v-if="errors.password" class="text-danger">
                        {{ errors.password[0] }}
                    </p>

                    <button type="submit" class="register-button btn w-100">
                        Prijava
                    </button>

                    <div v-if="falseLogin" class="alert alert-danger text-center mt-3">
                        {{ message }}
                    </div>

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

<script>
import axios from "axios";
export default {
    data() {
        return {
            form: {
                email: "",
                password: "",
            },
            errors: {},
            passwordEye: false,
            falseLogin: false,
            errors: {},
        };
    },
    methods: {
        tooglePassword() {
            this.passwordEye = !this.passwordEye;
        },
        loginUser() {
            axios
                .post("/loginUser", this.form)
                .then((response) => {
                    console.log(response);
                    this.$router.push("/");
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                        console.log(this.errors);
                    } else if (
                        error.response &&
                        error.response.status === 401
                    ) {
                        this.falseLogin = true;
                        this.message = error.response.data.message;
                        setTimeout(() => {
                            this.falseLogin = false;
                        }, 3000);
                    }
                });
        },
    },
};
</script>

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
