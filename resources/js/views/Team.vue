<script setup>
import Navbar from "../components/Navbar.vue";
import Sidebar from "../components/Sidebar.vue";
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
        <h5 class="mt-2">Timski rad</h5>
    </div>

    <div>
        <form @submit.prevent="addTeam()" class="ms-5 me-5 z-3">
            <div class="input-group mb-3 mt-3">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Ime vašeg tima"
                    v-model="team.name"
                />

                <button class="btn add-button" type="submit">
                    <i class="bi bi-plus text-light add-btn"></i>
                </button>
            </div>
        </form>
    </div>
    <br />
    <hr />

    <!-- Timovi -->
    <div class="container">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Ime tima</th>
                    <th scope="col">Lider</th>
                    <th scope="col">Datum</th>
                    <th scope="col">Uredi</th>
                    <th scope="col">Izbrisi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="teams.length === 0">
                    <td colspan="6" class="text-center">
                        Trenutno nije dodan nijedan team
                    </td>
                </tr>
                <tr v-else v-for="team in teams" :key="team.id">
                    <th scope="row">{{ team.id }}</th>
                    <td>
                        <RouterLink :to="'/team/' + team.id">{{
                            team.name
                        }}</RouterLink>
                    </td>
                    <td>
                        {{ team.user.firstName + " " + team.user.lastName }}
                    </td>
                    <td>{{ formatDate(team.created_at) }}</td>
                    <td>
                        <button
                            @click="openUpdateModal(team)"
                            class="btn btn-sm btn-outline-warning"
                        >
                            <i class="bi bi-pencil"></i>
                        </button>
                    </td>
                    <td>
                        <button
                            class="btn btn-sm btn-outline-danger"
                            @click="deleteTeam(team.id)"
                        >
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal za update -->

    <div
        class="modal fade"
        id="updateTeam"
        tabindex="-1"
        aria-labelledby="updateTeamLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateTeamLabel">Uredi tim</h5>
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
                            <label for="teamName" class="form-label"
                                >Ime tima</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="teamName"
                                v-model="updatedTeam.name"
                            />
                        </div>
                    </form>
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
                        @click="updateTeam"
                        type="button"
                        class="btn btn-primary"
                    >
                        Spremi
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
export default {
    data() {
        return {
            team: {
                name: "",
            },
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
        this.getTeams();
    },
    methods: {
        addTeam() {
            const Data = {
                name: this.team.name,
            };
            axios
                .post("addTeam", Data)
                .then((response) => {
                    console.log(response.data);
                    this.getTeams();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getTeams() {
            axios
                .get("getTeams")
                .then((response) => {
                    this.teams = response.data;

                    console.log("Ovo su teamovi -->", this.teams);
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

        openUpdateModal(team) {
            this.updatedTeam = { ...team };
            this.updateName = team.name;

            const modal = new bootstrap.Modal(
                document.getElementById("updateTeam")
            );
            modal.show();
        },

        updateTeam() {
            axios
                .post(`updateTeam/${this.updatedTeam.id}`, this.updatedTeam)
                .then((response) => {
                    console.log("Ažuriran tim:", this.updatedTeam);

                    this.getTeams();

                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("updateTeam")
                    );
                    modal.hide();
                })
                .catch((error) => {
                    console.error("Greška prilikom ažuriranja:", error);
                });
        },

        deleteTeam(id) {
            axios
                .post(`deleteTeam/${id}`)
                .then((response) => {
                    console.log("Obrisani tim:", id);


                    this.getTeams();
                })
                .catch((error) => {
                    console.error("Greška prilikom brisanja:", error);
                });
        },
    },
};
</script>

<style>
.add-button {
    background-color: #175392;
    border: none;
}

.add-button:hover {
    background-color: #175392;
}

.bi-list {
    font-size: 1.7rem;
}

.add-btn {
    font-size: 1.5rem;
}

th,
td {
    text-align: center;
    font-size: 16px;
    font-family: "Poppins", sans-serif;
}

td {
    letter-spacing: 1px;
}
</style>
