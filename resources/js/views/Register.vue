<template>
    <div class="main vh-100">
        <div class="container">
            <div
                v-if="successRegister"
                class="alert alert-primary w-25 position-absolute top-0 end-0 mt-3 me-3 text-center"
                role="alert"
            >
                {{ message }}
            </div>

            <div
                class="d-flex vh-100 justify-content-center align-items-center"
            >
                <form
                    class="bg-light col-12 col-lg-5 col-md-5 col-sm-5 p-4 rounded"
                    @submit.prevent="registerUser"
                >
                    <div
                        class="d-flex justify-content-center flex-column align-items-center gap-3"
                    >
                        <img src="../../images/logo.png" alt="" height="70px" />
                        <p class="reg-heading fw-bold">Izradite vaš račun</p>
                    </div>
                    <hr class="hr-line" />

                    <div class="form-floating mb-3">
                        <input
                            type="text"
                            class="form-control"
                            :class="{ 'is-invalid': errors.firstName }"
                            id="floatingInputName"
                            placeholder="Ime"
                            v-model="form.firstName"
                        />
                        <label for="floatingInputName">Ime</label>
                        <p v-if="errors.firstName" class="text-danger">
                            {{ errors.firstName[0] }}
                        </p>
                    </div>

                    <div class="form-floating mb-3">
                        <input
                            type="text"
                            class="form-control"
                            :class="{ 'is-invalid': errors.lastName }"
                            id="floatingInputSurname"
                            placeholder="Prezime"
                            v-model="form.lastName"
                        />
                        <label for="floatingInputSurname">Prezime</label>
                        <p v-if="errors.lastName" class="text-danger">
                            {{ errors.lastName[0] }}
                        </p>
                    </div>

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
                        Registracija
                    </button>

                    <div class="d-flex justify-content-center mt-3">
                        <p class="text-muted">
                            Već imate račun? <a href="/login">Prijava</a>
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
                firstName: "",
                lastName: "",
                email: "",
                password: "",
            },
            passwordEye: false,
            message: "",
            successRegister: false,
            falseRegister: false,
            errors: {},
        };
    },
    methods: {
        tooglePassword() {
            this.passwordEye = !this.passwordEye;
        },
        registerUser() {
            axios
                .post("/registerUser", this.form)
                .then((response) => {
                    this.message = response.data.message;
                    this.successRegister = true;
                    setInterval(() => {
                        this.successRegister = false;
                    }, 2000);
                })
                .catch((error) => {
                    console.log(error);

                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                        console.log(this.errors);
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
