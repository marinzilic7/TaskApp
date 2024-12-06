<script setup>
import Navbar from "../components/Navbar.vue";
import { ref } from "vue";
import axios from "axios";

const image = ref(null);

const uploadImage = async () => {
    const formData = new FormData();
    formData.append("image", image.value);

    try {
        const response = await axios.post("/uploadProfile", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });
        alert(response.data.message);
    } catch (error) {
        if (error.response && error.response.status === 400) {
            alert(error.response.data.error); // Prikaz poruke o grešci iz backend-a
        } else {
            alert("Failed to upload image.");
        }
    }
};

const imageChange = (event) => {
    image.value = event.target.files[0];
};
</script>

<template style="background-color: #eee">
    <div>
        <Navbar />
    </div>

    <div>
        <section>
            <div class="container mt-5 py-5">
                <div
                    class="row d-flex justify-content-center align-items-center mt-5"
                >
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <img
                                    :src="getImageUrl(loggedInUser.image)"
                                    alt="avatar"
                                    class="rounded-circle img-fluid"
                                    style="width: 150px"
                                />
                                <h5 class="my-3">
                                    {{
                                        loggedInUser.firstName +
                                        " " +
                                        loggedInUser.lastName
                                    }}
                                </h5>

                                <div class="mb-2">
                                    <div>
                                        <form
                                            @submit.prevent="uploadImage"
                                            enctype="multipart/form-data"
                                            class="d-flex justify-content-center align-items-center"
                                        >
                                            <div class="file-upload">
                                                <label
                                                    for="file-upload"
                                                    class="custom-label mt-2"
                                                    >Odaberi sliku</label
                                                >
                                                <input
                                                    id="file-upload"
                                                    type="file"
                                                    @change="imageChange"
                                                    class="upload-input"
                                                />
                                                <div class="file-name">
                                                    {{ fileName }}
                                                </div>
                                            </div>
                                            <button
                                                @click="uploadImage"
                                                class="upload-button ms-3"
                                            >
                                                Uploud
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Ime</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            {{ loggedInUser.firstName }}
                                        </p>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Prezime</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            {{ loggedInUser.lastName }}
                                        </p>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Email</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            {{ loggedInUser.email }}
                                        </p>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Broj mobitela</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">//</p>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Promijeni lozinku</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            <button
                                                type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#exampleModal"
                                                data-bs-whatever="@mdo"
                                                class="btn btn-primary btn-sm"
                                            >
                                                Promijeni lozinku
                                            </button>
                                        </p>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Odjava</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            <button
                                                @click="logout()"
                                                class="btn btn-primary btn-sm"
                                            >
                                                Odjavi se
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div
        class="modal fade"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Promjena lozinke
                    </h1>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="changePassword()">
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label"
                                >Stara lozinka:</label
                            >
                            <input
                                type="password"
                                v-model="form.currentPassword"
                                class="form-control"
                                id="message-text"
                            />
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label"
                                >Nova lozinka:</label
                            >
                            <input
                                type="password"
                                v-model="form.newPassword"
                                class="form-control"
                                id="message-text"
                            />
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >
                                Zatvori
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Potvrdi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoggedUser: false,
            loggedInUser: null,
            form: {
                currentPassword: "",
                newPassword: "",
            },
        };
    },
    created() {
        this.isLogged();
    },
    methods: {
        changePassword() {
            axios
                .post("/changePassword", this.form)
                .then((response) => {
                    this.form.currentPassword = "";
                    this.form.newPassword = "";
                    const modalElement =
                        document.getElementById("exampleModal");
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    modal.hide();
                    alert(response.data.message);
                })
                .catch((error) => {
                    if (error.response && error.response.status === 400) {
                        alert(error.response.data.error); // Prikazivanje greške sa backend-a
                    } else {
                        console.error(error);
                        alert("Došlo je do greške pri promeni lozinke.");
                    }
                });
        },
        isLogged() {
            axios
                .get("/isLogged")
                .then((response) => {
                    if (response.data && response.data.firstName) {
                        this.loggedInUser = response.data;
                        this.isLoggedUser = true;
                        console.log(this.loggedInUser.image);
                        console.log(this.loggedInUser);
                    } else {
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
        getImageUrl(imageName) {
            return `/profile_images/${imageName}`;
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
.upload-input {
    font-size: 14px;
}

.upload-input {
    display: none; /* Sakrij originalni input */
}

.custom-label {
    background-color: #007bff; /* Plava boja */
    color: white;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
}

.file-name {
    margin-top: 10px;
    font-size: 14px;
    color: #555;
}

.upload-button {
    font-size: 12px;
    background-color: #175392;
    color: #fff;
    border: none;
    padding: 10px;
}
</style>
