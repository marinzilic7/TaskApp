<script setup>
import Navbar from "../components/Navbar.vue";
</script>

<template>
    <div>
        <Navbar />
    </div>
    <h3 class="text-center mt-5">Tvoji timovi</h3>
    <div
        class="accordion accordion-flush border mt-5 ms-5 me-5"
        id="accordionFlushExample"
    >
        <div class="accordion-item" v-for="team in yourTeam" :key="team.id">
            <h2 class="accordion-header">
                <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne"
                    aria-expanded="false"
                    aria-controls="flush-collapseOne"
                    @click="getProject(team.team.id)"
                >
                    {{ team.team.name }}
                </button>
            </h2>
            <div
                id="flush-collapseOne"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample"
            >
                <div class="accordion-body">
                    <ul>
                        <li>
                            <h5>Projekti</h5>
                            <ul>
                                <li
                                    v-for="project in projects"
                                    :key="project.id"
                                    class="mt-3"
                                >
                                    <button
                                        class="btn btn-primary btn-sm"
                                        type="button"
                                        data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvasTop"
                                        aria-controls="offcanvasTop"
                                        @click="getTasks(project.id)"
                                    >
                                        {{ project.name }}
                                    </button>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- PRIKAZ TASKOVE ZA PROJEKETE -->

    <div
        class="offcanvas offcanvas-top"
        tabindex="-1"
        id="offcanvasTop"
        aria-labelledby="offcanvasTopLabel"
    >
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasTopLabel">
                Offcanvas top
            </h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close"
            ></button>
        </div>
        <div class="offcanvas-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID zadatka</th>
                        <th scope="col">Ime zadatka</th>
                        <th scope="col">Dodjeljeno</th>
                        <th scope="col">Rok zadatka</th>
                        <th scope="col">Datum kreiranja</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="task in tasks || []" :key="task.id">
                        <th scope="row">{{ task.id }}</th>
                        <td
                            class="d-flex align-items-center"
                            v-if="task.completed === 0"
                        >
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="flexRadioDefault"
                                    :id="'checkbox-' + task.id"
                                    :name="'task-' + task.id"
                                    @change="deleteProjectTasks(task.id)"
                                />
                            </div>
                            {{ task.name }}
                        </td>
                        <td class="d-flex" v-else>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="flexRadioDefault"
                                    :id="'checkbox-' + task.id"
                                    :name="'task-' + task.id"
                                    disabled
                                />
                            </div>
                            <p class="text-decoration-line-through text-danger">
                                {{ task.name }}
                            </p>
                        </td>
                        <td>
                            <p v-if="task.user === null">Nije određeno</p>
                            <p v-else>
                                {{
                                    task.user.firstName +
                                        " " +
                                        task.user.lastName || "N/A"
                                }}
                            </p>
                        </td>
                        <td>
                            <div v-if="task.deadline === null">
                                <input
                                    type="date"
                                    class="task-date border-0"
                                    v-model="deadlineDates[task.id]"
                                    @change="addDeadline(task.id)"
                                />
                                Nije određeno
                            </div>

                            <div v-else>
                                <input
                                    v-if="showInput"
                                    type="date"
                                    class="task-date border-0"
                                    v-model="deadlineDates[task.id]"
                                    @change="addDeadline(task.id)"
                                />
                                <p @click="showInputFunction(task.id)">
                                    {{ formatDate(task.deadline) }}
                                </p>
                            </div>
                        </td>

                        <td>{{ formatDate(task.created_at) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            yourTeam: [],
            projects: [],
            tasks: [],
            deadlineDates: {},
            showInput: false,
            teamId: null,
        };
    },
    created() {
        this.getYourTeam();
    },
    methods: {
        getYourTeam() {
            axios
                .get("/getYourTeam")
                .then((response) => {
                    this.yourTeam = response.data;
                    this.teamId = response.data.map((team) => team.team_id); // Dohvati sve team_id
                    console.log("Team IDs:", this.teamId);
                })
                .catch((error) => {
                    console.error("Error getting your team:", error);
                });
        },
        getProject(teamId) {
            axios
                .get(`/getProject/${teamId}`)
                .then((response) => {
                    this.projects = response.data;

                    // Iteracija kroz projekte da dohvatimo zadatke
                    this.projects = this.projects.map((project) => {
                        axios
                            .get(`/getTasksByProject/${project.id}`)
                            .then((taskResponse) => {
                                // Dodavanje tasks polja projektu
                                project.tasks = taskResponse.data;
                            })
                            .catch((error) => {
                                console.error(
                                    "Error getting tasks for project:",
                                    error
                                );
                            });
                        return project;
                    });

                    console.log("Ovo su projekti s taskovima", this.projects);
                })
                .catch((error) => {
                    console.error("Error getting projects:", error);
                });
        },
        getTasks(projectId) {
            axios
                .get(`/getTasksByProjectMember/${projectId}`)
                .then((response) => {
                    console.log("Ovo su taskovi", response.data);
                    this.tasks = response.data;
                })
                .catch((error) => {
                    console.error("Error getting tasks:", error);
                });
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
        deleteProjectTasks(id) {
            axios
                .post(`/deleteProjectTasks/${id}`)
                .then((response) => {
                    console.log(response.data);
                    this.getProject(this.teamId);
                })
                .catch((error) => {
                    console.error("Error deleting task:", error);
                });
        },
    },
};
</script>

<style></style>
