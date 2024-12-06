<script setup>
import Navbar from "@/components/Navbar.vue"; // Import komponente Navbar
import Sidebar from "@/components/Sidebar.vue"; // Import komponente Sidebar
</script>

<template>
    <div>
        <Navbar />
        <div class="d-flex align-items-center ms-3">
            <button
                class="btn"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasExample"
                aria-controls="offcanvasExample"
            >
                <i class="bi bi-list"></i>
            </button>
            <h5 class="mt-2">{{ group.title }}</h5>
        </div>

        <div>
            <form class="ms-5 me-5 z-3">
                <div class="input-group mb-3 mt-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Dodaj novi zadatak"
                        v-model="subtaskTitle"
                    />

                    <button
                        @click="addSubtask()"
                        class="btn add-button"
                        type="button"
                    >
                        <i class="bi bi-plus text-light add-btn"></i>
                    </button>
                </div>
            </form>
            <br />
            <hr />
            <br />
            <div
                class="head-items ms-5 me-5 border pt-2 d-flex justify-content-between"
            >
                <p class="ms-5">Naslov</p>
                <p>Krajnji rok</p>
                <p class="me-3">Važnost</p>
            </div>
            <hr />
        </div>
        <div class="ms-5 me-5 mt-3" v-for="task in subtasks" :key="task.id">
            <div class="border" v-if="task.completed === 0">
                <div class="d-flex gap-2 mt-3 ms-3">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="flexRadioDefault"
                            :id="'checkbox-' + task.id"
                            :name="'task-' + task.id"
                            @change="deleteSubTask(task.id)"
                        />
                    </div>
                    <p>{{ task.title }}</p>

                    <div v-if="task.deadline === null">
                        <input
                            type="date"
                            class="task-date border-0"
                            v-model="task.deadlineDate"
                            @change="addDeadline(task.id)"
                        />
                    </div>

                    <div v-else>
                        <p
                            v-if="
                                formatForComparison(task.deadline) &&
                                formatForComparison(task.deadline) <
                                    formatForComparison(newDate)
                            "
                            @click="changeDeadline(task.id)"
                            class="p-border task-date"
                        >
                            {{ formatDate(task.deadline) }}
                        </p>

                        <p
                            v-else
                            @click="changeDeadline(task.id)"
                            class="task-date"
                        >
                            {{ formatDate(task.deadline) }}
                        </p>
                    </div>

                    <i
                        :class="[
                            'bi',
                            task.isImportant ? 'bi-star-fill' : 'bi-star',
                            'ms-auto me-3',
                        ]"
                        title="Označi kao važno"
                        @click="important(task.id)"
                    >
                    </i>
                </div>
            </div>
        </div>
        <hr />
        <p class="text-center mt-3">
            {{ Number.isInteger(progress) ? progress : progress.toFixed(2) }} %
        </p>
        <div class="progress ms-5 me-5 mt-3">
            <div
                class="progress-bar"
                role="progressbar"
                aria-valuenow="0"
                aria-valuemin="0"
                aria-valuemax="100"
                :style="{ width: progress + '%' }"
            ></div>
        </div>
        <div
            class="complete-accordion accordion accordion-flush ms-5 me-5 mt-5"
            id="accordionFlushExample"
        >
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button
                        class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseOne"
                        aria-expanded="false"
                        aria-controls="flush-collapseOne"
                    >
                        <div class="d-flex align-items-center gap-2">
                            Dovršeno
                            <p class="complete-text-accordion mt-3">
                                {{ completedTasks.length }}
                            </p>
                        </div>
                    </button>
                </h2>
                <div
                    id="flush-collapseOne"
                    class="list-tasks accordion-collapse collapse"
                    data-bs-parent="#accordionFlushExample"
                >
                    <div class="accordion-body">
                        <div v-if="completedTasks.length > 0">
                            <div v-for="task in subtasks" :key="task.id">
                                <div
                                    class="ms-5 me-5 mt-3"
                                    v-if="task.completed === 1"
                                >
                                    <div class="border">
                                        <div class="d-flex gap-2 mt-3 ms-3">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="flexRadioDefault"
                                                    :id="'checkbox-' + task.id"
                                                    :name="'task-' + task.id"
                                                    @change="
                                                        deleteCompletedSubtask(
                                                            task.id
                                                        )
                                                    "
                                                />
                                            </div>
                                            <p
                                                class="text-decoration-line-through"
                                            >
                                                {{ task.title }}
                                            </p>

                                            <div v-if="task.deadline === null">
                                                <input
                                                    type="date"
                                                    class="task-date border-0"
                                                    v-model="task.deadlineDate"
                                                    @change="
                                                        addDeadline(task.id)
                                                    "
                                                />
                                            </div>

                                            <div v-else>
                                                <p
                                                    v-if="
                                                        formatForComparison(
                                                            task.deadline
                                                        ) &&
                                                        formatForComparison(
                                                            task.deadline
                                                        ) >
                                                            formatForComparison(
                                                                newDate
                                                            )
                                                    "
                                                    @click="
                                                        changeDeadline(task.id)
                                                    "
                                                    class="task-date"
                                                >
                                                    {{
                                                        formatDate(
                                                            task.deadline
                                                        )
                                                    }}
                                                </p>
                                                <p
                                                    v-else
                                                    class="p-border task-date"
                                                    @click="
                                                        changeDeadline(task.id)
                                                    "
                                                >
                                                    {{
                                                        formatDate(
                                                            task.deadline
                                                        )
                                                    }}
                                                </p>
                                            </div>

                                            <i
                                                :class="[
                                                    'bi',
                                                    task.isImportant
                                                        ? 'bi-star-fill'
                                                        : 'bi-star',
                                                    'ms-auto me-3',
                                                ]"
                                                title="Označi kao važno"
                                                @click="important(task.id)"
                                            >
                                            </i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div>
                                <div
                                    class="text-muted d-flex justify-content-center"
                                >
                                    <p>Trenutno nemate završenih zadataka</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Sidebar
        :tasks="subtasks"
        :importantTask="importantTask"
        :unTasks="completedTasks"
        :group="group"
        :subtasks="subtasks"
        ref="sidebar"

    />
