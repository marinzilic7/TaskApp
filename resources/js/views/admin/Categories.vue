<template>
    <div>
        <button
            class="btn btn-primary position-absolute top-5 end-0 me-3 mt-3"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasRight"
            aria-controls="offcanvasRight"
        >
            Dodaj
        </button>
        <h2 class="text-center">Pregled svih kategorija</h2>

        <div class="table-responsive mt-5 shadow-lg p-5">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Ime</th>
                        <th scope="col">Korisnik</th>
                        <th scope="col">Datum</th>
                        <th scope="col">Uređeno</th>
                        <th scope="col">Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="categories.length === 0">
                        <td colspan="6" class="text-center">Nema kategorija</td>
                    </tr>
                    <tr v-for="category in categories" :key="category.id">
                        <th scope="row">{{ category.id }}</th>
                        <td>{{ category.name }}</td>
                        <td>
                            {{
                                category.user
                                    ? category.user.firstName +
                                      " " +
                                      category.user.lastName
                                    : "Nema korisnika"
                            }}
                        </td>
                        <td>{{ formatDate(category.created_at) }}</td>
                        <td v-if="category.created_at === category.updated_at">
                            Nikad
                        </td>
                        <td v-else>{{ formatDate(category.updated_at) }}</td>
                        <td>
                            <button
                                class="bg-transparent border-0"
                                @click="openEditModal(category)"
                            >
                                <i
                                    class="bi bi-pencil-square fs-4 text-primary"
                                ></i>
                            </button>
                            <button
                                class="bg-transparent border-0"
                                @click="confirmDelete(category.id)"
                            >
                                <i
                                    class="bi bi-file-earmark-x fs-4 text-danger"
                                ></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal za potvrdu brisanja -->
        <div
            class="modal fade"
            id="deleteModal"
            tabindex="-1"
            aria-labelledby="deleteModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">
                            Potvrda brisanja
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        Da li ste sigurni da želite obrisati ovu kategoriju?
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Zatvori
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="deleteCategory"
                        >
                            Obriši
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal za uređivanje kategorije -->
        <div
            class="modal fade"
            id="editModal"
            tabindex="-1"
            aria-labelledby="editModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">
                            Uredi kategoriju
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateCategory">
                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="floatingInput"
                                    placeholder="Ime kategorije"
                                    v-model="editForm.name"
                                    required
                                />
                                <label for="floatingInput"
                                    >Ime kategorije</label
                                >
                            </div>
                            <button
                                type="submit"
                                class="btn btn-primary w-100 btn-sm"
                            >
                                Ažuriraj
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- offcanvas -->
        <div
            class="offcanvas offcanvas-end"
            tabindex="-1"
            id="offcanvasRight"
            aria-labelledby="offcanvasRightLabel"
        >
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">
                    Dodaj novu kategoriju
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close"
                ></button>
            </div>
            <div class="offcanvas-body">
                <form @submit.prevent="addCategory()">
                    <div class="form-floating mb-3">
                        <input
                            type="text"
                            class="form-control"
                            id="floatingInput"
                            placeholder="Ime kategorije"
                            required
                            v-model="form.name"
                        />
                        <label for="floatingInput">Ime kategorije</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-sm">
                        Potvrdi
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { format } from "date-fns"; // Importanje date-fns za formatiranje datuma
import { hr } from "date-fns/locale"; // Hrvatski lokalitet za ispravan prikaz mjeseca i dana

export default {
    data() {
        return {
            form: {
                name: "",
            },
            categories: [],
            categoryIdToDelete: null, // ID kategorije za brisanje
            editForm: {
                id: null,
                name: "",
            }, // Form za ažuriranje
        };
    },
    created() {
        this.fetchCategories();
    },
    methods: {
        addCategory() {
            axios
                .post("/addCategory", this.form)
                .then((response) => {
                    this.form.name = "";
                    this.fetchCategories();
                    let offcanvasElement =
                        document.getElementById("offcanvasRight");
                    let offcanvasInstance =
                        bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (offcanvasInstance) {
                        offcanvasInstance.hide();
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchCategories() {
            axios
                .get("/getCategories")
                .then((response) => {
                    this.categories = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        formatDate(date) {
            return format(new Date(date), "d. MMMM yyyy. HH:mm", {
                locale: hr,
            });
        },
        confirmDelete(categoryId) {
            this.categoryIdToDelete = categoryId;
            let deleteModal = new bootstrap.Modal(
                document.getElementById("deleteModal")
            );
            deleteModal.show();
        },
        deleteCategory() {
            axios
                .post(`/deleteCategory/${this.categoryIdToDelete}`)
                .then((response) => {
                    this.fetchCategories();
                    let deleteModal = bootstrap.Modal.getInstance(
                        document.getElementById("deleteModal")
                    );
                    deleteModal.hide();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        // Otvara modal za editovanje kategorije
        openEditModal(category) {
            this.editForm.id = category.id;
            this.editForm.name = category.name;
            let editModal = new bootstrap.Modal(
                document.getElementById("editModal")
            );
            editModal.show();
        },
        // Ažurira kategoriju
        updateCategory() {
            axios
                .post(`/updateCategory/${this.editForm.id}`, this.editForm)
                .then((response) => {
                    this.fetchCategories();
                    let editModal = bootstrap.Modal.getInstance(
                        document.getElementById("editModal")
                    );
                    editModal.hide();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>

<style scoped>
/* Tvoj stil za stranicu kategorija */
</style>
