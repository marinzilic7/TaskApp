<script setup>
import axios from "axios";
import Navbar from "../components/Navbar.vue";
import { RouterLink } from "vue-router";
import Sidebar from "../components/Sidebar.vue";
</script>

<template>
    <div>
        <Navbar />
    </div>

    <!-- Offcanvas -->

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
        <h5 class="mt-2">Planirano</h5>
    </div>
    <div class="ms-5">
        <p class="date-text ms-4 text-muted">{{ date }}</p>
    </div>
    <div>
        <form
            class="ms-5 me-5 z-3"
            action="
        "
        >
            <div class="input-group mb-3 mt-3">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Dodaj novi zadatak"
                    v-model="form.title"
                />

                <button @click="addTask" class="btn add-button" type="button">
                    <i class="bi bi-plus text-light add-btn"></i>
                </button>
            </div>
        </form>
    </div>
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

    <hr />

    <div class="accordion accordion-flush ms-5 me-5" id="accordionFlushExample">
        <div class="accordion-item border border">
            <h2 class="accordion-header">
                <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne"
                    aria-expanded="false"
                    aria-controls="flush-collapseOne"
                >
                    Ranije
                </button>
            </h2>
            <div
                id="flush-collapseOne"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample"
            >
                <div
                    class="accordion-body"
                    v-if="
                        !tasks.some(
                            (task) =>
                                task.completed === 0 &&
                                formatForComparison(task.deadline) &&
                                formatForComparison(task.deadline) <
                                    formatForComparison(newDate)
                        )
                    "
                >
                    <p class="text-center text-muted ms-5">
                        Nema zadataka za ranije
                    </p>
                </div>
                <div v-else class="accordion-body">
                    <div
                        class="ms-5 me-5 mt-3"
                        v-for="task in tasks"
                        :key="task.id"
                    >
                        <div
                            class="border"
                            v-if="
                                task.completed === 0 &&
                                formatForComparison(task.deadline) &&
                                formatForComparison(task.deadline) <
                                    formatForComparison(newDate)
                            "
                        >
                            <div class="d-flex gap-2 mt-3 ms-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="flexRadioDefault"
                                        :id="'checkbox-' + task.id"
                                        :name="'task-' + task.id"
                                        @change="deleteTask(task.id)"
                                    />
                                </div>
                                <p>{{ task.title }}</p>

                                <div>
                                    <input
                                        type="date"
                                        class="task-date-input border-0"
                                        v-model="task.deadlineDate"
                                        @change="addDeadline(task.id)"
                                    />

                                    <p
                                        v-if="
                                            formatForComparison(
                                                task.deadline
                                            ) &&
                                            formatForComparison(
                                                task.deadline
                                            ) >= formatForComparison(newDate)
                                        "
                                        @click="changeDeadline(task.id)"
                                        class="task-date"
                                    >
                                        {{ formatDate(task.deadline) }}
                                    </p>
                                    <p
                                        v-else
                                        class="p-border"
                                        @click="changeDeadline(task.id)"
                                    >
                                        {{ formatDate(task.deadline) }}
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
        </div>
        <div class="accordion-item border border">
            <h2 class="accordion-header">
                <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseTwo"
                    aria-expanded="false"
                    aria-controls="flush-collapseTwo"
                >
                    Danas
                </button>
            </h2>
            <div
                id="flush-collapseTwo"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample"
            >
                <div
                    class="accordion-body"
                    v-if="
                        !tasks.some(
                            (task) =>
                                task.completed === 0 &&
                                formatForComparison(task.deadline) &&
                                formatForComparison(task.deadline).getTime() ===
                                    formatForComparison(newDate).getTime()
                        )
                    "
                >
                    <p class="text-center text-muted ms-5">
                        Nema zadataka za danas
                    </p>
                </div>
                <div v-else class="accordion-body">
                    <div
                        class="ms-5 me-5 mt-3"
                        v-for="task in tasks"
                        :key="task.id"
                    >
                        <div
                            class="border"
                            v-if="
                                task.completed === 0 &&
                                formatForComparison(task.deadline) &&
                                formatForComparison(task.deadline).getTime() ===
                                    formatForComparison(newDate).getTime()
                            "
                        >
                            <div class="d-flex gap-2 mt-3 ms-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="flexRadioDefault"
                                        :id="'checkbox-' + task.id"
                                        :name="'task-' + task.id"
                                        @change="deleteTask(task.id)"
                                    />
                                </div>
                                <p>{{ task.title }}</p>

                                <div>
                                    <input
                                        type="date"
                                        class="task-date-input border-0"
                                        v-model="task.deadlineDate"
                                        @change="addDeadline(task.id)"
                                    />

                                    <p
                                        v-if="
                                            formatForComparison(
                                                task.deadline
                                            ) &&
                                            formatForComparison(
                                                task.deadline
                                            ) >= formatForComparison(newDate)
                                        "
                                        @click="changeDeadline(task.id)"
                                        class="task-date"
                                    >
                                        {{ formatDate(task.deadline) }}
                                    </p>
                                    <p
                                        v-else
                                        class="p-border task-date"
                                        @click="changeDeadline(task.id)"
                                    >
                                        {{ formatDate(task.deadline) }}
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
        </div>
        <div class="accordion-item border">
            <h2 class="accordion-header">
                <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseThree"
                    aria-expanded="false"
                    aria-controls="flush-collapseThree"
                >
                    Sutra
                </button>
            </h2>
            <div
                id="flush-collapseThree"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample"
            >
                <div
                    class="accordion-body"
                    v-if="
                        !tasks.some(
                            (task) =>
                                task.completed === 0 &&
                                formatForComparison(task.deadline) &&
                                formatForComparison(task.deadline).getTime() ===
                                    formatForComparison(
                                        getTomorrowDate()
                                    ).getTime()
                        )
                    "
                >
                    <p class="text-center text-muted ms-5">
                        Nema zadataka za sutra
                    </p>
                </div>
                <div v-else class="accordion-body">
                    <div
                        class="ms-5 me-5 mt-3"
                        v-for="task in tasks"
                        :key="task.id"
                    >
                        <div
                            class="border"
                            v-if="
                                task.completed === 0 &&
                                formatForComparison(task.deadline) &&
                                formatForComparison(task.deadline).getTime() ===
                                    formatForComparison(
                                        getTomorrowDate()
                                    ).getTime()
                            "
                        >
                            <div class="d-flex gap-2 mt-3 ms-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="flexRadioDefault"
                                        :id="'checkbox-' + task.id"
                                        :name="'task-' + task.id"
                                        @change="deleteTask(task.id)"
                                    />
                                </div>
                                <p>{{ task.title }}</p>

                                <div>
                                    <input
                                        type="date"
                                        class="task-date-input border-0"
                                        v-model="task.deadlineDate"
                                        @change="addDeadline(task.id)"
                                    />

                                    <p
                                        v-if="
                                            formatForComparison(
                                                task.deadline
                                            ) &&
                                            formatForComparison(
                                                task.deadline
                                            ) >= formatForComparison(newDate)
                                        "
                                        @click="changeDeadline(task.id)"
                                        class="task-date"
                                    >
                                        {{ formatDate(task.deadline) }}
                                    </p>
                                    <p
                                        v-else
                                        class="p-border task-date"
                                        @click="changeDeadline(task.id)"
                                    >
                                        {{ formatDate(task.deadline) }}
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
        </div>
        <div class="accordion-item border border">
            <h2 class="accordion-header">
                <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseFour"
                    aria-expanded="false"
                    aria-controls="flush-collapseFour"
                >
                    Za dva ili više dana
                </button>
            </h2>
            <div
                id="flush-collapseFour"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample"
            >
                <div
                    class="accordion-body"
                    v-if="
                        !tasks.some(
                            (task) =>
                                task.completed === 0 &&
                                getDaysDifference(task.deadline) >= 2
                        )
                    "
                >
                    <p class="text-center text-muted ms-5">
                        Nema zadataka za 2 ili više dana
                    </p>
                </div>
                <div v-else class="accordion-body">
                    <div
                        class="ms-5 me-5 mt-3"
                        v-for="task in tasks"
                        :key="task.id"
                    >
                        <div
                            class="border"
                            v-if="
                                task.completed === 0 &&
                                getDaysDifference(task.deadline) >= 2
                            "
                        >
                            <div class="d-flex gap-2 mt-3 ms-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="flexRadioDefault"
                                        :id="'checkbox-' + task.id"
                                        :name="'task-' + task.id"
                                        @change="deleteTask(task.id)"
                                    />
                                </div>
                                <p>{{ task.title }}</p>

                                <div>
                                    <input
                                        type="date"
                                        class="task-date-input border-0"
                                        v-model="task.deadlineDate"
                                        @change="addDeadline(task.id)"
                                    />

                                    <p
                                        v-if="
                                            formatForComparison(
                                                task.deadline
                                            ) &&
                                            formatForComparison(
                                                task.deadline
                                            ) >= formatForComparison(newDate)
                                        "
                                        @click="changeDeadline(task.id)"
                                        class="task-date"
                                    >
                                        {{ formatDate(task.deadline) }}
                                    </p>
                                    <p
                                        v-else
                                        class="p-border task-date"
                                        @click="changeDeadline(task.id)"
                                    >
                                        {{ formatDate(task.deadline) }}
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
        </div>
    </div>

    <Sidebar :tasks="tasks" :importantTask="importantTask" :unTasks="unTasks" />
