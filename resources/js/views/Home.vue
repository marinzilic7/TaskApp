<script setup>
import axios from "axios";
import Navbar from "../components/Navbar.vue";
</script>

<template>
    <div>
        <Navbar />
    </div>

    <div class="container">
        <div class="main">
            <h3 class="text-center mt-4">Dodaj svoje taskove</h3>
            <div class="d-flex justify-content-center mt-5">
                <button
                    class="add-button btn btn-primary w-50"
                    data-bs-toggle="modal"
                    data-bs-target="#exampleModal"
                    data-bs-whatever="@mdo"
                >
                    Dodaj
                </button>
            </div>
        </div>
        <div class="d-flex gap-5">
            <table class="table shadow-lg mt-5 w-25" v-for="task in tasks">
                <thead>
                    <tr>
                        <th colspan="5" class="text-center">
                            <div
                                class="d-flex justify-content-evenly align-items-center"
                            >
                                <span>{{ task.title }}</span>
                                <button
                                    class="btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#subTaskModal"
                                    data-bs-whatever="@mdo"
                                    :data-task-id="task.id"
                                    @click="setCurrentTaskId(task.id)"
                                >
                                    <i
                                        class="add-btn bi bi-plus-circle text-success"
                                        role="button"
                                    ></i>
                                </button>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th scope="col">Handle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(subtask, index) in task.subtasks"
                        :key="subtask.id"
                    >
                        <th scope="row">{{ index + 1 }}</th>
                        <td>{{ subtask.title }}</td>
                        <td></td>
                    </tr>
                    <tr v-if="task.subtasks.length === 0">
                        <td colspan="5" class="text-center text-muted">
                            Nema podzadataka
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL -->
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
                        Dodaj zadatak
                    </h1>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="addTask()">
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label"
                                >Ime zadataka:</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="recipient-name"
                                v-model="form.title"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label"
                                >Kategorija:</label
                            >
                            <select
                                class="form-select"
                                aria-label="Default select example"
                                v-model="form.category_id"
                                required
                            >
                                <option selected disabled>
                                    Odaberi kategoriju
                                </option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Potvrdi
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary w-100"
                        data-bs-dismiss="modal"
                    >
                        Zatvori
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Subtask modal -->

    <div
        class="modal fade"
        id="subTaskModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Dodaj podzatake
                    </h1>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="addSubtask()">
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label"
                                >Task:</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="recipient-name"
                                required
                                v-model="subtaskTitle"
                            />
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Potvrdi
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary w-100"
                        data-bs-dismiss="modal"
                    >
                        Zatvori
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                title: "",
                category_id: "",
            },
            tasks: [],
            categories: [],
            subTasks: [],
            currentTaskId: null,
            subtaskTitle: "",
        };
    },
    created() {
        this.fetchCategories();
        this.getTasks();
    },

    methods: {
        addTask() {
            const Data = {
                title: this.form.title,

                category_id: this.form.category_id,
            };
            console.log("Category Id je", Data);
            axios
                .post("/addTask", this.form)
                .then((response) => {
                    this.getTasks();
                    alert(response.data.message);
                })
                .catch((error) => {
                    if (error.response && error.response.status === 400) {
                        alert(error.response.data.error);
                    } else {
                        alert("Failed to add task.");
                    }
                });
        },

        fetchCategories() {
            axios
                .get("/getCategories")
                .then((response) => {
                    this.categories = response.data;
                    this.loading = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getTasks() {
            axios
                .get("/getTasks")
                .then((response) => {
                    this.tasks = response.data;
                    console.log("Ovo su taskovi", this.tasks);
                    this.tasks.forEach((task) => {
                        this.getSubtasks(task.id); // Dohvaćamo podzadatke za svaki task
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        setCurrentTaskId(taskId) {
            this.currentTaskId = taskId;
        },
        addSubtask() {
            if (!this.currentTaskId) {
                alert("Task ID nije postavljen!");
                return;
            }
            const subtaskData = {
                task_id: this.currentTaskId,
                title: this.subtaskTitle,
            };
            console.log("Dodavanje podzadatka za task ID:", subtaskData);

            axios
                .post("/addSubtask", subtaskData)
                .then((response) => {
                    alert(response.data.message);
                    this.getTasks(); // Osvježavanje liste taskova.
                })
                .catch((error) => {
                    console.error("Greška pri dodavanju podzadatka:", error);
                });
        },
        getSubtasks(taskId) {
            axios
                .get(`/getSubtasks/${taskId}`)
                .then((response) => {
                    // Dodajte podzadatke u odgovarajući task
                    const task = this.tasks.find((task) => task.id === taskId);
                    if (task) {
                        task.subtasks = response.data; // Dodajte podzadatke u task
                        console.log(
                            "Podzadaci za task",
                            taskId,
                            "su:",
                            response.data
                        );
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>

<style scoped>
.add-button {
    background-color: #175392;
    border: none;
}

.add-btn {
    font-size: 1.5rem;
}
</style>