</template>

<script>
import axios from "axios"; // Import za axios

export default {
    data() {
        return {
            groupId: this.$route.params.id, // Dohvat ID-a grupe s URL-a
            group: {},
            subtaskTitle: null,
            subtasks: [],
            newDate: null,
            importantTask: [],
            completedTasks: [],
            progress: 0,
            totalTasks: 0,
            singleGroup: {},
        };
    },

    created() {
        this.getGroupData(this.groupId);
        if (this.subtasks.length === 0) {
            this.getSubtasks(this.groupId); // Pozivanje samo ako još nisu dohvaćeni
        }

        this.getCompletedSubtasks(this.groupId);
        this.updateProgress();
    },
    watch: {
        "$route.params.id": function (newId) {
            this.groupId = newId; // Ažuriraj `groupId`
            this.getGroupData(this.groupId);
            this.getCompletedSubtasks();
            this.getSubtasks(this.groupId); // Ponovo dohvatiti podatke za novi ID
        },
    },
    mounted() {
        this.newDate = new Date();
    },
    methods: {
        // Funkcija za dohvat podataka o grupi
        async getGroupData(id) {
            try {
                const response = await axios.get(`/getGroupData/${id}`); // API endpoint za dohvat podataka o grupi
                this.group = response.data; // Pohranjivanje podataka u 'group' objekt
                console.log("Ovo su podaci grupe", this.group); // Ispis podataka u konzoli
            } catch (error) {
                console.error("Error fetching group data:", error); // Obrada pogreške
            }
        },
        addSubtask() {
            const subtaskData = {
                group_id: this.groupId,
                title: this.subtaskTitle,
            };
            console.log("Dodavanje podzadatka za task ID:", subtaskData);

            axios
                .post("/addSubtask", subtaskData)
                .then((response) => {
                    this.getSubtasks(this.groupId);

                    this.$refs.sidebar.getGrouptasks();


                    this.subtaskTitle = "";
                })
                .catch((error) => {
                    console.error("Greška pri dodavanju podzadatka:", error);
                });
        },
        getSubtasks(groupId) {
            axios
                .get(`/getSubtasks/${groupId}`)
                .then((response) => {
                    this.subtasks = response.data;
                    this.totalTasks = this.subtasks.length;
                    console.log("Podzadaci", this.subtasks);
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.updateProgress();
                });
        },

        deleteSubTask(id) {
            axios
                .post(`/deleteSubtask/${id}`)
                .then((response) => {
                    this.getSubtasks(this.groupId);
                    this.getCompletedSubtasks(this.groupId);
                    this.updateProgress();
                })
                .catch((error) => {
                    console.error("Greška pri brisanju podzadatka:", error);
                });
        },
        formatCurrentDate(date) {
            const options = { day: "numeric", month: "long", year: "numeric" };
            const formattedDate = new Intl.DateTimeFormat(
                "hr-HR",
                options
            ).format(new Date(date));

            // Podijeli datum na dijelove (npr. "4. prosinac 2024.")
            const [day, month, year] = formattedDate.split(" ");

            // Skrati naziv mjeseca na prva tri slova
            return `${day} ${month.substring(0, 3)} ${year}`;
        },
        formatForComparison(date) {
            const parsedDate = new Date(date);
            parsedDate.setHours(0, 0, 0, 0);
            // Provjera valjanosti datuma
            if (isNaN(parsedDate)) {
                return null; // Ako datum nije ispravan, vratiti null
            }

            return parsedDate; // Vraća Date objekt za usporedbu
        },

        addDeadline(taskId) {
            axios
                .post(`/addDeadlineSubTask/${taskId}`, {
                    deadline: this.subtasks.find((task) => task.id === taskId)
                        .deadlineDate,
                })
                .then((response) => {
                    this.getSubtasks(this.groupId);
                })
                .catch((error) => {
                    console.error("Greška pri dodavanju roka:", error);
                });
        },
        changeDeadline(taskId) {
            const task = this.subtasks.find((task) => task.id === taskId);

            if (task) {
                task.deadline = null; // Resetiranje roka
                console.log(task.deadline);
            }
        },

        formatDate(date) {
            const options = { day: "numeric", month: "long", year: "numeric" };
            const formattedDate = new Intl.DateTimeFormat(
                "hr-HR",
                options
            ).format(new Date(date));
            // Podijeli datum na dijelove (npr. "3. prosinac 2024.")
            const [day, month, year] = formattedDate.split(" ");

            // Uzmi prva tri slova mjeseca i spoji dijelove
            return `${day} ${month.substring(0, 3)} ${year}`;
        },

        important(id) {
            const task = this.subtasks.find((task) => task.id === id);
            if (task) {
                task.isImportant = !task.isImportant; // Privremeno promijeni status
            }

            // Pošalji zahtjev na server
            axios
                .post(`/importantSubtask/${id}`)
                .then(() => {
                    // Opcionalno, ponovno učitaj zadatke ako je potrebno
                    this.getSubtasks(this.groupId);
                })
                .catch((error) => {
                    console.error("Greška pri označavanju zadatka:", error);
                    // Vraćanje na prethodno stanje ako dođe do greške
                    if (task) {
                        task.isImportant = !task.isImportant;
                    }
                });
        },

        deleteCompletedSubtask(id) {
            axios
                .post(`/deleteCompletedSubtask/${id}`, {
                    groupId: this.groupId, // Dodajte groupId u tijelo zahtjeva
                })
                .then((response) => {
                    this.getSubtasks(this.groupId); // Promijenite taskId u groupId za poziv
                    this.getCompletedSubtasks(this.groupId);
                    this.updateProgress();
                })
                .catch((error) => {
                    console.error("Greška pri brisanju zadatka:", error);
                });
        },

        getCompletedSubtasks(id) {
            axios
                .get(`/getCompletedSubtasks/${id}`)
                .then((response) => {
                    this.completedTasks = response.data;
                    console.log("Završeni zadaci", this.completedTasks);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        updateProgress() {
            const completedTasksCount = this.subtasks.filter(
                (task) => task.completed === true || task.completed === 1 // Provjerite vrijednost koju koristite za označavanje završenih zadataka
            ).length;

            console.log("Ukupan broj zadataka:", this.totalTasks);
            console.log("Broj završenih zadataka:", completedTasksCount);

            // Ako postoji barem jedan zadatak, izračunajte postotak
            if (this.totalTasks > 0) {
                this.progress = (completedTasksCount / this.totalTasks) * 100;
            } else {
                this.progress = 0;
            }
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

.bi-list {
    font-size: 1.7rem;
}

.date-text {
    font-size: 12px;
}

.bi-list {
    font-size: 1.7rem;
}

.date-text {
    font-size: 12px;
}

.checked-task {
    text-decoration: line-through;
    color: grey;
}

.bi-star,
.bi-star-fill {
    color: #175392;
    cursor: pointer;
}

.form-check-input:checked {
    border-color: #175392;
    background-color: #175392;
}

li {
    list-style-type: none;
}

.acc-star {
    color: black;
}

.number-items {
    color: #175392;
}

.date {
    font-size: 13px;
    color: grey;
}

.task-date {
    position: absolute; /* Fiksiranje datuma u sredinu */
    left: 50%;
    margin-top: 3px;
    transform: translateX(-50%);
    font-size: 12px; /* Pomaknite datum u sredinu */
}

.task-calendar {
    position: absolute;
    left: 53%;

    transform: translateX(-50%);
}

.head-items {
    font-size: 12px;
    color: grey;
}

.p-border {
    border: 1px solid red;
    font-size: 12px;
    padding: 5px;
    color: red;
}

#add_list {
    outline: none;
}

#add_list::placeholder {
    color: #175392;
    font-size: 14px;
}

.add_list_btn {
    background-color: #175392;
    color: #fff;
}

.complete-accordion {
    border: 1px solid #175392;
}

.complete-text-accordion {
    color: #175392;
    font-weight: 700;
}

.progress-bar {
    background-color: #175392;
}
</style>