</template>

<script>
export default {
    data() {
        return {
            form: {
                title: "",
            },
            tasks: [],
            subTasks: [],
            currentTaskId: null,
            subtaskTitle: "",
            date: null,
            time: null,
            newDate: null,
            importantTask: [],
            completedTasks: [],
            unTasks: [],
        };
    },

    created() {
        this.getTasks();
        this.getImportant();
        this.getCompletedTasks();
        this.getUncompletedTask();
    },
    mounted() {
        this.currentTime();
        this.newDate = new Date(); // Uzmi trenutni datum
        console.log("NewDate", this.formatForComparison(this.newDate));
        console.log(
            "Odabrani datum je ",
            this.formatForComparison(this.tasks.deadline)
        );
    },
    methods: {
        addTask() {
            const Data = {
                title: this.form.title,
                deadline: this.form.deadline,
                category_id: this.form.category_id,
            };
            console.log("Category Id je", Data);
            axios
                .post("/addTask", this.form)
                .then((response) => {
                    this.getTasks();
                })
                .catch((error) => {
                    if (error.response && error.response.status === 400) {
                        alert(error.response.data.error);
                    } else {
                        alert("Failed to add task.");
                    }
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
        deleteTask(taskId) {
            axios
                .post(`/deleteTask/${taskId}`)
                .then((response) => {
                    this.getTasks();
                    this.getImportant();
                    this.getCompletedTasks();
                })
                .catch((error) => {
                    console.error("Greška pri brisanju zadatka:", error);
                });
        },
        important(id) {
            axios.post(`/importantTask/${id}`).then((response) => {
                this.getTasks();
                this.getImportant();
            });
        },
        closeAccordation() {
            const offcanvasElement =
                document.getElementById("offcanvasExample");

            if (offcanvasElement) {
                const offcanvasInstance =
                    bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (offcanvasInstance) {
                    offcanvasInstance.hide();
                }
            }
        },
        getImportant() {
            axios
                .get("/getImportant")
                .then((response) => {
                    this.importantTask = response.data;
                    console.log("Ovo su taskovi", this.importantTask);
                    this.importantTask.forEach((task) => {
                        this.getSubtasks(task.id);
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        formatDate(date) {
            const options = { day: "numeric", month: "long", year: "numeric" };
            const formattedDate = new Intl.DateTimeFormat(
                "hr-HR",
                options
            ).format(new Date(date));

            const [day, month, year] = formattedDate.split(" ");

            return `${day} ${month.substring(0, 3)} ${year}`;
        },

        changeDeadline(taskId) {
            const task = this.tasks.find((task) => task.id === taskId);
            console.log("Promjena roka za task ID:", taskId);
            console.log("Trenutni datum jeeeeeeee", this.newDate);
            console.log("Odabrani datum jeee", task.deadline);
            if (task) {
                task.deadline = null;
                console.log(task.deadline);
            }
        },
        addDeadline(taskId) {
            axios
                .post(`/addDeadline/${taskId}`, {
                    deadline: this.tasks.find((task) => task.id === taskId)
                        .deadlineDate,
                })
                .then((response) => {
                    this.getTasks();
                })
                .catch((error) => {
                    console.error("Greška pri dodavanju roka:", error);
                });
        },
        formatCurrentDate(date) {
            const options = { day: "numeric", month: "long", year: "numeric" };
            const formattedDate = new Intl.DateTimeFormat(
                "hr-HR",
                options
            ).format(new Date(date));

            const [day, month, year] = formattedDate.split(" ");

            return `${day} ${month.substring(0, 3)} ${year}`;
        },
        formatForComparison(date) {
            const parsedDate = new Date(date);
            parsedDate.setHours(0, 0, 0, 0);

            if (isNaN(parsedDate)) {
                console.error("Neispravan datum:", date);
                return null;
            }

            return parsedDate;
        },
        deleteCompleted(taskId) {
            axios
                .post(`/deleteCompleted/${taskId}`)
                .then((response) => {
                    this.getTasks();
                    this.getImportant();
                    this.getCompletedTasks();
                })
                .catch((error) => {
                    console.error("Greška pri brisanju zadatka:", error);
                });
        },
        getCompletedTasks() {
            axios
                .get("/getCompletedTasks")
                .then((response) => {
                    this.completedTasks = response.data;
                    console.log("Ovo su završeni taskovi", this.completedTasks);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        getTomorrowDate() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1); // Postavi datum na sutrašnji
            tomorrow.setHours(0, 0, 0, 0); // Postavi vrijeme na 00:00:00 (bez vremena)
            return tomorrow;
        },
        getDaysDifference(date) {
            const today = new Date();
            const deadline = new Date(date);

            today.setHours(0, 0, 0, 0);
            deadline.setHours(0, 0, 0, 0);

            const diffTime = deadline - today;
            const diffDays = diffTime / (1000 * 3600 * 24); // Pretvori razliku u dane
            return diffDays;
        },

        getUncompletedTask() {
            axios
                .get("/getUncompletedTask")
                .then((response) => {
                    this.unTasks = response.data;
                    console.log("Ovo su NEDOVRŠENI TASKOVI", this.unTasks);
                    this.unTasks.forEach((task) => {
                        this.getSubtasks(task.id);
                    });
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
    position: absolute;
    left: 50%;
    margin-top: 3px;
    transform: translateX(-50%);
    font-size: 12px;
}

.task-date-input {
    position: absolute;
    left: 57%;
    margin-top: 3px;
    transform: translateX(-50%);
    font-size: 12px;
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
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}

.complete-accordion {
    border: 1px solid #175392;
}
</style>
