<script setup>
import Navbar from "@/components/Navbar.vue";
import Sidebar from "../../components/Sidebar.vue";
</script>

<template>
    <div>
        <Navbar />
    </div>
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
        <h5 class="mt-2">{{ team.name }}</h5>
    </div>

    <div>
        <form @submit.prevent="addProject()" class="ms-5 me-5 z-3">
            <div class="input-group mb-3 mt-3">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Dodaj projekt"
                    v-model="project.name"
                />
                <input
                    type="text"
                    class="form-control"
                    placeholder="Opis projekta"
                    v-model="project.description"
                />
                <button class="btn add-button" type="submit">
                    <i class="bi bi-plus text-light add-btn"></i>
                </button>
            </div>
        </form>
        <div class="d-flex ms-5">
            <input
                type="text"
                v-model="searchQuery"
                @input="searchMembers"
                placeholder="Pretraži članove..."
            />

            <div v-if="members.length">
                <ul>
                    <li v-for="member in members" :key="member.id">
                        {{ member.firstName }} {{ member.lastName }}
                        <button
                            class="btn btn-sm btn-primary"
                            @click="addMemberToTeam(member.id)"
                        >
                            Dodaj u tim
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <br />
        <hr />
    </div>

    <div
        class="accordion accordion-flush ms-5 me-5 border"
        id="accordionFlushExample"
        v-for="(project, index) in projects"
        :key="project.id"
    >
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button
                    class="accordion-button collapsed"
                    type="button"
                    :data-bs-toggle="'collapse'"
                    :data-bs-target="'#flush-collapse-' + index"
                    aria-expanded="false"
                    :aria-controls="'flush-collapse-' + index"
                >
                    {{ project.name }}
                </button>
            </h2>
            <div
                :id="'flush-collapse-' + index"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample"
            >
                <div class="accordion-body">
                    <div class="input-group mb-3 mt-3">
                        <div class="d-flex justify-content-between col-12">
                            <div>
                                <form
                                    @submit.prevent="
                                        addTaskToProject(project.id)
                                    "
                                    class="ms-5 me-5 d-flex z-3"
                                >
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Dodaj zadatak za projekt"
                                        v-model="taskproject.name"
                                    />
                                    <button
                                        type="sumbit"
                                        class="btn btn-primary btn-sm add-button ms-2"
                                    >
                                        <i
                                            class="bi bi-plus text-light add-btn"
                                        ></i>
                                    </button>
                                </form>
                            </div>

                            <div class="d-flex align-items-center">
                                <i
                                    class="bi bi-person-plus fs-3 text-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#exampleModal"
                                    data-bs-whatever="@mdo"
                                    style="cursor: pointer; margin-left: 10px"
                                    title="Dodijeli zadatak"
                                ></i>
                            </div>
                        </div>
                    </div>
                    <br />
                    <hr />

                    <!-- Table sa zadacima za projekt -->

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
                            <tr v-if="project.tasks.length === 0">
                                <td colspan="6" class="text-center">
                                    Trenutno nije dodan nijedan zadatak
                                </td>
                            </tr>
                            <tr
                                v-else
                                v-for="task in project.tasks || []"
                                :key="task.id"
                            >
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
                                            @change="
                                                deleteProjectTasks(task.id)
                                            "
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
                                    <p
                                        class="text-decoration-line-through text-danger"
                                    >
                                        {{ task.name }}
                                    </p>
                                </td>
                                <td>
                                    <p v-if="task.user === null">
                                        Nije određeno
                                    </p>
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
        </div>
    </div>

    <!-- Modal za dodjeljivanje zadatka -->

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
                        Dodjeli zadatak
                    </h1>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label"
                                >Odaberi clana iz tima:</label
                            >
                            <select
                                v-model="odabraniClan"
                                class="form-select mt-3"
                                aria-label="Odaberi člana teama"
                            >
                                <option selected>Odaberi člana teama</option>
                                <option
                                    v-for="member in memberGroup"
                                    :key="member.id"
                                    :value="member.user_id"
                                >
                                    {{
                                        member.user.firstName +
                                        " " +
                                        member.user.lastName
                                    }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label"
                                >Odaberi projekt:</label
                            >
                            <select
                                v-model="chosenProject"
                                @change="getTaskByProject(chosenProject)"
                                class="form-select mt-3"
                                aria-label="Odaberi člana teama"
                            >
                                <option selected>
                                    Odaberi projekt za clana
                                </option>
                                <option
                                    v-for="project in projects"
                                    :key="project.id"
                                    :value="project.id"
                                >
                                    {{ project.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label"
                                >Odaberi zadatak iz projekta:</label
                            >
                            <select
                                v-model="chosenTask"
                                class="form-select mt-3"
                                aria-label="Odaberi člana teama"
                            >
                                <option selected>
                                    Odaberi zadatak za clana
                                </option>
                                <option
                                    v-for="task in tasks"
                                    :key="task.id"
                                    :value="task.id"
                                >
                                    {{ task.name }}
                                </option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal"
                    >
                        Close
                    </button>

                    <button
                        v-if="odabraniClan"
                        class="btn btn-primary btn-sm"
                        @click="assignTaskToMember"
                    >
                        Dodijeli zadatak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <Sidebar
        :tasks="tasks"
        :importantTask="importantTask"
        :unTasks="unTasks"
        :teams="teams"
        :updatedTeam="updatedTeam"
    />
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            teamId: this.$route.params.id,
            team: {},
            project: {
                name: "",
                description: "",
            },
            searchQuery: "",
            members: [],
            selectedMembers: [],
            projects: [],
            taskproject: {
                name: "",
            },
            showSelect: false,
            memberGroup: [],
            odabraniClan: null,
            chosenTask: null,
            chosenProject: null,
            tasks: [],
            deadlineDates: {},
            showInput: false,
            pokaziClanove: false,

            //

            teams: [],
            updatedTeam: {
                id: null,
                name: "",
            },
            tasks: [],
            importantTask: [],
            unTasks: [],
        };
    },
    created() {
        this.getTeamData(this.teamId);
        this.getProject(this.teamId);
        this.getMemberGroup(this.teamId);
        this.getTaskByProject(this.projectId);
    },
    methods: {
        async getTeamData(id) {
            try {
                const response = await axios.get(`/getTeamData/${id}`);
                this.team = response.data;
                console.log("Ovo su podaci grupe", this.team);
            } catch (error) {
                console.error("Error fetching group data:", error);
            }
        },
        addProject() {
            const Data = {
                name: this.project.name,
                description: this.project.description,
            };
            axios
                .post(`/addProject/${this.teamId}`, Data)
                .then((response) => {
                    console.log(response.data);
                    this.getProject(this.teamId);
                })
                .catch((error) => {
                    console.error("Error adding project:", error);
                });
        },

        async searchMembers() {
            try {
                const response = await axios.get("/search-members", {
                    params: {
                        query: this.searchQuery,
                    },
                });
                this.members = response.data;
            } catch (error) {
                console.error("Greška pri pretrazi članova", error);
            }
        },
        addMemberToTeam(id) {
            console.log("Ovo je id membera", id);
            console.log("Ovo je id teama ", this.teamId);
            const Data = {
                user_id: id,
            };
            axios
                .post(`/addMember/${this.teamId}`, Data)
                .then((response) => {
                    console.log(response.data);
                    this.getMemberGroup(this.teamId);
                    this.pokaziClanove = true;
                })
                .catch((error) => {
                    console.error("Error adding project:", error);
                });
        },

        getProject(teamId) {
            axios
                .get(`/getProject/${teamId}`)
                .then((response) => {
                    this.projects = response.data;

                    this.projects = this.projects.map((project) => {
                        axios
                            .get(`/getTasksByProject/${project.id}`)
                            .then((taskResponse) => {
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

        addTaskToProject(id) {
            const Data = {
                name: this.taskproject.name,
                team_id: this.teamId,
            };
            axios
                .post(`/addTaskToProject/${id}`, Data)
                .then((response) => {
                    console.log(response.data);
                    this.taskproject.name = "";
                    axios
                        .get(`/getTasksByProject/${id}`)
                        .then((taskResponse) => {
                            const project = this.projects.find(
                                (p) => p.id === id
                            );
                            if (project) {
                                project.tasks = taskResponse.data;
                            }
                        })
                        .catch((error) => {
                            console.error(
                                "Error refreshing tasks for project:",
                                error
                            );
                        });
                })
                .catch((error) => {
                    console.error("Error adding task:", error);
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
        toggleMemberSelect() {
            this.showSelect = !this.showSelect;
        },

        getMemberGroup(id) {
            axios
                .get(`/getMemberGroup/${id}`)
                .then((response) => {
                    this.memberGroup = response.data;
                    console.log("Ovo su članovi tima", this.memberGroup);
                })
                .catch((error) => {
                    console.error("Error getting members:", error);
                });
        },
        assignTaskToMember() {
            if (this.odabraniClan) {
                console.log("Member ID ", this.odabraniClan);
                console.log("Odabrani task", this.chosenTask);
                const Data = {
                    projectId: this.chosenProject,
                    member_id: this.odabraniClan,
                    task_id: this.chosenTask,
                };

                axios
                    .post(`/assignTaskToMember/${this.chosenProject}`, Data)
                    .then((response) => {
                        console.log(
                            "Timski član uspješno dodijeljen zadatku",
                            response
                        );
                        this.dohvatiTaskove(this.chosenProject);
                        const modalElement =
                            document.getElementById("exampleModal");
                        const modalInstance =
                            bootstrap.Modal.getInstance(modalElement);
                        modalInstance.hide();
                    })
                    .catch((error) => {
                        console.error("Error assigning task:", error);
                    });
            }
        },
        getTaskByProject(id) {
            axios
                .get(`/getTasksByProject/${id}`)
                .then((response) => {
                    const project = this.projects.find((p) => p.id === id);
                    if (project) {
                        this.tasks = response.data;
                    }
                })
                .catch((error) => {
                    console.error("Error refreshing tasks for project:", error);
                });
        },

        dohvatiTaskove(id) {
            this.projects = this.projects.map((project) => {
                axios
                    .get(`/getTasksByProject/${project.id}`)
                    .then((taskResponse) => {
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
        },

        addDeadline(id) {
            const Data = {
                deadline: this.deadlineDates[id],
            };
            this.showInput = false;
            axios
                .post(`/addTaskProjectDeadline/${id}`, Data)
                .then((response) => {
                    console.log(response.data);
                    this.getProject(this.teamId);
                })
                .catch((error) => {
                    console.error("Error adding deadline:", error);
                });
        },
        showInputFunction(id) {
            this.showInput = !this.showInput;
        },
    },
};
</script>

<style>
.add-button {
    background-color: #175392;
    border: none;
}

.add-btn {
    font-size: 1.5rem;
}
</style>
