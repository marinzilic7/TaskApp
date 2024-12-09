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
        <h5 class="mt-2">Važno</h5>
    </div>
    <div class="ms-5">
        <p class="date-text ms-4 text-muted">{{ date }}</p>
    </div>
    <div>
        <form
            class="ms-5 me-5"
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

                <button
                    @click="addImportant()"
                    class="btn add-button"
                    type="button"
                >
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
    <div class="ms-5 me-5 mt-3" v-for="task in tasks" :key="task.id">
        <div class="border" v-if="task.completed === 0">
            <div class="d-flex gap-2 mt-3 ms-3">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="flexRadioDefault"
                        :id="'checkbox-' + task.id"
                        :name="'task-' + task.id"
                        @change="deleteImportant(task.id)"
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
                        class=" p-border task-date"
                    >
                        {{ formatDate(task.deadline) }}
                    </p>
                    <p
                        v-else
                        class="task-date"
                        @click="changeDeadline(task.id)"
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
                        <p class="text-primary fw-bold mt-3">
                            {{ completedTasks.length }}
                        </p>
                    </div>
                </button>
            </h2>
            <div
                id="flush-collapseOne"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample"
            >
                <div class="accordion-body">
                    <div v-if="completedTasks.length > 0">
                        <div
                            class="ms-5 me-5 mt-3"
                            v-for="task in completedTasks"
                            :key="task.id"
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
                                            @change="deleteCompleted(task.id)"
                                        />
                                    </div>
                                    <p class="text-decoration-line-through">
                                        {{ task.title }}
                                    </p>

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
                                                formatForComparison(
                                                    task.deadline
                                                ) &&
                                                formatForComparison(
                                                    task.deadline
                                                ) < formatForComparison(newDate)
                                            "
                                            @click="changeDeadline(task.id)"
                                            class="p-border task-date"
                                        >
                                            {{ formatDate(task.deadline) }}
                                        </p>
                                        <p
                                            v-else
                                            class=" task-date"
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
            currentTaskId: null,
            subtaskTitle: "",
            date: null,
            time: null,
            noImportant: [],
            completedTasks: [],
            newDate: null,
            unTasks: [],
            importantTask: [],
            progress: 0,
            totalTasks: 0,
        };
    },
    created() {
        this.getImportant();
        this.getTasks();
        this.getCompletedImportantTasks();
        this.getUncompletedTask();
        this.updateProgress();
    },
    mounted() {
        this.currentTime();
        this.newDate = new Date();
    },
    methods: {
        addImportant() {
            const Data = {
                title: this.form.title,
            };

            axios
                .post("/addImportant", this.form)
                .then((response) => {
                    this.getTasks();
                    this.getImportant();
                    this.getUncompletedTask();
                    this.updateProgress();
                })
                .catch((error) => {
                    if (error.response && error.response.status === 400) {
                        alert(error.response.data.error);
                    } else {
                        alert("Failed to add task.");
                    }
                });
        },

        getImportant() {
            axios
                .get("/getImportant")
                .then((response) => {
                    this.tasks = response.data;
                    console.log("Ovo su taskovi", this.tasks);
                    this.totalTasks = this.tasks.length;
                    this.tasks.forEach((task) => {
                        this.getSubtasks(task.id);
                    });
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.updateProgress();
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
        deleteImportant(taskId) {
            // Za brze

            // // Napravite kopiju trenutnog stanja za slučaj greške
            // const previousTasks = [...this.tasks];

            // // Privremeno uklonite task s frontend-a
            // this.tasks = this.tasks.filter((task) => task.id !== taskId);

            axios
                .post(`/deleteTask/${taskId}`)
                .then((response) => {
                    this.getImportant();
                    this.getCompletedImportantTasks();
                    this.getUncompletedTask();
                    this.updateProgress();
                })
                .catch((error) => {
                    console.error("Greška pri brisanju zadatka:", error);
                });
        },
        important(id) {
            const task = this.tasks.find((task) => task.id === id);
            if (task) {
                task.isImportant = !task.isImportant;
            }

            axios.post(`/importantTask/${id}`).then((response) => {
                this.getImportant();
            });
        },
        closeAccordation() {
            const offcanvasElement =
                document.getElementById("offcanvasExample");
            const backdropElement = document.querySelector(
                ".offcanvas-backdrop"
            );
            offcanvasElement.classList.remove("show");
            if (backdropElement) {
                backdropElement.remove();
            }
        },
        getTasks() {
            axios
                .get("/getTasks")
                .then((response) => {
                    this.noImportant = response.data;
                    console.log("Ovo su taskovi", this.noImportant);
                    console.log(this.noImportant.length);
                    this.noImportant.forEach((task) => {
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
                    this.getImportant();
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
                    this.getCompletedImportantTasks();
                    this.getUncompletedTask();
                    this.updateProgress();
                })
                .catch((error) => {
                    console.error("Greška pri brisanju zadatka:", error);
                });
        },
        getCompletedImportantTasks() {
            axios
                .get("/getCompletedImportantTasks")
                .then((response) => {
                    this.completedTasks = response.data;
                    console.log("Ovo su završeni taskovi", this.completedTasks);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getUncompletedTask() {
            axios
                .get("/getUncompletedTask")
                .then((response) => {
                    this.unTasks = response.data;
                    console.log("Ovo su NEDOVRŠENI TASKOVI", this.unTasks);

                })
                .catch((error) => {
                    console.log(error);
                });
        },
        updateProgress() {
            const completedTasksCount = this.tasks.filter(
                (task) => task.completed === true || task.completed === 1
            ).length;

            console.log("Ukupan broj zadataka:", this.totalTasks);
            console.log("Broj završenih zadataka:", completedTasksCount);

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

.task-date {
    position: absolute;
    left: 50%;
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
}

.complete-accordion {
    border: 1px solid #175392;
}
</style>
