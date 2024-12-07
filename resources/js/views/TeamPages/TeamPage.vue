<script setup>
import Navbar from "@/components/Navbar.vue";
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
                        <button @click="addMemberToTeam(member.id)">
                            Dodaj u tim
                        </button>
                    </li>
                </ul>
            </div>

            <div v-if="selectedMembers.length">
                <h3>Odabrani članovi:</h3>
                <ul>
                    <li v-for="member in selectedMembers" :key="member.id">
                        {{ member.first_name }} {{ member.last_name }}
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
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Dodaj zadatak za projekt"
                        />
                        <button class="btn btn-primary btn-sm add-button">
                            <i class="bi bi-plus text-light add-btn"></i>
                        </button>
                    </div>
                    <br />
                    <hr />

                    <!-- Table sa zadacima za projekt -->

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ID zadatka</th>
                                <th scope="col">Ime zadataka</th>
                                <th scope="col">Dodjeljeno</th>
                                <th scope="col">Rok zadatka</th>
                                <th scope="col">Datum kreiranja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            teamId: this.$route.params.id, // Dohvaćanje ID-a iz URL-a
            team: {}, // Objekt za pohranu podataka tima
            project: {
                name: "",
                description: "",
            },
            searchQuery: "",
            members: [],
            selectedMembers: [],
            projects: [],
        };
    },
    created() {
        // Poziv metode za dohvat tima nakon što je komponenta kreirana
        this.getTeamData(this.teamId);
        this.getProject(this.teamId);
    },
    methods: {
        async getTeamData(id) {
            try {
                const response = await axios.get(`/getTeamData/${id}`); // API endpoint za dohvat podataka o grupi
                this.team = response.data; // Pohranjivanje podataka u 'team' objekt
                console.log("Ovo su podaci grupe", this.team); // Ispis podataka u konzoli
            } catch (error) {
                console.error("Error fetching group data:", error); // Obrada pogreške
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
                        query: this.searchQuery, // Prosljeđujemo upisani tekst pretrage
                    },
                });
                this.members = response.data; // Spremamo rezultate pretrage
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

                    console.log("Ovo su projekti", this.projects);
                })
                .catch((error) => {
                    console.error("Error getting  project:", error);
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

.add-btn {
    font-size: 1.5rem;
}
</style>
