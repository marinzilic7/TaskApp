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
    </div>
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
        };
    },
    created() {
        this.getGroupData(this.groupId);
        if (this.subtasks.length === 0) {
            this.getSubtasks(this.groupId); // Pozivanje samo ako još nisu dohvaćeni
        }
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
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        deleteSubTask(id) {
            axios
                .post(`/deleteSubtask/${id}`)
                .then((response) => {
                    this.getSubtasks(this.groupId);
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
