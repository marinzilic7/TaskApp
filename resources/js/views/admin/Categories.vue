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
                        <!-- <th scope="col">Akcije</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="category in categories" :key="category.id">
                        <th scope="row">{{ category.id }}</th>
                        <td>{{ category.name }}</td>
                        <td>
                            {{
                                category.user
                                    ? category.user.firstName + " " + category.user.lastName
                                    : "Nema korisnika"
                            }}
                        </td>

                        <td>{{ formatDate(category.created_at) }}</td>
                        <td v-if="category.created_at === category.updated_at">Nikad</td>
                        <td v-else>{{ formatDate(category.updated_at) }}</td>
                    </tr>
                </tbody>
            </table>
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
                            placeholder="name@example.com"
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
import { format, formatDistanceToNow, parseISO } from "date-fns"; // Importanje date-fns za formatiranje datuma
import { hr } from "date-fns/locale"; // Hrvatski lokalitet za ispravan prikaz mjeseca i dana

export default {
    data() {
        return {
            form: {
                name: "",
            },
            categories: [],
        };
    },
    created() {
        this.fetchCategories();
    },
    methods: {
        addCategory() {
            console.log(this.form.name);
            axios
                .post("/addCategory", this.form)
                .then((response) => {
                    this.form.name = "";
                    this.fetchCategories();
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
                    console.log(this.categories);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        formatDate(date) {
            return format(new Date(date), "d. MMMM yyyy.", { locale: hr });
        },
    },
};
</script>

<style scoped>
/* Tvoj stil za stranicu kategorija */
</style>
